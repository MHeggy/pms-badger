<?php

namespace App\Models;

use CodeIgniter\Model;

class TaskModel extends Model {
    protected $table = 'tasks';
    protected $primaryKey = 'taskID';
    protected $allowedFields = ['taskName', 'description'];

    public function __construct() {
        parent::__construct();
    }

    public function getTasks() {
        return $this->findAll();
    }
}