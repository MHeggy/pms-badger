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

        if (!$email) {
            return redirect()->back()->with('error', 'Please enter your email address.');
        }

        // Query the auth_identities table for the user's email
        $db = db_connect();
        $builder = $db->table('auth_identities');

        // Find user identity where the secret is the email and type is 'email_password'
        $identity = $builder->where('secret', $email)
                            ->where('type', 'email_password')  // Ensure it's the correct identity type
                            ->first();

        if (!$identity) {
            return redirect()->back()->with('error', 'No user found with that email address.');
        }

        // Now retrieve the user by the user_id from the auth_identities table
        $userModel = model(UserModel::class);
        $user = $userModel->find($identity['user_id']);  // Fetch the user by their ID

        if (!$user) {
            return redirect()->back()->with('error', 'No user found with that email address.');
        }

        // Generate a password reset token and send an email
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