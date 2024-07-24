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
        $builder = $this->db->table('projects');
        $builder->select('*')->join('projectstatuses', 'projects.statusID = projectstatuses.statusID', 'left');

        if (!empty($searchTerm)) {
            $builder->like('projectName', $searchTerm);
        }
        return $builder->get()->getResultArray();
    }

    public function filterProjectsByStatus($status) {
        $builder = $this->db->table('projects');
        $builder->select('*')->join('projectstatuses', 'projects.statusID = projectstatuses.statusID', 'left');

        if (!empty($status)) {
            $builder->where('projects.statusID', $status);
        }
        return $builder->get()->getResultArray();
    }

    public function findProjectDetails($projectId) {
        $this->select('projects.*, 
                       projectstatuses.statusName, 
                       address.street, 
                       address.city, 
                       address.zipCode, 
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

        $project['categories'] = array_column($categories, 'categoryName');
        $project['tasks'] = array_column($tasks, 'taskName');
        
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
        $builder->select('projects.*, projectstatuses.statusName')
            ->join('projects', 'projects.projectID = user_project.project_id')
            ->join('projectstatuses', 'projects.statusID = projectstatuses.statusID')
            ->where('user_id', $userID);
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

}