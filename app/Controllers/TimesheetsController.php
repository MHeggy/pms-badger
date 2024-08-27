<?php

namespace App\Controllers;

use App\Models\TimesheetsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TimesheetsController extends BaseController {
    protected $timesheetsModel;
    protected $session;
    protected $auth;

    public function __construct() {
        $this->timesheetsModel = new TimesheetsModel();
        $this->session = \Config\Services::session();
        $this->auth = \Config\Services::auth();
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
    
        // Fetch user details
        $userId = $timesheet['userID'];
        $user = $this->timesheetsModel->getUserInfo($userId);
    
        // Calculate the end date (Sunday) from the start date (Monday)
        $weekOf = new \DateTime($timesheet['weekOf']);
        $endDate = clone $weekOf;
        $endDate->modify('+6 days');
    
        // Format the dates
        $formattedStartDate = $weekOf->format('Y-m-d');
        $formattedEndDate = $endDate->format('Y-m-d');
    
        // Fill in the template data
        $sheet->setCellValue('K6', $formattedStartDate); // Start Date
        $sheet->setCellValue('K7', $formattedEndDate); // End Date
        $sheet->setCellValue('B4', $timesheet['userID']); // User ID
        $sheet->setCellValue('R31', $timesheet['totalHours']); // Total Hours
    
        // Combine the firstName and lastName
        $fullName = $user ? $user['firstName'] . '_' . $user['lastName'] : 'Unknown_User';
    
        // Merge cells for the name
        $sheet->mergeCells('K9:N9');
        $sheet->setCellValue('K9', $fullName); // User's Full Name
    
        // Fill timesheet entries
        $startRow = 12;
        foreach ($entries as $index => $entry) {
            $row = $startRow + $index;
            $sheet->setCellValue('B' . $row, $entry['projectNumber']);
            $sheet->mergeCells('C' . $row . ':E' . $row);
            $sheet->setCellValue('C' . $row, $entry['projectName']);
            $sheet->mergeCells('F' . $row . ':J' . $row);
            $sheet->setCellValue('F' . $row, $entry['activityDescription']);
            $sheet->setCellValue('K' . $row, $entry['mondayHours']);
            $sheet->setCellValue('L' . $row, $entry['tuesdayHours']);
            $sheet->setCellValue('M' . $row, $entry['wednesdayHours']);
            $sheet->setCellValue('N' . $row, $entry['thursdayHours']);
            $sheet->setCellValue('O' . $row, $entry['fridayHours']);
            $sheet->setCellValue('P' . $row, $entry['saturdayHours']);
            $sheet->setCellValue('Q' . $row, $entry['sundayHours']);
            $sheet->setCellValue('R' . $row, $entry['totalHours']);
        }
    
        // Generate filename
        $fileName = $fullName . '_' . $formattedStartDate . '.xlsx';
        $filePath = WRITEPATH . 'uploads/' . $fileName;
    
        // Debug output
        echo 'Filename: ' . $fileName . '<br>';
        echo 'Filepath: ' . $filePath . '<br>';
    
        // Ensure the uploads directory exists and is writable
        if (!is_dir(WRITEPATH . 'uploads')) {
            mkdir(WRITEPATH . 'uploads', 0755, true);
        }
    
        if (!is_writable(WRITEPATH . 'uploads')) {
            return redirect()->back()->with('error_message', 'Uploads directory is not writable.');
        }
    
        // Save the filled template as a new file
        try {
            $writer = new Xlsx($spreadsheet);
            $writer->save($filePath);
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', 'Failed to save the file: ' . $e->getMessage());
        }
    
        // Trigger file download
        return $this->response->download($filePath, null)->setFileName($fileName);
    }
    
    private function getTimesheetEntriesFromRequest() {
        $entries = [];
        $entryIDs = $this->request->getPost('entryID');
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
    
        foreach ($projectNumbers as $index => $projectNumber) {
            // Only add entries if there is valid data
            if (!empty($projectNumber) || !empty($projectNames[$index]) || !empty($totalHours[$index])) {
                $entries[] = [
                    'entryID' => $entryIDs[$index] ?? null,
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
                    'totalHours' => $totalHours[$index] ?? 0
                ];
            }
        }
    
        return $entries;
    }
    
}