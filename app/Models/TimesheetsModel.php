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

}