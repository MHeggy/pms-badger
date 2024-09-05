<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Authentication\Passwords\PasswordReset;

class AuthController extends Controller {

    public function forgotPasswordView() {
        return view('PMS/forgot_password.php');
    }

    public function processForgotPassword() {
        $email = $this->request->getPost('email');

        if (empty($email)) {
            return redirect()->back()->with('error', 'Please enter your email address.');
        }

        $user = auth()->getProvider()->findByCredentials([
            'identity' => $email
        ]);

        if (!$user) {
            return redirect()->back()->with('error', 'No user found with that email address.');
        }

        // Use Shield's passwordReset service to send a reset email
        $resetter = service('passwordReset');
        $resetter->send($user);

        return redirect()->back()->with('message', 'Password reset link sent to your email.');
    }

    public function resetPasswordView($token = null) {
        return view('PMS/reset_password', ['token' => $token]);
    }

    public function resetPassword() {
        $token = $this->request->getPost('token');
        $newPassword = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        $resetter = service('passwordReset');
        $user = $resetter->verify($token);

        if (!$user) {
            return redirect()->back()->with('error', 'Invalid or expired token.');
        }

        // Update the user's password using Shield's updatePassword method
        $user->password = $newPassword; // Automatically hashes the password
        $resetter->complete($user);

        return redirect()->to('login')->with('message', 'Password successfully reset.');
    }
}