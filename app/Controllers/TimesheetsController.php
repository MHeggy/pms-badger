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

        // Calculating the totalHours from each of the entries.
        $totalHours = 0;
        foreach ($entries as $entry) {
            $totalHours += $entry['totalHours'];
        }

        // Check if a timesheet already exists for the user and the week.
        $existingTimesheet = $this->timesheetsModel
            ->where('userID', $userId)
            ->where('weekOf', $weekOf)
            ->first();
        
        if ($existingTimesheet) {
            // Redirect to edit the existing timesheet.
            $this->session->setFlashdata('info_message', 'You have already submitted a timesheet for this week, please edit it instead.');
            return redirect()->to('/timesheets/edit/' . $existingTimesheet['timesheetID']);
        }

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
            $result = $this->timesheetsModel->insertTimesheetEntries($timesheetId, $entries);
            if (!$result) {
                throw new \Exception('Failed to insert timesheet entries.');
            }
            $this->session->setFlashdata('success_message', 'Timesheet submitted successfully.');
        } catch (\Exception $e) {
            $this->session->setFlashdata('error_message', 'Failed to insert timesheet entries: ' . $e->getMessage());
            return redirect()->to('/dashboard');
        }
    
        return redirect()->to('/dashboard');
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

        $totalHours = array_sum(array_column($entries, 'totalHours'));

        if (!$timesheet) {
            return redirect()->back()->with('error_message', 'Timesheet not found.');
        }

        return view('PMS/timesheet_details', [
            'timesheet' => $timesheet,
            'timesheetEntries' => $entries,
            'totalHours' => $totalHours,
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
        $templatePath = WRITEPATH . 'templates/badgerspreadsheet.xlsx'; // Path to your Excel template
    
        // Load the template
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
        $sheet = $spreadsheet->getActiveSheet();
    
        // Fetch the timesheet and entries
        $timesheet = $this->timesheetsModel->find($timesheetId);
        $entries = $this->timesheetsModel->getTimesheetEntriesByTimesheetId($timesheetId);
    
        if (!$timesheet) {
            return redirect()->back()->with('error_message', 'Timesheet not found.');
        }
    
        // Fill in the template data
        $sheet->setCellValue('B2', $timesheetId); // Example cell for Timesheet ID
        $sheet->setCellValue('B3', $timesheet['weekOf']); // Example cell for Week Of
        $sheet->setCellValue('B4', $timesheet['userID']); // Example cell for User ID
        $sheet->setCellValue('B5', $timesheet['totalHours']); // Example cell for Total Hours
    
        // Start filling timesheet entries at a specific row (e.g., row 10)
        $startRow = 10;
        foreach ($entries as $index => $entry) {
            $row = $startRow + $index;
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
        }
    
        // Save the filled template as a new file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Timesheet_' . $timesheetId . '.xlsx';
        $filePath = WRITEPATH . 'uploads/' . $fileName;
    
        $writer->save($filePath);
    
        // Trigger file download
        return $this->response->download($filePath, null)->setFileName($fileName);
    }
    

    private function getTimesheetEntriesFromRequest() {
        $entries = [];
        $projectNumbers = $this->request->getPost('projectNumber');
        $projectNames = $this->request->getPost('projectName');
        $activityDescriptions = $this->request->getPost('activityDescription');
        $mondayHours = $this->request->getPost('monday');
        $tuesdayHours = $this->request->getPost('tuesday');
        $wednesdayHours = $this->request->getPost('wednesday');
        $thursdayHours = $this->request->getPost('thursday');
        $fridayHours = $this->request->getPost('friday');
        $saturdayHours = $this->request->getPost('saturday');
        $sundayHours = $this->request->getPost('sunday');
        $totalHours = $this->request->getPost('totalHours');
        
        log_message('debug', 'Received timesheet data from request: ' . print_r([
            'projectNumber' => $projectNumbers,
            'projectName' => $projectNames,
            'activityDescription' => $activityDescriptions,
            'mondayHours' => $mondayHours,
            'tuesdayHours' => $tuesdayHours,
            'wednesdayHours' => $wednesdayHours,
            'thursdayHours' => $thursdayHours,
            'fridayHours' => $fridayHours,
            'saturdayHours' => $saturdayHours,
            'sundayHours' => $sundayHours,
            'totalHours' => $totalHours,
        ], true));
        
        foreach ($projectNumbers as $index => $projectNumber) {
            
            $entry = [
                'projectNumber' => $projectNumber,
                'projectName' => $projectNames[$index] ?? '',
                'activityDescription' => $activityDescriptions[$index] ?? '',
                'mondayHours' => $mondayHours[$index] ?? 0,
                'tuesdayHours' => $tuesdayHours[$index] ?? 0,
                'wednesdayHours' => $wednesdayHours[$index] ?? 0,
                'thursdayHours' => $thursdayHours[$index] ?? 0,
                'fridayHours' => $fridayHours[$index] ?? 0,
                'saturdayHours' => $saturdayHours[$index] ?? 0,
                'sundayHours' => $sundayHours[$index] ?? 0,
                'totalHours' => $totalHours[$index] ?? 0,
            ];
            
            log_message('debug', 'Processed timesheet entry ' . $index . ': ' . print_r($entry, true));

            // Check if the entry is empty and skip it, if so.
            if (empty($entry['projectNumber']) && empty($entry['projectName']) && empty($entry['activityDescription']) &&
                empty($entry['mondayHours']) && empty($entry['tuesdayHours']) && empty($entry['wednesdayHours']) &&
                empty($entry['thursdayHours']) && empty($entry['fridayHours']) && empty($entry['saturdayHours']) &&
                empty($entry['sundayHours']) && empty($entry['totalHours'])) {
                continue; // Skip empty entries
            }

            $entries[] = $entry;
        }
    
        return $entries;
    }
    
}