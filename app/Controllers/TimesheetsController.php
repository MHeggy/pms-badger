<?php

namespace App\Controllers;

use App\Models\TimesheetsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

        return view('PMS/payroll', [
            'userId' => $userId
        ]);
    }

    public function submit() {
        $userId = auth()->id();
        $weekOf = $this->request->getPost('week');
        $totalHours = $this->request->getPost('totalHours');
        $entries = $this->getTimesheetEntriesFromRequest();

        $timesheetData = [
            'userID' => $userId,
            'weekOf' => $weekOf,
            'totalHours' => $totalHours,
            'createdAt' => date('Y-m-d H:i:s'),
            'updatedAt' => date('Y-m-d H:i:s')
        ];

        try {
            $timesheetId = $this->timesheetsModel->insertTimesheet($timesheetData);
        } catch (\Exception $e) {
            $this->session->setFlashdata('error_message', 'Timesheet could not be submitted successfully, please try again.');
            return redirect()->to('/dashboard');
        }

        try {
            foreach ($entries as $entry) {
                $entry['timesheetID'] = $timesheetId;
                $entry['createdAt'] = date('Y-m-d H:i:s');
                $entry['updatedAt'] = date('Y-m-d H:i:s');
                $this->timesheetsModel->insertTimesheetEntry($entry);
            }
        } catch (\Exception $e) {
            $this->session->setFlashdata('error_message', 'Failed to insert timesheet entries: ' . $e->getMessage());
            return redirect()->to('/dashboard');
        }
    }

    public function viewTimesheets($userId) {
        $user = $this->timesheetsModel->getUserInfo($userId);
        $timesheets = $this->timesheetsModel->getUserTimesheets($userId);
        
        return view('PMS/user_timesheets', [
            'user' => $user,
            'timesheets' => $timesheets,
        ]);
    }

    public function viewTimesheet($timesheetId) {
        $timesheet = $this->timesheetsModel->find($timesheetId);
        $entries = $this->timesheetsModel->getTimesheetEntriesByTimesheetId($timesheetId);

        if (!$timesheet) {
            return redirect()->back()->with('error_message', 'Timesheet not found.');
        }

        return view('PMS/timesheet_details', [
            'timesheet' => $timesheet,
            'timesheetEntries' => $entries,
        ]);
    }

    public function editTimesheet($timesheetId) {
        $timesheet = $this->timesheetsModel->find($timesheetId);
        $entries = $this->timesheetsModel->getTimesheetEntriesByTimesheetId($timesheetId);

        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return view('PMS/edit_timesheet', [
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
    
        $entries = $this->getTimesheetEntriesFromRequest();
        $successEntries = $this->timesheetsModel->updateTimesheetEntries($timesheetId, $entries);
    
        if ($success && $successEntries) {
            $this->session->setFlashdata('success_message', 'Timesheet updated successfully.');
        } else {
            $this->session->setFlashdata('error_message', 'Unable to update timesheet, please try again.');
        }
    
        return redirect()->to('/dashboard');
    }

    public function deleteTimesheet($timesheetId) {
        $timesheet = $this->timesheetsModel->find($timesheetId);

        if (!$timesheet) {
            return redirect()->back()->with('error_message', 'Timesheet not found.');
        }

        $this->timesheetsModel->deleteTimesheetEntries($timesheetId);
        $success = $this->timesheetsModel->delete($timesheetId);

        if ($success) {
            return redirect()->back()->with('success_message', 'Timesheet deleted successfully.');
        } else {
            return redirect()->back()->with('error_message', 'Unable to delete timesheet, please try again.');
        }
    }

    public function exportTimesheet($timesheetId) {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $timesheet = $this->timesheetsModel->find($timesheetId);
        $entries = $this->timesheetsModel->getTimesheetEntriesByTimesheetId($timesheetId);

        if (!$timesheet) {
            return redirect()->back()->with('error_message', 'Timesheet not found.');
        }

        $sheet->setCellValue('A1', 'Timesheet ID: ' . $timesheetId);
        $sheet->setCellValue('A2', 'Week Of: ' . $timesheet['weekOf']);
        $sheet->setCellValue('A3', 'User ID: ' . $timesheet['userID']);
        $sheet->setCellValue('A4', 'Total Hours: ' . $timesheet['totalHours']);
        
        $sheet->setCellValue('A6', 'Project Number');
        $sheet->setCellValue('B6', 'Project Name');
        $sheet->setCellValue('C6', 'Activity Description');
        $sheet->setCellValue('D6', 'Monday Hours');
        $sheet->setCellValue('E6', 'Tuesday Hours');
        $sheet->setCellValue('F6', 'Wednesday Hours');
        $sheet->setCellValue('G6', 'Thursday Hours');
        $sheet->setCellValue('H6', 'Friday Hours');
        $sheet->setCellValue('I6', 'Saturday Hours');
        $sheet->setCellValue('J6', 'Sunday Hours');
        $sheet->setCellValue('K6', 'Total Hours');

        $row = 7;
        foreach ($entries as $entry) {
            $sheet->setCellValue('A' . $row, $entry['projectNumber']);
            $sheet->setCellValue('B' . $row, $entry['projectName']);
            $sheet->setCellValue('C' . $row, $entry['activityDescription']);
            $sheet->setCellValue('D' . $row, $entry['mondayHours']);
            $sheet->setCellValue('E' . $row, $entry['tuesdayHours']);
            $sheet->setCellValue('F' . $row, $entry['wednesdayHours']);
            $sheet->setCellValue('G' . $row, $entry['thursdayHours']);
            $sheet->setCellValue('H' . $row, $entry['fridayHours']);
            $sheet->setCellValue('I' . $row, $entry['saturdayHours']);
            $sheet->setCellValue('J' . $row, $entry['sundayHours']);
            $sheet->setCellValue('K' . $row, $entry['totalHours']);
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $fileName = 'Timesheet_' . $timesheetId . '.xlsx';
        $filePath = WRITEPATH . 'uploads/' . $fileName;

        $writer->save($filePath);

        return $this->response->download($filePath, null)->setFileName($fileName);
    }

    private function getTimesheetEntriesFromRequest() {
        $entries = [];
        $post = $this->request->getPost();
    
        foreach ($post['projectNumber'] as $index => $projectNumber) {
            if (empty($projectNumber)) {
                continue; // Skip empty rows
            }
    
            $entries[] = [
                'projectNumber' => $projectNumber,
                'projectName' => $post['projectName'][$index],
                'activityDescription' => $post['activityDescription'][$index],
                'mondayHours' => $post['monday'][$index] ?? 0,
                'tuesdayHours' => $post['tuesday'][$index] ?? 0,
                'wednesdayHours' => $post['wednesday'][$index] ?? 0,
                'thursdayHours' => $post['thursday'][$index] ?? 0,
                'fridayHours' => $post['friday'][$index] ?? 0,
                'saturdayHours' => $post['saturday'][$index] ?? 0,
                'sundayHours' => $post['sunday'][$index] ?? 0,
                'totalHours' => $post['totalHours'][$index] ?? 0
            ];
        }
    
        return $entries;
    }
    
    
}