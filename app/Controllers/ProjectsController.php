<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use App\Models\UserModel;
use App\Models\StateModel;
use App\Models\CountryModel;
use App\Models\TaskModel;
use App\Models\CategoryModel;
use App\Models\UpdatesModel;

class ProjectsController extends BaseController {

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

    public function index()
    {
        try {
            $userID = auth()->id();
            $user = auth()->user();

            if (!$userID) {
                return redirect()->to('/login')->with('error', 'You must login to access this page.');
            }

            // Fetch all projects and categories
            $projects = $this->projectModel->getProjects();
            foreach ($projects as &$project) {
                $project['assignedUsers'] = $this->projectModel->getAssignedUsers($project['projectID']);
            }

            $categories = $this->categoryModel->findAll();

            // Pass data to the view
            $data = [
                "projects" => $projects,
                'user1' => $user,
                'categories' => $categories
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

            $userID = auth()->id();
            $user = auth()->user();

            // Fetch data from the model.
            $projects = $this->projectModel->searchProjects($searchTerm);

            // Fetch assigned users for each project
            foreach ($projects as &$project) {
                $project['assignedUsers'] = $this->projectModel->getAssignedUsers($project['projectID']);
            }

            // Pass the data to the view.
            return view('PMS/projects', [
                'projects' => $projects,
                'searchTerm' => $searchTerm,
                'user1' => $user
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error in search: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while searching. Please try again.');
        }
    }

    // function to filter projects based on status.
    public function filter()
    {
        $filters = [
            'status' => $this->request->getGet('status'),
            'category' => $this->request->getGet('category'),
            'assignedUser' => $this->request->getGet('assignedUser'),
            'startDate' => $this->request->getGet('startDate'),
            'endDate' => $this->request->getGet('endDate')
        ];

        try {
            $userID = auth()->id();
            $user = auth()->user();

            if (!$userID) {
                return redirect()->to('/login')->with('error', 'You must login to access this page.');
            }

            // Fetch filtered projects
            $projects = $this->projectModel->filterProjects($filters);

            // Optionally, get the list of categories if needed for the view
            $categories = $this->categoryModel->findAll();

            // Pass filtered projects and necessary data to the view
            $data = [
                'projects' => $projects,
                'categories' => $categories,
                'user1' => $user
            ];

            return view('PMS/projects', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error in filter: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Internal server error']);
        }
    }

      
    public function projectDetails($projectID) {
        try {
            $userID = auth()->id();
            if (!$userID) {
                return redirect()->to('/login')->with('error', 'You must log in to access this page.');
            }
    
            // Fetch project ID from the URL if not provided
            if ($projectID === null) {
                $projectID = $this->request->getUri()->getSegment(3); // Assumes it's the 3rd segment in the URL
            }
            
            // Fetch project details from the model
            $project = $this->projectModel->findProjectDetails($projectID);
    
            if (!$project) {
                return view('PMS/projectDetails', ['error' => 'Project not found']);
            }
    
            // Fetch updates for the given project
            $updates = $this->updatesModel->getUpdatesByProject($projectID);
    
            // Pass the data to the view
            $data = [
                'project' => $project,
                'updates' => $updates
            ];
    
            return view('PMS/projectDetails', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error in projectDetails: ' . $e->getMessage());
            // Pass the error message to the view
            return view('PMS/projectDetails', ['error' => 'An internal server error occurred.']);
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
    
        // Get all projects and the projects the user is already assigned to.
        $allProjects = $this->projectModel->findAll();
        $assignedProjects = $this->projectModel->getAssignedProjects($userID);
    
        // Filter out projects that are already assigned to the user.
        $unassignedProjects = array_filter($allProjects, function($project) use ($assignedProjects) {
            return !in_array($project['projectID'], array_column($assignedProjects, 'projectID'));
        });
    
        $data['projects'] = $unassignedProjects;
    
        log_message('debug', 'Retrieved users: ' . print_r($data['users'], true));
        log_message('debug', 'Filtered projects: ' . print_r($data['projects'], true));
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
    
        // Reload the users and filter out already assigned projects.
        $userModel = new UserModel();
        $projectModel = new ProjectModel();
        $data['users'] = $userModel->findAll();
    
        // Get all projects and the projects the user is already assigned to.
        $allProjects = $projectModel->findAll();
        $assignedProjects = $projectModel->getAssignedProjects($userID);
    
        // Filter out projects that are already assigned to the user.
        $unassignedProjects = array_filter($allProjects, function($project) use ($assignedProjects) {
            return !in_array($project['projectID'], array_column($assignedProjects, 'projectID'));
        });
    
        $data['projects'] = $unassignedProjects;
    
        // Return the view with the updated data.
        return view('PMS/assignusers', $data);
    }
    
    public function myWork() {
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
    
    public function unassignUsersView() {
        $users = auth()->getProvider();
        $user = auth()->user();
    
        // Superadmin permission check
        if (!$user->inGroup('superadmin')) {
            return redirect()->to('/dashboard')->with('error', 'You do not have proper permissions to view this page.');
        }
    
        $data['users'] = $users->findAll();
    
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

    public function getProjectsForUser($userId) {
        
        // Fetch projects associated with the user
        $assignedProjects = $this->projectModel->getAssignedProjects($userId);
    
        // Return the projects as a JSON response
        return $this->response->setJSON(['projects' => $assignedProjects]);
    }

    public function getUnassignedProjectsForUser($userId) {
        // Fetch all projects
        $allProjects = $this->projectModel->findAll();
    
        // Fetch projects already assigned to the selected user
        $assignedProjects = $this->projectModel->getAssignedProjects($userId);
    
        // Extract project IDs that are already assigned
        $assignedProjectIds = array_column($assignedProjects, 'projectID');
    
        // Filter out assigned projects from the list of all projects
        $unassignedProjects = array_filter($allProjects, function($project) use ($assignedProjectIds) {
            return !in_array($project['projectID'], $assignedProjectIds);
        });
    
        // Return the unassigned projects as a JSON response
        return $this->response->setJSON(['projects' => array_values($unassignedProjects)]);
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

    public function edit($projectID = null) {
        // Initialize the ProjectModel and any other required models
        $projectModel = new ProjectModel();
        $categoryModel = new CategoryModel();
        $taskModel = new TaskModel();
        $stateModel = new StateModel();
        $countryModel = new CountryModel();
    
        // Handle the case where projectID might not be provided
        if ($projectID === null) {
            $projectID = $this->request->getUri()->getSegment(3); // Fetch projectID from URL segment if not provided
        }
    
        // Fetch project details using the findProjectDetails method
        try {
            $project = $projectModel->findProjectDetails($projectID);
        } catch (\Exception $e) {
            // Handle the case where the project isn't found or invalid projectID is given
            return redirect()->to('/projects')->with('error', 'Project not found');
        }
    
        // Fetch all available categories and tasks
        $allCategories = $categoryModel->findAll(); // Fetch all categories
        $allTasks = $taskModel->findAll(); // Fetch all tasks
        $states = $stateModel->findAll(); // Fetch all states
        $countries = $countryModel->findAll(); // Fetch all countries
    
        // Prepare data to pass to the view
        $data = [
            'project' => $project,
            'selectedCategories' => $project['categories'], // Currently selected categories for the project
            'selectedTasks' => $project['tasks'], // Currently selected tasks for the project
            'allCategories' => $allCategories, // All available categories
            'allTasks' => $allTasks, // All available tasks
            'states' => $states, // All available states
            'countries' => $countries // All available countries
        ];
    
        // Load the edit_project view with all necessary data
        return view('PMS/edit_project.php', $data);
    }        

    public function updateProject() {
        $projectID = $this->request->getPost('projectID');
        $projectName = $this->request->getPost('projectName');
        $projectNumber = $this->request->getPost('projectNumber');
        $statusID = $this->request->getPost('statusID');
        $dateAccepted = $this->request->getPost('dateAccepted');
        $categories = $this->request->getPost('categories');
        $tasks = $this->request->getPost('tasks');

        $projectModel = new ProjectModel();
        $categoryModel = new CategoryModel();
        $taskModel = new TaskModel();

        try {
            // Update project details
            $projectModel->update($projectID, [
                'projectName' => $projectName,
                'projectNumber' => $projectNumber,
                'statusID' => $statusID,
                'dateAccepted' => $dateAccepted,
            ]);
    
            // Update project categories
            $projectModel->updateProjectCategories($projectID, $categories);
    
            // Update project tasks
            $projectModel->updateProjectTasks($projectID, $tasks);
    
            return redirect()->to('/projects')->with('success', 'Project updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the project');
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
                'projectNumber' => $this->request->getPost('project_number'),
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

    // function to add the updates that other users provide from the updates table in the database.
    public function addUpdate() {
        $projectID = $this->request->getPost('projectID');
        $userID = auth()->id();
        $updateText = $this->request->getPost('updateText');
    
        // Validate the input.
        if (empty($updateText)) {
            return redirect()->back()->withInput()->with('error', 'Update text is required.');
        }
    
        // Insert the data into the database.
        $this->updatesModel->save([
            'projectID' => $projectID,
            'userID' => $userID,
            'updateText' => $updateText,
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    
        return redirect()->to('/projects/details/' . $projectID)->with('success', 'Update added successfully.');
    }

    public function editUpdate() {
        $updateID = $this->request->getPost('updateID');
        $updateText = $this->request->getPost('updateText');

        $update = $this->updatesModel->getUpdateByID($updateID);

        // Check if the update exists.
        if (!$update) {
            return redirect()->back()->with('error', 'Update not found.');
        }

        // Check if user is allowed to edit the update.
        if ($update['userID'] !== auth()->id() && !auth()->user()->inGroup('superadmin')) {
            return redirect()->back()->with('error', 'You do not have permission to edit this update.');
        }

        // Update the update text
        $this->updatesModel->updateUpdate($updateID, ['updateText' => $updateText]);

        return redirect()->to('/projects/details/' . $update['projectID'])->with('success', 'Update successfully edited.');
    }
    
    public function deleteUpdate($updateID) {
        // Retrieve the update details
        $update = $this->updatesModel->getUpdateByID($updateID);
    
        // Check if the update exists
        if (!$update) {
            return redirect()->back()->with('error', 'Update not found.');
        }
    
        // Check if the user is allowed to delete this update
        if ($update['userID'] !== auth()->id() && !auth()->user()->inGroup('superadmin')) {
            return redirect()->back()->with('error', 'You do not have permission to delete this update.');
        }
    
        // Delete the update
        $this->updatesModel->deleteUpdate($updateID);
    
        // Redirect back to the project details page with a success message
        return redirect()->to('/projects/details/' . $update['projectID'])->with('success', 'Update deleted successfully.');
    }    
    
}