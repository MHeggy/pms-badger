<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;


class UserModel extends ShieldUserModel {

    protected function initialize(): void {
        parent::initialize();
        $this->allowedFields = ['username', 'status', 'status_message', 'active', 'email', 'password_hash', 'firstName', 'lastName', 'last_active'];
    }

}
