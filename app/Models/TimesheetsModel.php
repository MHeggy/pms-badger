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
        return $this->select('timsheets.*, users.username')
        ->join('users', 'users.id = timesheets.userID')
        ->where('timesheets.userID', $userId)
        ->findAll();
    }

    public function insertTimesheet($data)
    {
        $this->db->transStart();
        $timesheetId = $this->insert($data);

        if ($timesheetId) {
            $this->db->transComplete();
            return $timesheetId;
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    public function insertTimesheetEntries($timesheetId, $entries) {
        $this->db->transStart();
        foreach ($entries as $entry) {
            $entry['timesheetID'] = $timesheetId;
            $this->db->table('timesheetEntries')->insert($entry);
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function getUserInfo($userId) {
        return $this->db->table('users')->where('id', $userId)->get()->getRowArray();
    }

    public function getTimesheetEntries($timesheetId) {
        return $this->db->table('timesheetEntries')->where('timesheetID', $timesheetId)->get()->getResultArray();
    }

    public function updateTimesheet($timesheetId, $entries) {
        return $this->update($timesheetId, $data);
    }

    public function updateTimesheetEntries($timesheetId, $entries) {
        $this->db->transStart();
        $this->db->table('timesheetEntries')->where('timesheetID', $timesheetId)->delete();
        foreach($entries as $entry) {
            $entry['timesheetID'] = $timesheetId;
            $this->db->table('timesheetEntries')->insert($entry);
        }
        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function deleteTimesheetEntries($timesheetId) {
        return $this->db->table('timesheetEntries')->where('timesheetID', $timesheetId)->delete();
    }

    public function getTimesheetEntriesFromRequest() {
        $entries = [];
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        foreach ($daysOfWeek as $day) {
            $hours = $this->request()->getPost((strtolower($day) . 'Hours'));
            $totalHours = $this->calculateTotalHoursForDay($hours);

            if ($hours) {
                $entries[] = [
                    'projectNumber' => $this->request->getPost('projectNumber'),
                    'projectName' => $this->request->getPost('projectName'),
                    'activityDescription' => $this->request->getPost('activityDescription'),
                    'mondayHours' => $day === 'Monday' ? $hours : null,
                    'tuesdayHours' => $day === 'Tuesday' ? $hours : null,
                    'wednesdayHours' => $day === 'Wednesday' ? $hours : null,
                    'thursdayHours' => $day === 'Thursday' ? $hours : null,
                    'fridayHours' => $day === 'Friday' ? $hours : null,
                    'saturdayHours' => $day === 'Saturday' ? $hours : null,
                    'sundayHours' => $day === 'Sunday' ? $hours : null,
                    'totalHours' => $totalHours
                ];
            }
        }

        return $entries;
    }

    private function calculateTotalHoursForDay($hours) {
        return array_sum($hours);
    }
}