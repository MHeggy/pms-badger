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
        $entries = $this->getTimesheetEntriesFromRequest();
        $totalHours = $this->request->getPost('totalHours');

        // Prepare data for the timesheet.
        $timesheetData = [
            'userID' => $userId,
            'weekOf' => $weekOf,
            'totalHours' => $totalHours,
            'createdAt' => date('Y-m-d H:i:s'),
            'updatedAt' => date('Y-m-d H:i:s')
        ];

        // Insert the timesheet and get the ID
        $timesheetId = $this->timesheetsModel->insertTimesheet($timesheetData);

        if ($timesheetId) {
            // Insert each timesheet entry.
            foreach ($entries as $entry) {
                $entry['timesheetID'] = $timesheetId;
                $entry['createdAt'] = date('Y-m-d H:i:s');
                $entry['updatedAt'] = date('Y-m-d H:i:s');
                $this->timesheetsModel->insertTimesheetEntry($entry);
            }

            $this->session->setFlashdata('success_message', 'Timesheet submitted successfully.');
        } else {
            $this->session->setFlashdata('error_message', 'Timesheet could not be submitted successfully, please try again.');
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
            return redirect()->back()->with('error_messge', 'Timesheet not found.');
        }

        return view('PMS/timesheet_details.php', [
            'timesheet' => $timesheet,
            'timesheetEntries' => $entries,
        ]);
    }

    public function editTimesheet($timesheetId) {
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
    
        $entries = $this->getTimesheetEntriesFromRequest();
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
        
        // Fetch the number of rows (assuming each row is represented by an index)
        $projectNumbers = $this->request->getPost('projectNumber');
        $projectNames = $this->request->getPost('projectName');
        $descriptions = $this->request->getPost('description');
        $mondayHours = $this->request->getPost('monday');
        $tuesdayHours = $this->request->getPost('tuesday');
        $wednesdayHours = $this->request->getPost('wednesday');
        $thursdayHours = $this->request->getPost('thursday');
        $fridayHours = $this->request->getPost('friday');
        $saturdayHours = $this->request->getPost('saturday');
        $sundayHours = $this->request->getPost('sunday');
        $totalHours = $this->request->getPost('totalHours');
    
        // Debugging statement
        log_message('debug', 'Inserting timesheet entries: ' . print_r($entries, true));

        // Loop through each row
        for ($i = 0; $i < count($projectNumbers); $i++) {
            $entries[] = [
                'projectNumber' => $projectNumbers[$i],
                'projectName' => $projectNames[$i],
                'activityDescription' => $descriptions[$i],
                'mondayHours' => $mondayHours[$i],
                'tuesdayHours' => $tuesdayHours[$i],
                'wednesdayHours' => $wednesdayHours[$i],
                'thursdayHours' => $thursdayHours[$i],
                'fridayHours' => $fridayHours[$i],
                'saturdayHours' => $saturdayHours[$i],
                'sundayHours' => $sundayHours[$i],
                'totalHours' => $totalHours[$i],
            ];
        }
    
        return $entries;
    }

    public function exportTimesheet($timesheetId) {
        // Load the timesheet and entries.
        $timesheet = $this->timesheetsModel->find($timesheetId);
        $entries = $this->getTimesheetEntries($timesheetId);

        if (!$timesheet) {
            return redirect()->back()->with('error_message', 'Timesheet not found.');
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set spreadsheet headers
        $sheet->setCellValue('A1', 'Project Number');
        $sheet->setCellValue('B1', 'Project Name');
        $sheet->setCellValue('C1', 'Activity Description');
        $sheet->setCellValue('D1', 'Monday');
        $sheet->setCellValue('E1', 'Tuesday');
        $sheet->setCellValue('F1', 'Wednesday');
        $sheet->setCellValue('G1', 'Thursday');
        $sheet->setCellValue('H1', 'Friday');
        $sheet->setCellValue('I1', 'Saturday');
        $sheet->setCellValue('J1', 'Sunday');
        $sheet->setCellValue('K1', 'Total Hours');

        // Fill spreadsheet with timesheet data
        $row = 2;
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

            // Prepare the file for download
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="timesheet_' . $timesheetId . '.xlsx"');
            header('Cache-Control: max-age=0');

            $writer->save('php://output');
            exit;
    }
    
    private function getTimesheetEntries($timesheetId) {
        // Fetch timesheet entries from the model
        return $this->timesheetsModel->getTimesheetEntriesByTimesheetId($timesheetId);
    }

    private function calculateTotalHoursForDay($hours) {
        return array_sum($hours);
    }

}