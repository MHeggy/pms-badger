<?php

namespace App\Controllers;

use App\Models\TimesheetsModel;

class TimesheetsController extends BaseController {
    protected $timesheetsModel;
    protected $session;

    public function __construct() {
        $this->timesheetsModel = new TimesheetsModel();
        $this->session = \Config\Services::session();
    }

    public function index() {
        $userId = auth()->id();

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        return view ('PMS/payroll.php', [
            'userId' => $userId
        ]);
    }

    public function submit() {
        $userId = auth()->id();
        $weekOf = $this->request->getPost('week');
        $entries = $this->getTimesheetEntriesFromRequest($userId);

        $totalHours = array_sum(array_column($entries, 'totalHours'));

        $data = [
            'userID' => $userId,
            'weekOf' => $weekOf,
            'totalHours' => $totalHours,
        ];

        $success = $this->timesheetsModel->insertTimesheet($data);

        if ($success) {
            $timesheetId = $success;
            $successEntries = $this->timesheetsModel->insertTimesheetEntries($timesheetId, $entries);

            if ($successEntries) {
                $this->session->setFlashdata('success_message', 'Timesheet submitted successfully.');
            } else {
                $this->session->setFlashdata('error_message', 'Unable to submit timesheet, please try again.');
            }
        } else {
            $this->session->setFlashdata('error_message', 'Unable to submit timesheet, please try again.');
        }

        return redirect()->to('/dashboard');
    }

    public function viewTimesheets($userId) {
        $user = $this->timesheetsModel->getUserInfo($userId);
        $timesheets = $this->timesheetsModel->getUserTimesheets($userId);
        
        return view('PMS/user_timesheets.php', [
            'user' => $user,
            'timesheets' => $timesheets,
        ]);
    }

    public function viewTimesheet($timesheetId) {
        $timesheet = $this->timesheetsModel->find($timesheetId);
        $entries = $this->getTimesheetEntries($timesheetId);

        if (!$timesheet) {
            return redirect()->back()->with('error_message', 'Timesheet not found.');
        }

        return view('PMS/timesheet_details.php', [
            'timesheet' => $timesheet,
            'entries' => $entries,
        ]);
    }

    public function editTimesheet($timesheetId)
    {
        $timesheet = $this->timesheetsModel->find($timesheetId);
        $entries = $this->getTimesheetEntries($timesheetId);

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('PMS/edit_timesheet.php', [
            'timesheet' => $timesheet,
            'entries' => $entries,
            'daysOfWeek' => $daysOfWeek,
        ]);
    }

    public function updateTimesheet() {
        $timesheetId = $this->request->getPost('id');
        $data = [
            'weekOf' => $this->request->getPost('week'),
        ];

        $success = $this->timesheetsModel->updateTimesheet($timesheetId, $data);

        $entries = $this->getTimesheetEntriesFromRequest($timesheetId);
        $successEntries = $this->timesheetsModel->updateTimesheetEntries($timesheetId, $entries);

        if ($success && $successEntries) {
            $this->session->setFlashdata('success_message', 'Timesheet updated successfully.');
        } else {
            $this->session->setFlashdata('error_message', 'Unable to update timesheet, please try again.');
        }

        return redirect()->to('/dashboard');
    }

    public function deleteTimesheet($timesheetId)
    {
        $timesheet = $this->timesheetsModel->find($timesheetId);

        if (!$timesheet) {
            return redirect()->back()->with('error_message', 'Timesheet not found.');
        }

        $this->timesheetsModel->deleteTimesheetEntries($timesheetId);
        $success = $this->timesheetsModel->delete($timesheetId);

        if ($success) {
            return redirect()->back()->with('success_message', 'Timesheet deleted successfully.');
        } else {
            return redirect()->back()->with('error_message', 'Failed to delete timesheet. Please try again.');
        }
    }

    private function getTimesheetEntriesFromRequest() {
        $entries = [];
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($daysOfWeek as $day) {
            $hours = $this->request()->getPost((strtolower($day) . 'Hours'));
            $totalHours = $this->calculateTotalHoursForDay($hours);

            if ($hours) {
                $entries[] = [
                    'projectNumber' => $this->request->getPost('projectNumber'),
                    'projectName' => $this->request->getPost('projectName'),
                    'activityDescription' => $this->request->getPost('activityDescription'),
                    'mondayHours' => $day === 'Monday' ? $hours : null,
                    'tuesdayHours' => $day === 'Tuesday' ? $hours : null,
                    'wednesdayHours' => $day === 'Wednesday' ? $hours : null,
                    'thursdayHours' => $day === 'Thursday' ? $hours : null,
                    'fridayHours' => $day === 'Friday' ? $hours : null,
                    'saturdayHours' => $day === 'Saturday' ? $hours : null,
                    'sundayHours' => $day === 'Sunday' ? $hours : null,
                    'totalHours' => $totalHours
                ];
            }
        }

        return $entries;
    }
}