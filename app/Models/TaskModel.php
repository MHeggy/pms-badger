<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model {
    protected $table = 'tasks';
    protected $primaryKey = 'taskID';
    protected $allowedFields = ['taskName'];

    public function __construct() {
        parent::__construct();
    }

    public function getTasks() {
        return $this->findAll();
    }

    public function addProjectTasks($projectID, $tasks, $deadlines) {
        $db = \Config\Database::connect();
        $builder = $db->table('project_tasks');
        
        foreach ($tasks as $taskID) {
            $deadline = isset($deadlines[$taskID]) ? $deadlines[$taskID] : null;
            $builder->insert([
                'projectID' => $projectID,
                'taskID' => $taskID,
                'deadline' => $deadline
            ]);
        }
    }
}
