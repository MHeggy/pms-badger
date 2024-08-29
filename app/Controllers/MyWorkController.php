<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\UserModel;
use App\Models\StateModel;
use App\Models\CountryModel;
use App\Models\TaskModel;
use App\Models\CategoryModel;
use App\Models\UpdatesModel;

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
        $status = $this->request->getGet('status');
    
        // Ensure the user is logged in
        $userID = auth()->id();
        if (!$userID) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Unauthorized']);
        }
    
        $query = $this->projectModel->getAssignedProjects($userID);
        if ($status) {
            $query->where('statusID', $status);
        }
    
        $assignedProjects = $query->findAll();
    
        // Return JSON response if request is via AJAX
        if ($this->request->isAJAX()) {
            return $this->response->setJSON(['projects' => $assignedProjects]);
        }
    
        // Return the view with filtered data for non-AJAX requests
        $data['assignedProjects'] = $assignedProjects;
        return view('PMS/mywork', $data);
    }
    
}