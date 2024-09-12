<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class ProjectModel extends Model {
    protected $table = 'projects';

    protected $primaryKey = 'projectID';

    protected $allowedFields = ['projectNumber', 'projectName', 'dateAccepted', 'addressID', 'city', 'zipCode', 'stateID', 'countryID', 'statusID'];

    public function __construct() {
        parent::__construct();
    }

    public function getProjects() {
        $builder = $this->db->table('projects');
        $builder->select('
            projects.*, 
            projectstatuses.statusName, 
            GROUP_CONCAT(DISTINCT pcategories.categoryName ORDER BY pcategories.categoryName ASC) AS categoryNames,
            GROUP_CONCAT(DISTINCT users.username ORDER BY users.username ASC) AS assignedUsers
        ')
        ->join('projectstatuses', 'projects.statusID = projectstatuses.statusID', 'left')
        ->join('project_categories', 'projects.projectID = project_categories.projectID', 'left')
        ->join('pcategories', 'project_categories.categoryID = pcategories.categoryID', 'left')
        ->join('user_project', 'projects.projectID = user_project.project_id', 'left')
        ->join('users', 'user_project.user_id = users.id', 'left');
        
        // Grouping by projectID and statusName to ensure unique projects
        $builder->groupBy('projects.projectID, projectstatuses.statusName');
        
        // Execute and return the query result
        return $builder->get()->getResultArray();
    }
    
    
    public function searchProjects($searchTerm) {
        $builder = $this->db->table('projects');
        $builder->select('
            projects.*, 
            projectstatuses.statusName, 
            GROUP_CONCAT(DISTINCT pcategories.categoryName ORDER BY pcategories.categoryName ASC) AS categoryNames,
            GROUP_CONCAT(DISTINCT users.username ORDER BY users.username ASC) AS assignedUsers
        ')
        ->join('projectstatuses', 'projects.statusID = projectstatuses.statusID', 'left')
        ->join('project_categories', 'projects.projectID = project_categories.projectID', 'left')
        ->join('pcategories', 'project_categories.categoryID = pcategories.categoryID', 'left')
        ->join('user_project', 'projects.projectID = user_project.project_id', 'left')
        ->join('users', 'user_project.user_id = users.id', 'left');
        
        if (!empty($searchTerm)) {
            $builder->like('projects.projectName', $searchTerm);
        }
        
        // Include all fields being selected and grouped
        $builder->groupBy('projects.projectID, projectstatuses.statusName');
        return $builder->get()->getResultArray();
    }    

    public function searchAssignedProjects($userID, $searchTerm) {
        $builder = $this->db->table('projects');
        $builder->select('
            projects.*, 
            projectstatuses.statusName, 
            GROUP_CONCAT(DISTINCT pcategories.categoryName ORDER BY pcategories.categoryName ASC) AS categoryNames,
            GROUP_CONCAT(DISTINCT users.username ORDER BY users.username ASC) AS assignedUsers
        ')
        ->join('projectstatuses', 'projects.statusID = projectstatuses.statusID', 'left')
        ->join('project_categories', 'projects.projectID = project_categories.projectID', 'left')
        ->join('pcategories', 'project_categories.categoryID = pcategories.categoryID', 'left')
        ->join('user_project', 'projects.projectID = user_project.project_id', 'left')
        ->join('users', 'user_project.user_id = users.id', 'left')
        ->where('users.id', $userID);  // Ensure filtering by user
    
        if (!empty($searchTerm)) {
            $builder->like('projects.projectName', $searchTerm);  // Apply search term
        }
    
        $builder->groupBy('projects.projectID, projectstatuses.statusName');
        return $builder->get()->getResultArray();
    }   
    
    public function filterAssignedProjects($userID, $filters = [])
    {
        $builder = $this->db->table('projects');
        $builder->select('
            projects.*, 
            projectstatuses.statusName, 
            GROUP_CONCAT(DISTINCT pcategories.categoryName ORDER BY pcategories.categoryName ASC) AS categoryNames,
            GROUP_CONCAT(DISTINCT users.username ORDER BY users.username ASC) AS assignedUsers
        ')
        ->join('projectstatuses', 'projects.statusID = projectstatuses.statusID', 'left')
        ->join('project_categories', 'projects.projectID = project_categories.projectID', 'left')
        ->join('pcategories', 'project_categories.categoryID = pcategories.categoryID', 'left')
        ->join('user_project', 'projects.projectID = user_project.project_id', 'left')
        ->join('users', 'user_project.user_id = users.id', 'left')
        ->where('users.id', $userID) // Filter by user assigned to the project
        ->groupBy('projects.projectID, projectstatuses.statusName');

        // Apply filters for status
        if (!empty($filters['status'])) {
            $builder->where('projects.statusID', $filters['status']);
        }

        // Apply filters for category
        if (!empty($filters['category'])) {
            $builder->where('project_categories.categoryID', $filters['category']);
        }

        // Apply search term (if present)
        if (!empty($filters['searchTerm'])) {
            $builder->like('projects.projectName', $filters['searchTerm']);
        }

        // Additional filters can be added as needed
        if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
            $builder->where('projects.dateAccepted >=', $filters['startDate'])
                    ->where('projects.dateAccepted <=', $filters['endDate']);
        }

        return $builder->get()->getResultArray();
    }
    
    public function filterProjectsByStatus($status) {
        $builder = $this->db->table('projects');
        $builder->select('projects.*, projectstatuses.statusName, GROUP_CONCAT(DISTINCT pcategories.categoryName) AS categoryNames, GROUP_CONCAT(DISTINCT tasks.taskName) AS taskNames')
            ->join('projectstatuses', 'projects.statusID = projectstatuses.statusID', 'left')
            ->join('project_categories', 'projects.projectID = project_categories.projectID', 'left')
            ->join('pcategories', 'project_categories.categoryID = pcategories.categoryID', 'left')
            ->join('project_tasks', 'projects.projectID = project_tasks.projectID', 'left')
            ->join('tasks', 'project_tasks.taskID = tasks.taskID', 'left');
    
        if (!empty($status)) {
            $builder->where('projects.statusID', $status);
        }
    
        $builder->groupBy('projects.projectID, projectstatuses.statusName');
        return $builder->get()->getResultArray();
    }
    
    public function filterProjects($filters)
    {
        $builder = $this->db->table('projects');
        $builder->select('projects.*, projectstatuses.statusName, GROUP_CONCAT(DISTINCT pcategories.categoryName) AS categoryNames, GROUP_CONCAT(DISTINCT tasks.taskName) AS taskNames, GROUP_CONCAT(DISTINCT users.username) AS assignedUsers')
                ->join('projectstatuses', 'projects.statusID = projectstatuses.statusID', 'left')
                ->join('project_categories', 'projects.projectID = project_categories.projectID', 'left')
                ->join('pcategories', 'project_categories.categoryID = pcategories.categoryID', 'left')
                ->join('project_tasks', 'projects.projectID = project_tasks.projectID', 'left')
                ->join('tasks', 'project_tasks.taskID = tasks.taskID', 'left')
                ->join('user_project', 'projects.projectID = user_project.project_id', 'left')
                ->join('users', 'user_project.user_id = users.id', 'left')
                ->groupBy('projects.projectID, projectstatuses.statusName');

        // Apply filters
        if (!empty($filters['status'])) {
            $builder->where('projects.statusID', $filters['status']);
        }

        if (!empty($filters['category'])) {
            $builder->where('pcategories.categoryID', $filters['category']);
        }

        if (!empty($filters['assignedUser'])) {
            $builder->where('users.username', $filters['assignedUser']);
        }

        if (!empty($filters['startDate']) && !empty($filters['endDate'])) {
            $builder->where('projects.dateAccepted >=', $filters['startDate'])
                    ->where('projects.dateAccepted <=', $filters['endDate']);
        }

        return $builder->get()->getResultArray();
    }

    
    public function findProjectDetails($projectId) {
        $this->select('projects.*, 
                       projectstatuses.statusName, 
                       address.street, 
                       address.city, 
                       address.zipCode, 
                       address.stateID,
                       address.countryID,
                       states.stateName AS stateName, 
                       countries.countryName AS countryName');
        $this->join('projectstatuses', 'projectstatuses.statusID = projects.statusID');
        $this->join('address', 'address.addressID = projects.addressID');
        $this->join('states', 'states.stateID = address.stateID');
        $this->join('countries', 'countries.countryID = address.countryID');
        
        $project = $this->find($projectId);
    
        if (!$project) {
            throw new \Exception('Project not found or invalid projectId.');
        }
        
        $categories = $this->db->table('project_categories')
            ->select('pcategories.categoryID, pcategories.categoryName')   // Also select categoryID
            ->join('pcategories', 'pcategories.categoryID = project_categories.categoryID')
            ->where('project_categories.projectID', $projectId)
            ->get()
            ->getResultArray();
    
        $tasks = $this->db->table('project_tasks')
            ->select('tasks.taskID, tasks.taskName, project_tasks.deadline')   // Also select taskID
            ->join('tasks', 'tasks.taskID = project_tasks.taskID')
            ->where('project_tasks.projectID', $projectId)
            ->get()
            ->getResultArray();
    
        // Ensure categories and tasks are available
        $project['categories'] = $categories;
        $project['tasks'] = $tasks;
        
        return $project;
    }
    
    public function getUserProjectAssociation($userID, $projectID) {
        return $this->db->table('user_project')
            ->where('user_id', $userID)
            ->where('project_id', $projectID)
            ->get()
            ->getRow();
    }

    public function createUserProjectAssociation($userID, $projectID) {
        $data = [
            'user_id' => $userID,
            'project_id' => $projectID
        ];
        $this->db->table('user_project')->insert($data);
    }

    public function getAssignedProjects($userID) {
        $builder = $this->db->table('user_project');
        $builder->select('projects.projectID, projects.projectNumber, projects.projectName, projects.dateAccepted, projects.statusID, projectstatuses.statusName, GROUP_CONCAT(DISTINCT pcategories.categoryName) AS categoryNames, GROUP_CONCAT(DISTINCT tasks.taskName) AS taskNames')
            ->join('projects', 'projects.projectID = user_project.project_id')
            ->join('projectstatuses', 'projects.statusID = projectstatuses.statusID')
            ->join('project_categories', 'projects.projectID = project_categories.projectID', 'left')
            ->join('pcategories', 'project_categories.categoryID = pcategories.categoryID', 'left')
            ->join('project_tasks', 'projects.projectID = project_tasks.projectID', 'left')
            ->join('tasks', 'project_tasks.taskID = tasks.taskID', 'left')
            ->where('user_project.user_id', $userID)
            ->groupBy('projects.projectID, projectstatuses.statusName, projects.statusID');
        
        $query = $builder->get();
        return $query->getResultArray();
    }
    
    public function getCompletedProjects($userID) {
        $builder = $this->db->table('user_project');
        $builder->select('projects.*, projectstatuses.statusName')
            ->join('projects', 'projects.projectID = user_project.project_id')
            ->join('projectstatuses', 'projects.statusID = projectstatuses.statusID')
            ->where('user_id', $userID)
            ->where('projectstatuses.statusName', 'Completed');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getOngoingProjects($userID) {
        $builder = $this->db->table('user_project');
        $builder->select('projects.*, projectstatuses.statusName')
            ->join('projects', 'projects.projectID = user_project.project_id')
            ->join('projectstatuses', 'projects.statusID = projectstatuses.statusID')
            ->where('user_id', $userID)
            ->where('projectstatuses.statusName !=', 'Completed');
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function getAssignedUsers($projectID) {
        $builder = $this->db->table('user_project');
        $builder->select('users.id, users.username')
            ->join('users', 'users.id = user_project.user_id')
            ->where('user_project.project_id', $projectID);
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function deleteUserProjectAssociation($userID, $projectID) {
        $this->db->table('user_project')
            ->where('user_id', $userID)
            ->where('project_id', $projectID)
            ->delete();
    }

    public function addProject($data) {
        return $this->insert($data);
    }

    public function updateProjectTasks($projectID, $taskIDs) {
        // Start a transaction to ensure atomicity
        $this->db->transStart();
    
        // Delete existing tasks for the project
        $this->db->table('project_tasks')->where('projectID', $projectID)->delete();
    
        // Insert new tasks
        if (!empty($taskIDs)) {
            $data = [];
            foreach ($taskIDs as $taskID) {
                $data[] = [
                    'projectID' => $projectID,
                    'taskID' => $taskID,
                    'deadline' => $deadline
                ];
            }
            $this->db->table('project_tasks')->insertBatch($data);
        }
    
        // Complete the transaction
        $this->db->transComplete();
    
        if ($this->db->transStatus() === FALSE) {
            throw new \Exception('Error updating project tasks.');
        }
    }    

    public function updateProjectCategories($projectID, $categoryIDs) {
        // Start a transaction to ensure atomicity
        $this->db->transStart();
    
        // Delete existing categories for the project
        $this->db->table('project_categories')->where('projectID', $projectID)->delete();
    
        // Insert new categories
        if (!empty($categoryIDs)) {
            $data = [];
            foreach ($categoryIDs as $categoryID) {
                $data[] = [
                    'projectID' => $projectID,
                    'categoryID' => $categoryID
                ];
            }
            $this->db->table('project_categories')->insertBatch($data);
        }
    
        // Complete the transaction
        $this->db->transComplete();
    
        if ($this->db->transStatus() === FALSE) {
            throw new \Exception('Error updating project categories.');
        }
    }
    
}