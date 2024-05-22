<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;
use CodeIgniter\Validation\ValidationInterface;

class UserModel extends Model {
    protected $table = 'users';

    protected $primaryKey = 'id';

    public function __construct() {
        parent::__construct();
    }

    public function getUserByUsername($username) {
        return $this->where('username', $username)->first();
    }

    //public function projects() {
        //return $this->belongsToMany(ProjectModel::class, 'user_project', 'user_id', 'project_id');
    //}
}
