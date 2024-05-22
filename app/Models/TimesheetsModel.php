<?php

namespace App\Models;

use CodeIgniter\Model;

class TimesheetsModel extends Model
{
    protected $table = 'timesheets';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'user_id', 'week_of', 'created_at', 'updated_at']; // Adjusted field names to match your database

    // Optionally, you can define validation rules for your fields
    protected $validationRules = [
        'user_id' => 'required|numeric',
        'week_of' => 'required',
    ];

    protected $validationMessages = [
        // Define custom validation error messages if needed
    ];

    protected $skipValidation = false; // Set to true if you want to skip validation

    public function getUserTimesheets($userId)
    {
        // Fetch timesheet data along with user information and pay rate
        $this->select('timesheets.*, users.username, users.pay_rate');
        $this->join('users', 'users.id = timesheets.user_id');
        $this->where('timesheets.user_id', $userId); // Filter timesheets by user ID
        return $this->findAll();
    }

    public function insertTimesheet($data, $dailyHours)
    {
        // Start transaction
        $this->db->transStart();

        // Insert timesheet data
        $timesheetId = $this->insert($data);

        if ($timesheetId) {
            // Insert daily hours worked
            $dailyHoursData = [
                'timesheet_id' => $timesheetId,
                'monday' => $dailyHours['Monday'],
                'tuesday' => $dailyHours['Tuesday'],
                'wednesday' => $dailyHours['Wednesday'],
                'thursday' => $dailyHours['Thursday'],
                'friday' => $dailyHours['Friday'],
                'saturday' => $dailyHours['Saturday'],
                'sunday' => $dailyHours['Sunday'],
            ];
            $this->db->table('daily_hours')->insert($dailyHoursData);

            // Commit transaction
            $this->db->transComplete();
            return true;
        } else {
            // Rollback transaction
            $this->db->transRollback();
            return false;
        }
    }


    public function getUserInfo($userId) {
        return $this->db->table('users')->where('id', $userId)->get()->getRowArray();
    }

    // function to get the user's daily hours.
    public function getDailyHours($timesheetId) {
        return $this->db->table('daily_hours')->where('timesheet_id', $timesheetId)->get()->getResultArray();
    }

    // function to update.
    public function updateTimesheet($timesheetId, $data) {
        return $this->update($timesheetId, $data);
    }
    public function updateDailyHours($timesheetId, $dailyHours) {
        // Start transaction
        $this->db->transStart();

        // Check if a record already exists for the timesheet
        $existingRecord = $this->db->table('daily_hours')->where('timesheet_id', $timesheetId)->get()->getRow();

        if ($existingRecord) {
            // Update the existing record with new daily hours
            $this->db->table('daily_hours')->where('timesheet_id', $timesheetId)->update($dailyHours);
        } else {
            // If no record exists, insert a new one
            $dailyHours['timesheet_id'] = $timesheetId;
            $this->db->table('daily_hours')->insert($dailyHours);
        }

        // Commit transaction
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    // Add a method to delete daily hours records associated with a timesheet
    public function deleteDailyHours($timesheetId) {
        // Delete daily hours records where timesheet_id matches the provided $timesheetId
        return $this->db->table('daily_hours')->where('timesheet_id', $timesheetId)->delete();
    }



}
