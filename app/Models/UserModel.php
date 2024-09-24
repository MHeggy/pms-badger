<?php
declare(strict_types=1);
namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;
use CodeIgniter\Validation\ValidationInterface;

class UserModel extends ShieldUserModel {
    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $allowedFields = ['username', 'email', 'password_hash', 'firstName', 'lastName', 'phone', 'last_active'];

    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
            ...$this->allowedFields,
            'first_name', // Added
            'last_name',  // Added
        ];
    }

    public function getUserByUsername($username) {
        return $this->where('username', $username)->first();
    }

}
