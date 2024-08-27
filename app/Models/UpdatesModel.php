<?php

namespace App\Models;

use CodeIgniter\Model;

class UpdatesModel extends Model {
    protected $table = 'updates';
    protected $primaryKey = 'updateID';
    protected $allowedFields = ['projectID', 'userID', 'updateText', 'timestamp'];

    public function getUpdatesByProject($projectID) {
        return $this->where('projectID', $projectID)
                    ->join('users', 'users.id = updates.userID')
                    ->select('updates.*, users.username')
                    ->orderBy('timestamp', 'DESC')
                    ->findAll();
    }
    
    public function addUpdate($data) {
        return $this->insert($data);
    }
}