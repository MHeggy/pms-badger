<?php

namespace App\Models;

use CodeIgniter\Model;

class UpdatesModel extends Model {
    protected $table = 'updates';
    protected $primaryKey = 'updateID';
    protected $allowedFields = ['projectID', 'userID', 'updateText', 'timestamp'];

    // function to get all updates by projectID
    public function getUpdatesByProject($projectID) {
        return $this->where('projectID', $projectID)
                    ->join('users', 'users.id = updates.userID')
                    ->select('updates.*, users.username')
                    ->orderBy('timestamp', 'DESC')
                    ->findAll();
    }
    
    // function to add an update to a specific project
    public function addUpdate($data) {
        return $this->insert($data);
    }

    // function to updte an update.
    public function updateUpdate($updateID, $data) {
        return $this->update($updateID, $data);
    }

    // function to delete an update.
    public function deleteUpdate($updateID) {
        return $this->delete($updateID);
    }

    // function to get update by updateID.
    public function getUpdateByID($updateID) {
        return $this->where('updateID', $updateID)
                    ->first();
    }
}