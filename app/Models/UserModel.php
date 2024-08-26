<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;
use CodeIgniter\Validation\ValidationInterface;

class UserModel extends ShieldUserModel {
    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $allowedFields = ['username', 'email', 'password_hash', 'firstName', 'lastName', 'phone', 'last_active'];

    public function __construct() {
        parent::__construct();
    }

    public function getUserByUsername($username) {
        return $this->where('username', $username)->first();
    }

}
