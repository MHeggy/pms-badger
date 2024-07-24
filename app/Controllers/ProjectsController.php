<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\UserModel;
use App\Models\StateModel;
use App\Models\CountryModel;
use App\Models\TaskModel;
use App\Models\CategoryModel; 

class ProjectsController extends BaseController {

    protected $projectModel;
    protected $authGroups;
    protected $stateModel;
    protected $countryModel;
    protected $taskModel;
    protected $categoryModel;

    public function __construct() {
        $this->projectModel = new ProjectModel();
        $this->authGroups = new \Config\AuthGroups();
        $this->stateModel = new StateModel();
        $this->countryModel = new CountryModel();
        $this->taskModel = new TaskModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index() {
        try {
            // Fetch projects from the model
            $projects = $this->projectModel->getProjects();
    
            // Fetch assigned users for each project
            foreach ($projects as &$project) {
                $project['assignedUsers'] = $this->projectModel->getAssignedUsers($project['projectID']);
            }
    
            $userID = auth()->id();
    
            if (!$userID) {
                return redirect()->to('/login')->with('error', 'You must login to access this page.');
            }
    
            // Pass projects and data to the view
            $data = [
                "projects" => $projects
            ];
    
            // Load the projects view and pass the data
            return view('PMS/projects', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error in index: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Internal server error']);
        }
    }
    
    public function search() {
        try {
            $searchTerm = $this->request->getGet('search');

            // Fetch data from the model
            $projects = $this->projectModel->searchProjects($searchTerm);

            // Return JSON response
            return $this->response->setJSON(['projects' => $projects]);
        } catch (\Exception $e) {
            log_message('error', 'Error in search: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Internal server error']);
        }
    }


    // function to filter projects based on status.
    public function filter() {
        // get status filter from the request.
        $status = $this->request->getGet('status');

        $data['projects'] = $this->projectModel->filterProjectsByStatus($status);

        return view('PMS/projects.php', $data);
    }

    public function projectDetails($projectId) {
        try {
            // Fetch project details including status and address
            $project = $this->projectModel->findProjectDetails($projectId);
        
            // Check if project exists
            if (!$project) {
                return $this->response->setStatusCode(404)->setJSON(['error' => 'Project not found']);
            }
        
            // Pass project details to the view
            $data = [
                'project' => $project
            ];
        
            return view('PMS/projectDetails', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error in projectDetails: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Internal server error']);
        }
    }
    
    

    public function assignUsersView() {
        $user = auth()->user();
        $userID = auth()->id();
        $userModel = new UserModel();
        if (!$user->inGroup('superadmin')) {
            return redirect()->to('/dashboard')->with('error', 'You do not have proper permissions to view this page.');
        }
        $data['users'] = $userModel->findAll();
        $data['projects'] = $this->projectModel->findAll();

        log_message('debug', 'Retrieved users: ' . print_r($data['users'], true));
        return view('PMS/assignusers', $data);
    }

    public function assignProjectsToUser() {
        // Get the user ID and selected projects from the form submission.
        $userID = $this->request->getPost('user');
        $selectedProjects = $this->request->getPost('projects');

        // Check each selected project for existing associations and save new associations.
        foreach ($selectedProjects as $projectID) {
            $existingAssociation = $this->projectModel->getUserProjectAssociation($userID, $projectID);

            // If no existing association found, save the association to the database.
            if (!$existingAssociation) {
                $this->projectModel->createUserProjectAssociation($userID, $projectID);
            }
        }

        // Set a success message to display to the user.
        session()->setFlashdata('success', 'Projects assigned successfully.');

        // Load the users and projects data again to populate the view.
        $userModel = new UserModel();
        $projectModel = new ProjectModel();
        $data['users'] = $userModel->findAll();
        $data['projects'] = $projectModel->findAll();

        // Return the view with the updated data.
        return view('PMS/assignusers', $data);
    }

    public function myWork() {
        $user = auth()->user();
        $userID = $user->id;

        // Fetch projects associated with the user.
        $assignedProjects = $this->projectModel->getAssignedProjects($userID);
        // Pass the data to the view.
        $data = [
            'pageTitle' => 'My Work',
            'projects' => $assignedProjects,
            'user' => $user
        ];
        // return the view with the data passed.
        return view('PMS/mywork', $data);
    }

    public function unassignUsersView() {
        $user = auth()->user();
        $userID = auth()->id();
        $userModel = new UserModel();
        if (!$user->inGroup('superadmin')) {
            return redirect()->to('/dashboard')->with('error', 'You do not have proper permissions to view this page.');
        }

        $data['users'] = $userModel->findAll();
        // Print selected user ID to log
        $userID = $this->request->getPost('unassign_user');
        log_message('debug', 'Selected user ID: ' . $userID);

        // Fetch projects associated with the selected user
        if ($userID) {
            $data['projects'] = $this->projectModel->getAssignedProjects($userID);
        } else {
            $data['projects'] = [];
        }

        log_message('debug', 'Retrieved users: ' . print_r($data['projects'], true));
        return view('PMS/unassignusers', $data);
    }

    public function unassignProjectsFromUser() {
        $userID = $this->request->getPost('unassign_user');
        $selectedProjects = $this->request->getPost('unassign_projects[]');
        // Debug log to check if the user ID is retrieved correctly
        log_message('debug', 'Selected user ID: ' . $userID);
        // Check if the user ID and selected projects are provided.
        if (!$userID || !$selectedProjects) {
            return redirect()->back()->withInput()->with('error', 'Invalid user ID or projects.');
        }

        foreach($selectedProjects as $projectID) {
            $existingAssociation = $this->projectModel->getUserProjectAssociation($userID, $projectID);

            if ($existingAssociation) {
                $this->projectModel->deleteUserProjectAssociation($userID, $projectID);
            }
        }

        session()->setFlashdata('success', 'Projects unassigned successfully.');

        return redirect()->to('/unassignUsers');
    }

    public function getProjectsForUser($userId)
    {
        $assignedProjects = $this->projectModel->getAssignedProjects($userId);

        // return the projects as a JSON response
        return $this->response->setJSON(['projects' => $assignedProjects]);
    }

    // functions for categories and tasks start here.
    public function addCategoriesToProject($projectID, $categoryIDs) {
        foreach ($categoryIDs as $categoryID) {
            $data = [
                'projectID' => $projectID,
                'categoryID' => $categoryID
            ];
            $this->projectModel->db->table('project_categories')->insert($data);
        }
    }

    public function addTasksToProject($projectID, $taskIDs) {
        foreach ($taskIDs as $taskID) {
            $data = [
                'projectID' => $projectID,
                'taskID' => $taskID
            ];
            $this->projectModel->db->table('project_tasks')->insert($data);
        }
    }

    // function to show the addProjects view page.
    public function addProjectsView() {
        // Fetch states and countries from the models
        $data['states'] = $this->stateModel->findAll();
        $data['countries'] = $this->countryModel->findAll();
        $data['categories'] = $this->categoryModel->findAll();
        $data['tasks'] = $this->taskModel->findAll();
        return view('PMS/addProjects.php', $data);
    }

    // function to insert projects into the database.
    public function add() {
        $db = \Config\Database::connect();
        $db->transBegin();
        
        try {
            // Insert address data
            $addressData = [
                'street' => $this->request->getPost('street'),
                'city' => $this->request->getPost('city'),
                'stateID' => $this->request->getPost('stateID'),
                'zipCode' => $this->request->getPost('zipCode'),
                'countryID' => $this->request->getPost('countryID')
            ];
            
            $addressModel = new \App\Models\AddressModel();
            $addressModel->insert($addressData);
            $addressID = $addressModel->insertID();
            
            // Insert project data
            $projectData = [
                'projectName' => $this->request->getPost('project_name'),
                'dateAccepted' => $this->request->getPost('date_accepted'),
                'statusID' => $this->request->getPost('status'),
                'addressID' => $addressID
            ];
            
            $this->projectModel->insert($projectData);
            
            if ($this->projectModel->errors()) {
                throw new \Exception('Error inserting project: ' . json_encode($this->projectModel->errors()));
            }
            
            // Get the newly created projectID
            $projectID = $this->projectModel->insertID();
            
            // Add categories to the project if provided
            $categoryIDs = $this->request->getPost('categories'); // array of category IDs
            if ($categoryIDs) {
                $this->addCategoriesToProject($projectID, $categoryIDs);
            }
    
            // Add tasks to the project if provided
            $taskIDs = $this->request->getPost('tasks'); // array of task IDs
            if ($taskIDs) {
                $this->addTasksToProject($projectID, $taskIDs);
            }
    
            $db->transCommit();
            return redirect()->back()->with('success', 'Project added successfully.');
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'An error occurred: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }
    
}