<?php

namespace App\Models;

use CodeIgniter\Model;

class CalendarModel extends Model {
    protected $table = 'events';

    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'title', 'start_date', 'end_date', 'all_day', 'created_at', 'updated_at'];

    public function insertEvent($data) {
        return $this->insert($data);
    }

    public function updateEvent($id, $data) {
        return $this->db->table('events')->where('id', $id)->update($data);
    }


    public function getEventById($id) {
        return $this->find($id);
    }
    public function getAllEvents() {
        return $this->db->table('events')->select()->get()->getResultArray();
    }

}