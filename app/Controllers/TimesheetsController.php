<?php

namespace App\Controllers;

use App\Models\TimesheetsModel;
use App\Models\NotificationModel;

class TimesheetsController extends BaseController
{
    protected $timesheetsModel;
    protected $notificationModel;

    public function __construct()
    {
        $this->timesheetsModel = new TimesheetsModel();
        $this->session = \Config\Services::session();
        $this->notificationModel = new NotificationModel();
    }

    public function submit() {
        $data = [
            'user_id' => $this->request->getPost('user-id'),
            'week_of' => $this->request->getPost('week'),
        ];

        // Process daily hours worked
        $dailyHours = [];
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($daysOfWeek as $day) {
            $dailyHours[$day] = $this->request->getPost(strtolower($day));
        }
        // Insert the timesheet data
        $success = $this->timesheetsModel->insertTimesheet($data, $dailyHours);

        if ($success) {
            // Set a success flash message
            $this->session->setFlashdata('success_message', 'Timesheet submitted successfully!');
        } else {
            // Set an error flash message if insertion failed
            $this->session->setFlashdata('error_message', 'Failed to submit timesheet. Please try again.');
        }

        return redirect()->to('/dashboard')->with('success_message', 'Timesheet submitted successfully!');
    }

    public function index() {
        $userId = auth()->id();
        $notifications = $this->notificationModel->getUnreadNotifications($userId);
        return view('PMS/payroll.php', [
            'userId' => $userId,
            'notifications' => $notifications
        ]);
    }


    public function viewTimesheets($userId) {
        // Fetch the user information
        $user = $this->timesheetsModel->getUserInfo($userId);
        // Fetch the timesheets for the user
        $timesheets = $this->timesheetsModel->getUserTimesheets($userId);

        // fetch notifications
        $notifications = $this->notificationModel->getUnreadNotifications($userId);

        $role = auth()->user()->getGroups();

        return view('PMS/user_timesheets.php', [
            'user' => $user,
            'timesheets' => $timesheets,
            'role' => $role,
            'notifications' => $notifications
        ]);
    }

    // function edit the user's timesheet
    public function editTimesheet($timesheetId) {
        // Fetch timesheet details for editing.
        $timesheet = $this->timesheetsModel->find($timesheetId);

        // Fetch daily hours worked for the timesheet
        $dailyHours = $this->timesheetsModel->getDailyHours($timesheetId);

        // convert keys in the array to lowercase.
        $dailyHours = array_change_key_case($dailyHours, CASE_LOWER);

        // Log the contents of $dailyHours
        log_message('debug', 'Daily Hours: ' . print_r($dailyHours, true));

        // Define days of the week
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        // Pass the timesheet and daily hours data to the view
        return view('PMS/edit_timesheet.php', [
            'timesheet' => $timesheet,
            'dailyHours' => $dailyHours,
            'daysOfWeek' => $daysOfWeek,
        ]);
    }

    public function updateTimesheet() {
        // get the timesheet ID from the form.
        $timesheetId = $this->request->getPost('id');

        // get the week of the timesheet from the form.
        $weekOf = $this->request->getPost('week');

        $data = [
            'week_of' => $weekOf,
        ];

        $success = $this->timesheetsModel->updateTimesheet($timesheetId, $data);

        // Process daily hours worked
        $dailyHours = [];
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($daysOfWeek as $day) {
            $dailyHours[$day] = $this->request->getPost(strtolower($day));
        }

        $successDailyHours = $this->timesheetsModel->updateDailyHours($timesheetId, $dailyHours);

        if ($success && $successDailyHours) {
            $this->session->setFlashdata('success_message', 'Timesheet updated successfully');
        } else {
            $this->session->setFlashdata('error_message', 'Failed to update timesheet. Please try again or contact an administrator.');
        }

        return redirect()->to('/dashboard')->with('success_message', 'Timesheet updated successfully!');
    }

    // function to delete timesheet
    public function deleteTimesheet($timesheetId) {
        // check if the timesheet exists first
        $timesheet = $this->timesheetsModel->find($timesheetId);

        if (!$timesheet) {
            // timesheet not found.
            return redirect()->back()->with('error_message', 'Timesheet was unable to be found.');
        }

        // Delete related daily hours records first
        $this->timesheetsModel->deleteDailyHours($timesheetId);

        // Then, delete the timesheet record
        $success = $this->timesheetsModel->delete($timesheetId);

        if ($success) {
            // deletion successful
            return redirect()->back()->with('success_message', 'Timesheet deleted successfully.');
        } else {
            return redirect()->back()->with('error_message', 'Failed to delete timesheet. Please try again.');
        }
    }
}
