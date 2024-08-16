<?php

namespace App\Models;

use CodeIgniter\Model;

class TimesheetsModel extends Model {
    protected $table = 'timesheets';
    protected $primaryKey = 'timesheetID';
    protected $allowedFields = ['userID', 'weekOf', 'createdAt', 'updatedAt'];

    protected $validationRules = [
        'userID' => 'required|numeric',
        'weekOf' => 'required',
    ];

    protected $validationMessages = [];
    protected $skipValidation = false;

    public function getUserInfo($userId) {
        return $this->db->table('users')
                        ->select('users.id, users.username')
                        ->where('users.id', $userId)
                        ->get()
                        ->getRowArray();
    }

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

        log_message('debug', 'Insert data: ' . print_r($data, true));
    
        $builder = $this->db->table('timesheets');
        $result = $builder->insert($data);
    
        if (!$result) {
            throw new \Exception('Insert failed: ' . $this->db->getLastQuery());
        }
    
        return $this->db->insertID();
    }

    public function insertTimesheetEntries($timesheetId, $entries) {
        $this->db->transStart();
    
        $batchData = [];
        foreach ($entries as $entry) {
            // Check if the entry is empty
            if (empty($entry['projectNumber']) && empty($entry['projectName']) && empty($entry['activityDescription']) &&
                empty($entry['mondayHours']) && empty($entry['tuesdayHours']) && empty($entry['wednesdayHours']) &&
                empty($entry['thursdayHours']) && empty($entry['fridayHours']) && empty($entry['saturdayHours']) &&
                empty($entry['sundayHours'])) {
                continue; // Skip empty rows
            }
    
            // Calculate total hours for the entry
            $totalHours = (float)$entry['mondayHours'] + (float)$entry['tuesdayHours'] + (float)$entry['wednesdayHours'] +
                          (float)$entry['thursdayHours'] + (float)$entry['fridayHours'] + (float)$entry['saturdayHours'] +
                          (float)$entry['sundayHours'];
    
            $batchData[] = [
                'timesheetID' => $timesheetId,
                'projectNumber' => $entry['projectNumber'],
                'projectName' => $entry['projectName'],
                'activityDescription' => $entry['activityDescription'],
                'mondayHours' => $entry['monday'],
                'tuesdayHours' => $entry['tuesday'],
                'wednesdayHours' => $entry['wednesday'],
                'thursdayHours' => $entry['thursday'],
                'fridayHours' => $entry['friday'],
                'saturdayHours' => $entry['saturday'],
                'sundayHours' => $entry['sunday'],
                'totalHours' => $totalHours, // Insert the calculated total hours
                'createdAt' => date('Y-m-d H:i:s'),
                'updatedAt' => date('Y-m-d H:i:s')
            ];
        }
    
        // Log the batch data for debugging
        log_message('debug', 'Batch data: ' . print_r($batchData, true));
    
        if (!empty($batchData)) {
            try {
                $this->db->table('timesheetEntries')->insertBatch($batchData);
            } catch (\Exception $e) {
                log_message('error', 'Insert batch failed: ' . $e->getMessage());
                throw $e;
            }
        } else {
            log_message('error', 'Batch data is empty.');
            throw new \Exception('Batch data is empty.');
        }
    
        $this->db->transComplete();
    
        if (!$this->db->transStatus()) {
            log_message('error', 'Transaction failed.');
            throw new \Exception('Transaction failed.');
        }
    
        return true;
    }
    

    public function getTimesheetWithEntries($timesheetId) {
        $builder = $this->db->table('timesheets');
        $builder->select('timesheets.*, timesheetEntries.*');
        $builder->join('timesheetEntries', 'timesheets.timesheetID = timesheetEntries.timesheetID', 'left');
        $builder->where('timesheets.timesheetID', $timesheetId);
    
        log_message('debug', 'SQL Query: ' . $builder->getCompiledSelect());
    
        $query = $builder->get();
        return $query->getResultArray();
    }

    public function updateTimesheet($timesheetId, $data) {
        return $this->update($timesheetId, $data);
    }

    public function updateTimesheetEntries($timesheetId, $entries) {
        $this->db->transStart();
        $this->db->table('timesheetEntries')->where('timesheetID', $timesheetId)->delete();
        
        $batchData = [];
        foreach ($entries as $entry) {
            if (empty($entry['projectNumber']) && empty($entry['projectName']) && empty($entry['activityDescription']) &&
                empty($entry['mondayHours']) && empty($entry['tuesdayHours']) && empty($entry['wednesdayHours']) &&
                empty($entry['thursdayHours']) && empty($entry['fridayHours']) && empty($entry['saturdayHours']) &&
                empty($entry['sundayHours'])) {
                continue;
            }

            $batchData[] = [
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
                'createdAt' => date('Y-m-d H:i:s'),
                'updatedAt' => date('Y-m-d H:i:s')
            ];
        }

        if (!empty($batchData)) {
            $this->db->table('timesheetEntries')->insertBatch($batchData);
        }

        $this->db->transComplete();
    
        if (!$this->db->transStatus()) {
            log_message('error', 'Transaction failed.');
            throw new \Exception('Transaction failed.');
        }
    
        return true;
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
        return $this->select('timesheets.*, users.username')
                    ->join('users', 'timesheets.userID = users.id', 'left')
                    ->where('timesheets.weekOf', $weekOf)
                    ->get()
                    ->getResultArray();
    }
}
