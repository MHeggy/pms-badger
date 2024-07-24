<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class ProjectModel extends Model {
    protected $table = 'projects';

    protected $primaryKey = 'projectID';

    protected $allowedFields = ['projectName', 'dateAccepted', 'addressID', 'city', 'zipCode', 'stateID', 'countryID', 'statusID'];

    public function __construct() {
        parent::__construct();
    }

    public function getProjects() {
        $query = $this->db->query('
            SELECT p.*, ps.statusName, GROUP_CONCAT(pc.categoryName) AS categoryNames
            FROM projects p
            JOIN projectstatuses ps ON p.statusID = ps.statusID
            LEFT JOIN project_categories pcj ON p.projectID = pcj.projectID
            LEFT JOIN pcategories pc ON pcj.categoryID = pc.categoryID
            GROUP BY p.projectID, ps.statusName
        ');
        return $query->getResultArray();
    }
    

    public function searchProjects($searchTerm) {
        // query to search for projects by project name.
        $builder = $this->db->table('projects');

        // start building the query.
        $builder->select('*')->join('projectstatuses', 'projects.statusID = projectstatuses.statusID', 'left');

        if (!empty($searchTerm)) {
            $builder->like('projectName', $searchTerm);
        }
        // execute query and return resulting array.
        return $builder->get()->getResultArray();
    }

    public function filterProjectsByStatus($status) {
        $builder = $this->db->table('projects');

        $builder->select('*')->join('projectstatuses', 'projects.statusID = projectstatuses.statusID', 'left');

        // Check if a filter status was selected.
        if (!empty($status)) {
            $builder->where('projects.statusID', $status);
        }

        // execute the query and return resulting array.
        return $builder->get()->getResultArray();
    }

    public function findProjectDetails($projectId) {
        // Define the columns to select.
        $this->select('projects.*, 
                       projectstatuses.statusName, 
                       address.street, 
                       address.city, 
                       address.zipCode, 
                       states.stateName AS stateName, 
                       countries.countryName AS countryName');
        
        // Define the join conditions.
        $this->join('projectstatuses', 'projectstatuses.statusID = projects.statusID');
        $this->join('address', 'address.addressID = projects.addressID');
        $this->join('states', 'states.stateID = address.stateID');
        $this->join('countries', 'countries.countryID = address.countryID');
        
        // Fetch the project details by its ID.
        $project = $this->find($projectId);
    
        if (!$project) {
            throw new \Exception('Project not found or invalid projectId.');
        }
        
        // Fetch categories and tasks
        $categories = $this->db->table('project_categories')
            ->select('pcategories.categoryName')
            ->join('pcategories', 'pcategories.categoryID = project_categories.categoryID')
            ->where('project_categories.projectID', $projectId)
            ->get()
            ->getResultArray();

        $tasks = $this->db->table('project_tasks')
            ->select('tasks.taskName')
            ->join('tasks', 'tasks.taskID = project_tasks.taskID')
            ->where('project_tasks.projectID', $projectId)
            ->get()
            ->getResultArray();

        // Add categories and tasks to the project array
        $project['categories'] = array_column($categories, 'categoryName');
        $project['tasks'] = array_column($tasks, 'taskName');
        
        return $project;
    }
    
    
    public function getUserProjectAssociation($userID, $projectID) {
        // Query the database to check if the association exists
        return $this->db->table('user_project')
            ->where('user_id', $userID)
            ->where('project_id', $projectID)
            ->get()
            ->getRow();
    }

    public function createUserProjectAssociation($userID, $projectID) {
        // Insert a new record into the user_project table
        $data = [
            'user_id' => $userID,
            'project_id' => $projectID
        ];

        $this->db->table('user_project')->insert($data);
    }

    public function getAssignedProjects($userID) {
        $builder = $this->db->table('user_project');
        $builder->select('projects.*, projectstatuses.statusName')
            ->join('projects', 'projects.projectID = user_project.project_id')
            ->join('projectstatuses', 'projects.statusID = projectstatuses.statusID')
            ->where('user_id', $userID);
        $query = $builder->get();
        return $query->getResultArray();
    }

    // function to get the completed projects
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

    // function to find all assigned users in a project
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
        $this->insert($data);
    }

}