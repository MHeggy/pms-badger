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
        log_message('debug', 'Inserting timesheet: ' . json_encode($data));
        // Check if data is properly structured
        if (empty($data) || !is_array($data)) {
            throw new \Exception('Invalid data provided for insert.');
        }
    
        $builder = $this->db->table('timesheets');
        $result = $builder->insert($data);
    
        // If insert fails, throw an exception with the last query for debugging
        if (!$result) {
            throw new \Exception('Insert failed: ' . $this->db->getLastQuery());
        }
    
        return $this->db->insertID(); // Return the ID of the newly inserted timesheet
    }
    

    public function insertTimesheetEntry($data) {
        log_message('debug', 'Inserting timesheet entry: ' . json_encode($data));
        return $this->db->table('timesheetEntries')->insert($data);
    }

    public function getUserInfo($userId) {
        return $this->db->table('users')->where('id', $userId)->get()->getRowArray();
    }

    public function getTimesheetWithEntries($timesheetId) {
        $builder = $this->db->table('timesheets');
        $builder->select('timesheets.*, timesheetEntries.*');
        $builder->join('timesheetEntries', 'timesheets.timesheetID = timesheetEntries.timesheetID', 'left');
        $builder->where('timesheets.timesheetID', $timesheetId);
        $query = $builder->get();
        return $query->getResultArray();
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
