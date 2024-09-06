<?php

namespace App\Models;

use CodeIgniter\Model;

class StateModel extends Model {
    protected $table = 'states';
    protected $primaryKey = 'stateID';
    protected $allowedFields = ['stateName'];
}
