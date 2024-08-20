<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Commands\User;
use CodeIgniter\Shield\Models\UserModel;
use App\Models\TimesheetsModel;

class PayrollController extends BaseController {
    protected $userModel;
    protected $timesheetsModel;

    public function __construct() {
        $this->userModel = auth()->getProvider();
        $this->timesheetsModel = new TimesheetsModel();
    }

    public function index() {
        $user = auth()->user();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        if (!$user->inGroup('accountant') && !$user->inGroup('superadmin')) {
            return redirect()->to('/dashboard')->with('error_message', 'You do not have permission to access this page.');
        }

        // Fetch usernames
        $usernames = $this->userModel->select('username')->findAll();

        $weeks = $this->timesheetsModel->distinct()
            ->select('weekOf')
            ->orderBy('weekOf', 'DESC')
            ->findAll();
        
        $selectedUsername = $this->request->getGet('username');
        $selectedWeek = $this->request->getGet('week');

        $query = $this->timesheetsModel->select('timesheets.*, users.username')
                                       ->join('users', 'users.id = timesheets.userID', 'left');
        
        if (!empty($selectedUsername)) {
            $query->where('users.username', $selectedUsername);
        }

        if (!empty($selectedWeek)) {
            $query->where('timesheets.weekOf', $selectedWeek);
        }

        $filteredTimesheets = $query->findAll();

        return view('PMS/accountantpayroll', [
            'usernames' => $usernames,
            'weeks' => $weeks,
            'filteredTimesheets' => $filteredTimesheets,
        ]);
    }

    public function viewWeek($weekOf) {
        $timesheets = $this->timesheetsModel->getTimesheetsWithUsernames($weekOf);
    
        return view('PMS/timesheetByWeek.php', [
            'weekOf' => $weekOf,
            'timesheets' => $timesheets,
        ]);
    }
    
    public function search() {
        // Retrieve search term from the URL query parameters
        $searchTerm = $this->request->getGet('search');

        // Check if the search term is empty
        if (empty($searchTerm)) {
            // If empty, redirect back to the index page
            return redirect()->to('/accountantpayroll')->with('error_message', 'Please provide a search term.');
        }

        // Fetch timesheets based on the search term (assumed to be the weekOf date)
        $timesheets = $this->timesheetModel->like('weekOf', $searchTerm)->findAll();

        // Group timesheets by 'weekOf'
        $timesheetData = [];
        foreach ($timesheets as $timesheet) {
            $weekOf = $timesheet['weekOf'];
            if (!isset($timesheetData[$weekOf])) {
                $timesheetData[$weekOf] = [];
            }
            $timesheetData[$weekOf][] = $timesheet;
        }

        // Pass the search results to the view
        return view('PMS/accountantpayroll.php', [
            'timesheetData' => $timesheetData,
        ]);
    }
}