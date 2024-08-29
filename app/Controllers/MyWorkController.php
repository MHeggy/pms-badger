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
    
            // Fetch assigned projects for the user
            $projects = $this->projectModel->getAssignedProjects($userID);
    
            // Filter projects by status
            if ($status) {
                $projects = array_filter($projects, function($project) use ($status) {
                    return isset($project['statusID']) && $project['statusID'] == $status;
                });
            }
    
            // Pass the filtered projects to the view
            $data = [
                'assignedProjects' => $projects,
                'status' => $status
            ];
    
            return view('PMS/mywork', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error in filter: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Internal server error']);
        }
    }

    public function search() {
        $userID = auth()->id();
        
        // Ensure the user is logged in
        if (!$userID) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }
    
        // Debugging statement for the logged-in user ID
        log_message('debug', 'search() called by User ID: ' . $userID);
        
        $searchTerm = $this->request->getGet('search');
        
        // Debugging statement for the search term
        log_message('debug', 'Search term: ' . $searchTerm);
    
        try {
            // Fetch assigned projects for the user
            $projects = $this->projectModel->getAssignedProjects($userID);
    
            // Debugging statement to see what projects are fetched
            log_message('debug', 'Assigned Projects for User ID ' . $userID . ': ' . print_r($projects, true));
            
            // Perform search on the fetched projects
            $searchResults = $this->projectModel->searchProjects($searchTerm, $projects);
    
            // Debugging statement to see the search results
            log_message('debug', 'Search Results for term "' . $searchTerm . '": ' . print_r($searchResults, true));
    
            // Pass the searched projects to the view
            $data = [
                'assignedProjects' => $searchResults,
                'searchTerm' => $searchTerm
            ];
    
            return $this->response->setJSON(['projects' => $searchResults]);
        } catch (\Exception $e) {
            log_message('error', 'Error in search: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Internal server error']);
        }
    }
       

}