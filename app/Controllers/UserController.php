<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Commands\User;
use CodeIgniter\Shield\Models\UserModel;

class UserController extends BaseController {// start of UserController class.

    protected $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function changePasswordView() {
        return view('PMS/changepassword.php');
    }

    public function changePassword() {
        // get the new password from the form.
        $newPassword = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm');

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->withInput()->with('error', 'Passwords do not match, please try again.');
        }

        $user = auth()->user();

        $user->setPassword($newPassword);

        return redirect()->back()->to('/dashboard')->with('success', 'Password changed successfully!');
    }

}