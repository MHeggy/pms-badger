<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\UserModel;
use App\Models\StateModel;
use App\Models\CountryModel;
use App\Models\TaskModel;
use App\Models\CategoryModel;
use App\Models\UpdatesModel;
use CodeIgniter\Controller;

class MyWorkController extends Controller {
    protected $projectModel;
    protected $authGroups;
    protected $stateModel;
    protected $countryModel;
    protected $taskModel;
    protected $categoryModel;
    protected $updatesModel;

    public function __construct() {
        $this->projectModel = new ProjectModel();
        $this->authGroups = new \Config\AuthGroups();
        $this->stateModel = new StateModel();
        $this->countryModel = new CountryModel();
        $this->taskModel = new TaskModel();
        $this->categoryModel = new CategoryModel();
        $this->updatesModel = new UpdatesModel();
    }

    public function index() {
        $userID = auth()->id();

        // Ensure the user is logged in
        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        // Try statement to catch any exceptions
        try {
            // Fetch assigned projects for the user using the model method.
            $assignedProjects = $this->projectModel->getAssignedProjects($userID);

            // Debugging statement.
            log_message('debug', 'Assigned Projects for User ID ' . $userID . ': ' . print_r($assignedProjects, true));
            // print_r statement for the $assignedProjects variable.
            //print_r($assignedProjects);

            $data = [
                'assignedProjects' => $assignedProjects
            ];

            // return the view with the data passed to it as an array.
            return view('PMS/mywork', $data);
        } catch (\Exception $e) {
            // Log the error message to the log file.
            log_message('error', 'Error in myWork: ' . $e->getMessage());
            // Return a JSON response with an error message.
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Internal server error']);
        }
    }

    public function filter() {
        $userID = auth()->id();
    
        // Ensure the user is logged in
        if (!$userID) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }
    
        try {
            // Get filter criteria from the request
            $status = $this->request->getGet('status');
            $category = $this->request->getGet('category');
            $assignedUser = $this->request->getGet('assigned_user');
            $dateRange = $this->request->getGet('date_range'); // Expected in format "start_date - end_date"
    
            // Extract date range if provided
            $dates = explode(' - ', $dateRange);
            $startDate = isset($dates[0]) ? $dates[0] : null;
            $endDate = isset($dates[1]) ? $dates[1] : null;
    
            // Fetch assigned projects for the user with filters applied
            $projects = $this->projectModel->filterProjects([
                'status' => $status,
                'category' => $category,
                'assignedUser' => $assignedUser,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);
    
            log_message('debug', 'Filtered Projects: ' . print_r($projects, true));
    
            // Filter projects to ensure they are assigned to the logged-in user
            $projects = array_filter($projects, function($project) use ($userID) {
                // Ensure $project['assignedUsers'] is an array
                $assignedUsers = isset($project['assignedUsers']) && is_array($project['assignedUsers']) 
                    ? $project['assignedUsers'] 
                    : [];
    
                return in_array($userID, $assignedUsers);
            });
    
            // Pass the filtered projects to the view
            $data = [
                'assignedProjects' => $projects,
                'selectedStatus' => $status,
                'selectedCategory' => $category,
                'selectedUser' => $assignedUser,
                'selectedDateRange' => $dateRange
            ];
    
            return view('PMS/mywork', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error in filter: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Internal server error']);
        }
    }        
    
}