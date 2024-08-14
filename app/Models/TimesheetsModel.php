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
        if (empty($data) || !is_array($data)) {
            throw new \Exception('Invalid data provided for insert.');
        }
    
        $builder = $this->db->table('timesheets');
        $result = $builder->insert($data);
    
        if (!$result) {
            throw new \Exception('Insert failed: ' . $this->db->getLastQuery());
        }
    
        return $this->db->insertID();
    }

    public function insertTimesheetEntries($timesheetId, $entries) {
        $this->db->transStart();

        foreach ($entries as $entry) {
            if (!is_array($entry)) {
                throw new \Exception('Each entry must be an array.');
            }

            // prepare the entry data.
            $entryData = [
                'timesheetID' => $timesheetId,
                'projectNumber' => $entry['projectNumber'],
                'projectName' => $entry['projectName'],
                'activityDescription' => $entry['activityDescription'],
                'mondayHours' => $entry['mondayHours'],
                'tuesdayHours' => $entry['tuesdayHours'],
                'wednesdayHours' => $entry['wednesdayHours'],
                'thursdayHours' => $entry['thursdayHours'],
                'fridayHours' => $entry['fridayHours'],
                'saturdayHours' => $entry['saturdayHours'],
                'sundayHours' => $entry['sundayHours'],
                'totalHours' => $entry['totalHours'],
                'createdAt' => date('Y-m-d H:i:s'),
                'updatedAt' => date('Y-m-d H:i:s')
            ];

            // Debugging statement for the sql query.
            log_message('debug', 'Entry data: ' . print_r($entryData, true));
            // Insert the entry.
            $this->db->table('timesheetEntries')->insert($entryData);
        }

        $this->db->transComplete();

        if (!$this->db->transStatus()) {
            throw new \Exception('Transaction failed: ' . $this->db->getLastQuery());
        }

        return true;
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
        $this->db->transStart();
        $this->db->table('timesheetEntries')->where('timesheetID', $timesheetId)->delete();
        
        foreach ($entries as &$entry) {
            $entry['timesheetID'] = $timesheetId;
            // No need to set 'entryID' as it is auto-incremented
            $entry['createdAt'] = date('Y-m-d H:i:s');
            $entry['updatedAt'] = date('Y-m-d H:i:s');
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
