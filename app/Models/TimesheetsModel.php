<?php

namespace App\Models;

use CodeIgniter\Model;

class TimesheetsModel extends Model
{
    protected $table = 'timesheets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'user_id', 'week_of', 'created_at', 'updated_at'];

    protected $validationRules = [
        'user_id' => 'required|numeric',
        'week_of' => 'required',
    ];

    protected $validationMessages = [];

    protected $skipValidation = false;

    public function getUserTimesheets($userId)
    {
        return $this->select('timesheets.*, users.username, users.pay_rate')
            ->join('users', 'users.id = timesheets.user_id')
            ->where('timesheets.user_id', $userId)
            ->findAll();
    }

    public function insertTimesheet($data, $dailyHours)
    {
        $this->db->transStart();
        $timesheetId = $this->insert($data);

        if ($timesheetId) {
            $dailyHoursData = array_merge(['timesheet_id' => $timesheetId], $dailyHours);
            $this->db->table('daily_hours')->insert($dailyHoursData);
            $this->db->transComplete();
            return $this->db->transStatus();
        } else {
            $this->db->transRollback();
            return false;
        }
    }

    public function getUserInfo($userId)
    {
        return $this->db->table('users')->where('id', $userId)->get()->getRowArray();
    }

    public function getDailyHours($timesheetId)
    {
        return $this->db->table('daily_hours')->where('timesheet_id', $timesheetId)->get()->getRowArray();
    }

    public function updateTimesheet($timesheetId, $data)
    {
        return $this->update($timesheetId, $data);
    }

    public function updateDailyHours($timesheetId, $dailyHours)
    {
        $this->db->transStart();
        $existingRecord = $this->db->table('daily_hours')->where('timesheet_id', $timesheetId)->get()->getRow();

        if ($existingRecord) {
            $this->db->table('daily_hours')->where('timesheet_id', $timesheetId)->update($dailyHours);
        } else {
            $dailyHours['timesheet_id'] = $timesheetId;
            $this->db->table('daily_hours')->insert($dailyHours);
        }

        $this->db->transComplete();
        return $this->db->transStatus();
    }

    public function deleteDailyHours($timesheetId)
    {
        return $this->db->table('daily_hours')->where('timesheet_id', $timesheetId)->delete();
    }
}