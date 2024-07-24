<?php

namespace App\Models;

use CodeIgniter\Model;

class CalendarModel extends Model
{
    protected $table = 'events';
    protected $primaryKey = 'id';

    protected $allowedFields = ['id', 'title', 'start_date', 'end_date', 'created_at', 'updated_at'];

    public function insertEvent($data)
    {
        return $this->insert($data);
    }

    public function updateEvent($id, $data)
    {
        return $this->update($id, $data);
    }

    public function getEventById($id)
    {
        return $this->find($id);
    }

    public function getAllEvents()
    {
        return $this->findAll();
    }
}
