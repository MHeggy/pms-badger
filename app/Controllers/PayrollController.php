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
    
        // Fetch user data (including first and last names)
        $users = $this->userModel->asArray()
            ->select('id, firstName, lastName')
            ->where('active', 1)
            ->findAll();
    
        $weeks = $this->timesheetsModel->distinct()
            ->select('weekOf')
            ->orderBy('weekOf', 'DESC')
            ->findAll();
    
        $selectedUserId = $this->request->getGet('userID');
        $selectedWeek = $this->request->getGet('week');
    
        $query = $this->timesheetsModel->select('timesheets.*, users.firstName, users.lastName, users.username')
                                ->join('users', 'users.id = timesheets.userID', 'left');
    
        if (!empty($selectedUserId)) {
            $query->where('users.id', $selectedUserId);
        }
    
        if (!empty($selectedWeek)) {
            $query->where('timesheets.weekOf', $selectedWeek);
        }
    
        // Order by weekOf descending
        $filteredTimesheets = $query->orderBy('timesheets.weekOf', 'DESC')->findAll();
    
        return view('PMS/accountantpayroll', [
            'users' => $users,
            'weeks' => $weeks,
            'filteredTimesheets' => $filteredTimesheets,
            'selectedUserId' => $selectedUserId,
            'selectedWeek' => $selectedWeek,
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