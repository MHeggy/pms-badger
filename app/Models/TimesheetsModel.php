<?php

namespace App\Models;

use CodeIgniter\Model;

class TimesheetsModel extends Model {
    protected $table = 'timesheets';
    protected $primaryKey = 'timesheetID';
    protected $allowedFields = ['userID', 'weekOf', 'totalHours', 'createdAt', 'updatedAt'];

    protected $validationRules = [
        'userID' => 'required|numeric',
        'weekOf' => 'required',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    public function getUserTimesheets($userId) {
        return $this->select('timesheets.*, users.username')
                    ->join('users', 'users.id = timesheets.userID')
                    ->where('timesheets.userID', $userId)
                    ->findAll();
    }

    public function insertTimesheet($data) {
        $this->db->transStart();
        $timesheetId = $this->insert($data);

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return false;
        } else {
            $this->db->transComplete();
            return $timesheetId;
        }
    }

    public function insertTimesheetEntries($timesheetId, $entries) {
        foreach ($entries as $entry) {
            $entry['timesheetID'] = $timesheetId;
        }

        // Perform batch entry.
        $this->db->transStart();
        $this->db->table('timesheetEntries')->insertBatch($entries);
        $this->db->transComplete();
    }

    public function getUserInfo($userId) {
        return $this->db->table('users')->where('id', $userId)->get()->getRowArray();
    }

    public function getTimesheetEntries($timesheetId) {
        return $this->db->table('timesheetEntries')->where('timesheetID', $timesheetId)->get()->getResultArray();
    }

    public function updateTimesheet($timesheetId, $data) {
        return $this->update($timesheetId, $data);
    }

    public function updateTimesheetEntries($timesheetId, $entries) {
        // Delete existing entries and perform batch insert for new entries
        $this->db->transStart();
        $this->db->table('timesheetEntries')->where('timesheetID', $timesheetId)->delete();
        
        // Add the timesheetID to each entry
        foreach ($entries as &$entry) {
            $entry['timesheetID'] = $timesheetId;
        }
        
        $this->db->table('timesheetEntries')->insertBatch($entries);
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function deleteTimesheetEntries($timesheetId) {
        return $this->db->table('timesheetEntries')->where('timesheetID', $timesheetId)->delete();
    }

    public function getTimesheetEntriesByTimesheetId($timesheetId) {
        return $this->db->table('timesheetEntries')
        ->where('timesheetID', $timesheetId)
        ->get()
        ->getResultArray();
    }

    public function getTimesheetsWithUsernames($weekOf) {
        $builder = $this->db->table('timesheets');
        $builder->select('timesheets.*, users.username');
        $builder->join('users', 'timesheets.userID = users.id', 'left');
        $builder->where('timesheets.weekOf', $weekOf);
        $query = $builder->get();
        return $query->getResultArray();
    }
    
}
