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
                        ->select('users.id, users.username, users.firstName, users.lastName')
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

    public function insertTimesheetEntry($data) {
        if (empty($data) || !is_array($data)) {
            throw new \Exception('Invalid data provided for timesheet entry.');
        }
    
        // Insert the new timesheet entry
        $builder = $this->db->table('timesheetEntries');
        $builder->insert($data);
    
        // Check if projectName indicates PTO
        if (stripos($data['projectName'], 'PTO') !== false) {
            // Get the related timesheetID
            $timesheetId = $data['timesheetID'];
            $totalHours = $data['totalHours'];
    
            // Update the timesheet's ptoHours
            $this->db->table('timesheets')
                     ->where('timesheetID', $timesheetId)
                     ->set('ptoHours', 'ptoHours + ' . $totalHours, false) // Add hours
                     ->update();
        }
        
        return $this->db->insertID();
    }
    
    public function insertTimesheetEntries($timesheetId, $entries) {
        $builder = $this->db->table('timesheetEntries');

        foreach($entries as $entry) {
            // Checking if the entry is empty, and if so skip it.
            if (empty($entry['projectNumber']) && empty($entry['projectName']) && empty($entry['activityDescription']) &&
                empty($entry['mondayHours']) && empty($entry['tuesdayHours']) && empty($entry['wednesdayHours']) &&
                empty($entry['thursdayHours']) && empty($entry['fridayHours']) && empty($entry['saturdayHours']) &&
                empty($entry['sundayHours']) && empty($entry['totalHours'])) {
                continue; // Skip empty entries
            }
            // preparing the data to be submitted.
            $data = [
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

            $builder->insert($data);
        }
        return $this->$db->affectedRows() > 0;
    }

    public function getTimesheetWithEntries($timesheetId) {
        return $this->db->table('timesheets')
                        ->select('timesheets.*, SUM(timesheetEntries.totalHours) AS totalHours, timesheets.ptoHours')
                        ->join('timesheetEntries', 'timesheets.timesheetID = timesheetEntries.timesheetID', 'left')
                        ->where('timesheets.timesheetID', $timesheetId)
                        ->groupBy('timesheets.timesheetID')
                        ->get()
                        ->getRowArray();
    }    

    public function updateTimesheet($timesheetId, $data) {
        return $this->update($timesheetId, $data);
    }

    public function updateTimesheetEntries($timesheetId, $entries) {
        $this->db->transStart();
    
        // Fetch existing entries for the timesheet
        $existingEntries = $this->db->table('timesheetEntries')
                                    ->where('timesheetID', $timesheetId)
                                    ->get()
                                    ->getResultArray();
    
        // Create a map of existing entries by their entryID
        $existingEntriesMap = [];
        foreach ($existingEntries as $existingEntry) {
            $existingEntriesMap[$existingEntry['entryID']] = $existingEntry;
        }
    
        $updatedEntriesMap = [];
        $batchInsertData = [];
        $totalHours = 0;
    
        foreach ($entries as $entry) {
            if (empty($entry['projectNumber']) && empty($entry['projectName']) && empty($entry['activityDescription']) &&
                empty($entry['mondayHours']) && empty($entry['tuesdayHours']) && empty($entry['wednesdayHours']) &&
                empty($entry['thursdayHours']) && empty($entry['fridayHours']) && empty($entry['saturdayHours']) &&
                empty($entry['sundayHours'])) {
                continue;
            }
    
            // Calculate the total hours for this entry
            $entryTotalHours = array_sum([
                $entry['mondayHours'], $entry['tuesdayHours'], $entry['wednesdayHours'],
                $entry['thursdayHours'], $entry['fridayHours'], $entry['saturdayHours'], $entry['sundayHours']
            ]);
            $totalHours += $entryTotalHours;
    
            if (isset($existingEntriesMap[$entry['entryID']])) {
                // If the entry already exists, update it
                $this->db->table('timesheetEntries')
                         ->where('entryID', $entry['entryID'])
                         ->update([
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
                             'totalHours' => $entryTotalHours,
                             'updatedAt' => date('Y-m-d H:i:s')
                         ]);
                $updatedEntriesMap[$entry['entryID']] = true;
            } else {
                // If the entry doesn't exist, prepare to insert it
                $batchInsertData[] = [
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
                    'totalHours' => $entryTotalHours,
                    'createdAt' => date('Y-m-d H:i:s'),
                    'updatedAt' => date('Y-m-d H:i:s')
                ];
            }
        }
    
        // Insert any new entries
        if (!empty($batchInsertData)) {
            $this->db->table('timesheetEntries')->insertBatch($batchInsertData);
        }
    
        // Delete entries that are no longer present
        foreach ($existingEntriesMap as $entryID => $existingEntry) {
            if (!isset($updatedEntriesMap[$entryID])) {
                $this->db->table('timesheetEntries')->where('entryID', $entryID)->delete();
            }
        }
    
        // Update the total hours in the timesheets table
        $this->db->table('timesheets')->where('timesheetID', $timesheetId)->update(['totalHours' => $totalHours]);
    
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
