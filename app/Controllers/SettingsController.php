<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Commands\User;
use CodeIgniter\Shield\Models\UserModel;

class SettingsController extends BaseController {// start of SettingsController class.

    protected $settingsModel;
    protected $userModel;

    // constructor
    public function __construct() {
        $this->settingsModel = new SettingsModel;
        $this->userModel = new UserModel();
    }

    public function changePassword() {

    }

}// end of SettingsController class