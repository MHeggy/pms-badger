<?php

namespace App\Controllers;

use App\Models\TimesheetsModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use ZipArchive;

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
    
        // Ensure the user is authenticated
        if (!$userId) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }
    
        // Get the start of the current week (Monday)
        $weekStart = (new \DateTime())->setISODate((new \DateTime())->format('o'), (new \DateTime())->format('W'))->format('Y-m-d');
        
        // Query for the timesheet of the current week
        $timesheetModel = new TimesheetsModel();
        $timesheet = $timesheetModel
                        ->where('userID', $userId)
                        ->where('weekOf', $weekStart)
                        ->first();
        
        // Initializing the weekOf variable.
        $weekOf = $weekStart;
    
        // If a timesheet exists for this week, fetch its entries
        $entries = [];
        if ($timesheet) {
            $entries = $timesheetModel->getTimesheetEntriesByTimesheetId($timesheet['timesheetID']);
            $weekOf = $timesheet['weekOf']; // Update weekOf to the existing timesheet's weekOf
        }
    
        // Pass the timesheet and its entries to the view
        return view('PMS/payroll', [
            'userId' => $userId,
            'timesheet' => $timesheet,
            'entries' => $entries,
            'weekOf' => $weekOf
        ]);
    }

    public function submit() {
        $userId = auth()->id();
        $weekOf = $this->request->getPost('week');
        $entries = $this->getTimesheetEntriesFromRequest();
    
        // Check if a timesheet already exists for the user and the week.
        $existingTimesheet = $this->timesheetsModel
            ->where('userID', $userId)
            ->where('weekOf', $weekOf)
            ->first();
    
        if ($existingTimesheet) {
            // Calculate total hours from entries
            $totalHours = $this->calculateTotalHours($entries);
            
            // Prepare data for updating the existing timesheet
            $timesheetData = [
                'totalHours' => $totalHours,
                'updatedAt' => date('Y-m-d H:i:s')
            ];
    
            try {
                $this->timesheetsModel->updateTimesheet($existingTimesheet['timesheetID'], $timesheetData);
                $this->timesheetsModel->updateTimesheetEntries($existingTimesheet['timesheetID'], $entries);
                $this->session->setFlashdata('success_message', 'Timesheet saved successfully.');
            } catch (\Exception $e) {
                $this->session->setFlashdata('error_message', 'Failed to save timesheet: ' . $e->getMessage());
                return redirect()->to('/timesheets');
            }
    
            return redirect()->to('/timesheets');
        }
    
        // Create a new timesheet if it doesn't exist
        $totalHours = $this->calculateTotalHours($entries);
        $timesheetData = [
            'userID' => $userId,
            'weekOf' => $weekOf,
            'totalHours' => $totalHours,
            'createdAt' => date('Y-m-d H:i:s'),
            'updatedAt' => date('Y-m-d H:i:s')
        ];
    
        try {
            $timesheetId = $this->timesheetsModel->insertTimesheet($timesheetData);
            $this->timesheetsModel->insertTimesheetEntries($timesheetId, $entries);
            $this->session->setFlashdata('success_message', 'Timesheet submitted successfully.');
        } catch (\Exception $e) {
            $this->session->setFlashdata('error_message', 'Timesheet could not be submitted: ' . $e->getMessage());
            return redirect()->to('/timesheets');
        }
    
        return redirect()->to('/timesheets');
    }
    

    public function viewTimesheets($userId) {
        $user = $this->timesheetsModel->getUserInfo($userId);
        if (!$user) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }
        $timesheets = $this->timesheetsModel->getUserTimesheets($userId);

        // Order timesheets by weekOf in descending order
        usort($timesheets, function($a, $b) {
            return strtotime($b['weekOf']) - strtotime($a['weekOf']);
        });

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
    
        // Debugging userID
        echo "<pre>User ID from timesheet: " . $timesheet['userID'] . "</pre>";
    
        // Get the user info based on the userID from the timesheet
        $user = $this->timesheetsModel->getUserInfo($timesheet['userID']);
    
        // Debugging output
        echo "<pre>User Info Retrieved: "; 
        print_r($user);
        echo "</pre>";
    
        $totalHours = array_sum(array_column($entries, 'totalHours'));
    
        return view('PMS/timesheet_details', [
            'timesheet' => $timesheet,
            'timesheetEntries' => $entries,
            'totalHours' => $totalHours,
            'user' => $user,
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

    // function to get a timesheet to populate the data in the edit modal for the accountant.
    public function getTimesheet($timesheetId) {
        $timesheet = $this->timesheetsModel->find($timesheetId);
        $entries = $this->timesheetsModel->getTimesheetEntriesByTimesheetId($timesheetId);

        return $this->response->setJSON([
            'timesheet' => $timesheet,
            'entries' => $entries
        ]);
    }
    
    // function to export timesheet to excel (individual timesheets)
    public function exportTimesheet($timesheetId) {
        $templatePath = WRITEPATH . 'templates/badgerspreadsheet.xlsx'; // Path to your Excel template
    
        // Load the template
        try {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', 'Failed to load the template: ' . $e->getMessage());
        }
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
    
        // Variables to hold the last entry with projectNumber = 13-000
        $specialEntry = null;
    
        // Fill timesheet entries
        $startRow = 12;
        foreach ($entries as $index => $entry) {
            if ($entry['projectNumber'] === '13-000') {
                $specialEntry = $entry;
                continue; // Skip this entry in the loop
            }
    
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
    
        // Insert the special entry into line 29 if it exists
        if ($specialEntry) {
            $sheet->setCellValue('K29', $specialEntry['mondayHours']);
            $sheet->setCellValue('L29', $specialEntry['tuesdayHours']);
            $sheet->setCellValue('M29', $specialEntry['wednesdayHours']);
            $sheet->setCellValue('N29', $specialEntry['thursdayHours']);
            $sheet->setCellValue('O29', $specialEntry['fridayHours']);
            $sheet->setCellValue('P29', $specialEntry['saturdayHours']);
            $sheet->setCellValue('Q29', $specialEntry['sundayHours']);
            $sheet->setCellValue('R29', $specialEntry['totalHours']);
        }
    
        // Generate filename
        $fileName = $fullName . '_' . $formattedStartDate . '.xlsx';
        $filePath = WRITEPATH . 'uploads/' . $fileName;
    
        // Ensure the uploads directory exists and is writable
        if (!is_dir(WRITEPATH . 'uploads')) {
            mkdir(WRITEPATH . 'uploads', 0755, true);
        }
    
        if (!is_writable(WRITEPATH . 'uploads')) {
            return redirect()->back()->with('error_message', 'Uploads directory is not writable.');
        }
    
        // Save the filled template as a new file
        try {
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
            $writer->save($filePath);
        } catch (\Exception $e) {
            return redirect()->back()->with('error_message', 'Failed to save the file: ' . $e->getMessage());
        }
    
        // Trigger file download
        return $this->response->download($filePath, null)->setFileName($fileName);
    }
    

    // function to export multiple timesheets to excel (accountant payroll)
    public function exportMultipleTimesheets() {
        $timesheetIds = $this->request->getPost('timesheet_ids');
        
        if (empty($timesheetIds)) {
            return redirect()->back()->with('error_message', 'No timesheets selected for export.');
        }
    
        $zipFileName = 'timesheets_' . date('YmdHis') . '.zip';
        $zipFilePath = WRITEPATH . 'uploads/' . $zipFileName;
    
        if (!is_dir(WRITEPATH . 'uploads')) {
            mkdir(WRITEPATH . 'uploads', 0755, true);
        }
    
        if (!is_writable(WRITEPATH . 'uploads')) {
            return redirect()->back()->with('error_message', 'Uploads directory is not writable.');
        }
    
        $zip = new \ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE) !== true) {
            return redirect()->back()->with('error_message', 'Failed to create ZIP archive.');
        }
    
        $tempFiles = []; // Array to store temporary file paths
    
        foreach ($timesheetIds as $timesheetId) {
            $templatePath = WRITEPATH . 'templates/badgerspreadsheet.xlsx';
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templatePath);
            $sheet = $spreadsheet->getActiveSheet();
    
            $timesheet = $this->timesheetsModel->find($timesheetId);
            $entries = $this->timesheetsModel->getTimesheetEntriesByTimesheetId($timesheetId);
    
            if (!$timesheet) {
                continue;
            }
    
            $userId = $timesheet['userID'];
            $user = $this->timesheetsModel->getUserInfo($userId);
    
            $weekOf = new \DateTime($timesheet['weekOf']);
            $endDate = clone $weekOf;
            $endDate->modify('+6 days');
    
            $formattedStartDate = $weekOf->format('Y-m-d');
            $formattedEndDate = $endDate->format('Y-m-d');
    
            $sheet->setCellValue('K6', $formattedStartDate);
            $sheet->setCellValue('K7', $formattedEndDate);
            $sheet->setCellValue('B4', $timesheet['userID']);
            $sheet->setCellValue('R31', $timesheet['totalHours']);
    
            $fullName = $user ? $user['firstName'] . '_' . $user['lastName'] : 'Unknown_User';
            $sheet->mergeCells('K9:N9');
            $sheet->setCellValue('K9', $fullName);
    
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
    
            $fileName = $fullName . '_' . $formattedStartDate . '.xlsx';
            $filePath = WRITEPATH . 'uploads/' . $fileName;
    
            try {
                $writer = new Xlsx($spreadsheet);
                $writer->save($filePath);
                if (!file_exists($filePath)) {
                    log_message('error', 'Failed to save spreadsheet to ' . $filePath);
                } else {
                    $zip->addFile($filePath, $fileName);
                    $tempFiles[] = $filePath; // Store file path for later deletion
                }
            } catch (\Exception $e) {
                log_message('error', 'Exception occurred: ' . $e->getMessage());
                $zip->close();
                return redirect()->back()->with('error_message', 'Failed to save one or more files: ' . $e->getMessage());
            }
        } // end foreach loop
    
        if ($zip->close() !== true) {
            log_message('error', 'Failed to close ZIP archive at ' . $zipFilePath);
            return redirect()->back()->with('error_message', 'Failed to close ZIP archive.');
        }
    
        // Use the response object to initiate the download
        $downloadResponse = $this->response->download($zipFilePath, null)->setFileName($zipFileName);
    
        // Clean up temporary files after the response has been sent
        foreach ($tempFiles as $tempFile) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
        }
    
        // Optionally, delete the ZIP file as well if it's no longer needed
        register_shutdown_function(function() use ($zipFilePath) {
            if (file_exists($zipFilePath)) {
                unlink($zipFilePath);
            }
        });
    
        return $downloadResponse;
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

    // Method to calculate total hours
    private function calculateTotalHours($entries) {
        $totalHours = 0;
        foreach ($entries as $entry) {
            $totalHours += $entry['totalHours'];
        }
        return $totalHours;
    }
    
}