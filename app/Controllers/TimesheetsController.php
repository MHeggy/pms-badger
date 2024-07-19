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

    public function index() {
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        $notifications = $this->notificationModel->getUnreadNotifications($userId);
        return view('PMS/payroll.php', [
            'userId' => $userId,
            'notifications' => $notifications
        ]);
    }


    public function submit()
    {
        $data = [
            'user_id' => $this->request->getPost('user-id'),
            'week_of' => $this->request->getPost('week'),
        ];

        $dailyHours = [];
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($daysOfWeek as $day) {
            $dailyHours[strtolower($day)] = $this->request->getPost(strtolower($day));
        }

        $success = $this->timesheetsModel->insertTimesheet($data, $dailyHours);

        if ($success) {
            $this->session->setFlashdata('success_message', 'Timesheet submitted successfully!');
        } else {
            $this->session->setFlashdata('error_message', 'Failed to submit timesheet. Please try again.');
        }

        return redirect()->to('/dashboard');
    }

    public function viewTimesheets($userId)
    {
        $user = $this->timesheetsModel->getUserInfo($userId);
        $timesheets = $this->timesheetsModel->getUserTimesheets($userId);
        $notifications = $this->notificationModel->getUnreadNotifications($userId);

        return view('PMS/user_timesheets.php', [
            'user' => $user,
            'timesheets' => $timesheets,
            'notifications' => $notifications
        ]);
    }

    public function viewTimesheet($timesheetId)
    {
        $timesheet = $this->timesheetsModel->find($timesheetId);
        $dailyHours = $this->timesheetsModel->getDailyHours($timesheetId);

        if (!$timesheet) {
            return redirect()->back()->with('error_message', 'Timesheet not found.');
        }

        return view('PMS/timesheet_details.php', [
            'timesheet' => $timesheet,
            'dailyHours' => $dailyHours,
        ]);
    }

    public function editTimesheet($timesheetId)
    {
        $timesheet = $this->timesheetsModel->find($timesheetId);
        $dailyHours = $this->timesheetsModel->getDailyHours($timesheetId);

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('PMS/edit_timesheet.php', [
            'timesheet' => $timesheet,
            'dailyHours' => $dailyHours,
            'daysOfWeek' => $daysOfWeek,
        ]);
    }

    public function updateTimesheet()
    {
        $timesheetId = $this->request->getPost('id');
        $data = ['week_of' => $this->request->getPost('week')];

        $success = $this->timesheetsModel->updateTimesheet($timesheetId, $data);

        $dailyHours = [];
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($daysOfWeek as $day) {
            $dailyHours[strtolower($day)] = $this->request->getPost(strtolower($day));
        }

        $successDailyHours = $this->timesheetsModel->updateDailyHours($timesheetId, $dailyHours);

        if ($success && $successDailyHours) {
            $this->session->setFlashdata('success_message', 'Timesheet updated successfully');
        } else {
            $this->session->setFlashdata('error_message', 'Failed to update timesheet. Please try again.');
        }

        return redirect()->to('/dashboard');
    }

    public function deleteTimesheet($timesheetId)
    {
        $timesheet = $this->timesheetsModel->find($timesheetId);

        if (!$timesheet) {
            return redirect()->back()->with('error_message', 'Timesheet was unable to be found.');
        }

        $this->timesheetsModel->deleteDailyHours($timesheetId);
        $success = $this->timesheetsModel->delete($timesheetId);

        if ($success) {
            return redirect()->back()->with('success_message', 'Timesheet deleted successfully.');
        } else {
            return redirect()->back()->with('error_message', 'Failed to delete timesheet. Please try again.');
        }
    }
}