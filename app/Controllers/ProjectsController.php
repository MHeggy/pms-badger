<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\UserModel;

class ProjectsController extends BaseController {

    protected $projectModel;
    protected $authGroups;

    public function __construct() {
        $this->projectModel = new ProjectModel();
        $this->authGroups = new \Config\AuthGroups();
    }

    public function index() {
        try {
            // Fetch projects from the model
            $projects = $this->projectModel->getProjects();

            // Fetch assigned users for each project
            foreach ($projects as &$project) {
                $project['assignedUsers'] = $this->projectModel->getAssignedUsers($project['projectID']);
            }

            // Get unread notifications for the logged-in user
            $userID = auth()->id();

            if (!$userID) {
                return redirect()->to('/login')->with('error', 'You must login to access this page.');
            }

            // Pass projects and notifications data to the view
            $data = [
                "projects" => $projects
            ];

            // Load the projects view and pass the data
            return view('PMS/projects.php', $data);
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
        // Fetch project details along with status name
        $project = $this->projectModel->findProjectDetails($projectId);

        // Check if project exists
        if (!$project) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Project not found']);
        }

        // Return project details including status name as JSON response
        return $this->response->setJSON(['details' => $project]);
    }

    public function projectsTest() {
        return view('PMS/ProjectsTest.php');
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
        $data['notifications'] = $this->notificationModel->getUnreadNotifications($userID);

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
        $notifications = $this->notificationModel->getUnreadNotifications($userID);
        // Pass the data to the view.
        $data = [
            'pageTitle' => 'My Work',
            'projects' => $assignedProjects,
            'user' => $user,
            'notifications' => $notifications
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
        $data['notifications'] = $this->notificationModel->getUnreadNotifications($userID);
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

    // function to show the addProjects view page.
    public function addProjectsView() {
        return view('PMS/addProjects.php');
    }

    public function add() {
        // Get form data
        $data = [
            'projectName' => $this->request->getPost('project_name'),
            'description' => $this->request->getPost('description'),
            'dateAccepted' => $this->request->getPost('date_accepted'),
            'statusID' => $this->request->getPost('status'),
            'projectNumber' => $this->request->getPost('project_number')
        ];

        // Add project to database
        $this->projectModel->addProject($data);

        // Redirect back or to a success page
        return redirect()->back()->with('success', 'Project added successfully.');
    }
}