<?php

namespace App\Controllers;

use App\Models\UserModel;

class LogoutController extends BaseController {
    protected $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    // function to display logout confirmation page.
    public function logout() {
        // destroy the session.
        session()->destroy();
        // redirect to the home page.
        return redirect()->to('/dashboard');
    }
}