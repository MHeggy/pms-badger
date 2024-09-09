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

            // Fetch categories for filtering
            $categories = $this->categoryModel->findAll();

            // Debugging statement.
            log_message('debug', 'Assigned Projects for User ID ' . $userID . ': ' . print_r($assignedProjects, true));

            $data = [
                'assignedProjects' => $assignedProjects,
                'categories' => $categories
            ];

            // return the view with the data passed to it as an array.
            return view('PMS/mywork', $data);
        } catch (\Exception $e) {
            // Log the error message to the log file.
            log_message('error', 'Error in myWork: ' . $e->getMessage());
            // Return a JSON response with an error message.
            return redirect()->back()->with('error', 'Unable to fetch your projects, please try again later or speak with an admin');
        }
    }

    public function filter() {
        $userID = auth()->id();
    
        // Ensure the user is logged in
        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }
    
        try {
            // Get filter criteria from the request
            $status = $this->request->getGet('status');
            $category = $this->request->getGet('category');  // Add category filter
    
            // Debugging statements
            log_message('debug', 'Status: ' . $status);
            log_message('debug', 'Category: ' . $category);
    
            // Fetch assigned projects for the user
            $projects = $this->projectModel->getAssignedProjects($userID);
    
            // Fetch all categories (you may already have a model or method to do this)
            $categories = $this->categoryModel->findAll();
    
            // Filter projects by status
            if ($status) {
                $projects = array_filter($projects, function($project) use ($status) {
                    return isset($project['statusID']) && $project['statusID'] == $status;
                });
            }
    
            // Filter projects by category
            if ($category) {
                $projects = array_filter($projects, function($project) use ($category) {
                    return isset($project['categoryNames']) && in_array($category, explode(',', $project['categoryNames']));
                });
            }
    
            // Pass the filtered projects and categories to the view
            $data = [
                'assignedProjects' => $projects,
                'status' => $status,
                'category' => $category,
                'categories' => $categories  // Pass the categories to the view
            ];
    
            return view('PMS/mywork', $data);
        } catch (\Exception $e) {
            log_message('error', 'Error in filter: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Internal server error']);
        }
    }
    
    public function search() {
        $userID = auth()->id();
        $user = auth()->user();
    
        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }
    
        // Get the search term from the request.
        $searchTerm = $this->request->getGet('search');
    
        try {
            // Debugging statement for search term
            log_message('debug', 'Search Term: ' . $searchTerm);
    
            // Fetch and search assigned projects for the user
            $searchResults = $this->projectModel->searchAssignedProjects($userID, $searchTerm);

            // Fetch all the categories available and pass to view again
            $categories = $this->categoryModel->findAll();
    
            // Debugging statement for search results
            log_message('debug', 'Search Results: ' . print_r($searchResults, true));
        
            // Pass the searched projects to the view
            $data = [
                'assignedProjects' => $searchResults,
                'searchTerm' => $searchTerm,
                'user1' => $user,
                'categories' => $categories
            ];
        
            // Render the view with search results
            return view('PMS/mywork', $data);
        } catch (\Exception $e) {
            echo 'Error rendering the view: ' . $e->getMessage();
        }
    }    
       
}