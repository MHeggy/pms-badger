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

    public function processForgotPassword()
    {
        $email = $this->request->getPost('email');

        if (empty($email)) {
            return redirect()->back()->with('error', 'Please enter your email address.');
        }

        // Find user by email
        $user = auth()->getProvider()->findByCredentials(['email' => $email]);

        if (!$user) {
            return redirect()->back()->with('error', 'No user found with that email address.');
        }

        // Step 3: Generate a unique token and expiration time
        $token = bin2hex(random_bytes(50));  // Generate a random token
        $expiration = Time::now()->addMinutes(30);  // Token expires in 30 minutes

        // Save the reset token and expiration in the database (you might want to create a separate table for this)
        $db = db_connect();
        $db->table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiration->toDateTimeString(),
        ]);

        // Step 4: Send an email to the user with the reset link
        $resetLink = base_url("/reset-password/$token");

        $emailService = \Config\Services::email();  // Load email service
        $emailService->setTo($email);
        $emailService->setSubject('Password Reset Request');
        $emailService->setMessage("Click here to reset your password: <a href=\"$resetLink\">Reset Password</a>");

        if (!$emailService->send()) {
            return redirect()->back()->with('error', 'Failed to send reset email.');
        }

        return redirect()->back()->with('message', 'Password reset link sent to your email.');
    }

    public function resetPasswordView($token = null) {
        return view('PMS/reset_password', ['token' => $token]);
    }

    public function resetPassword()
    {
        $token = $this->request->getPost('token');
        $newPassword = $this->request->getPost('password');

        $confirmPassword = $this->request->getPost('confirm_password');

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Passwords do not match.');
        }

        // Step 7: Verify the token and check its expiration
        $db = db_connect();
        $resetData = $db->table('password_resets')->where('token', $token)->get()->getRow();

        if (!$resetData || Time::now()->isAfter($resetData->expires_at)) {
            return redirect()->back()->with('error', 'Invalid or expired token.');
        }

        // Step 8: Find the user by email and update the password
        $user = auth()->getProvider()->findByCredentials(['email' => $resetData->email]);

        if (!$user) {
            return redirect()->back()->with('error', 'No user found for the given token.');
        }

        // Update user's password (Shield will handle hashing)
        $user->password = $newPassword;
        auth()->getProvider()->save($user);

        // Step 9: Remove the reset token once used
        $db->table('password_resets')->where('token', $token)->delete();

        return redirect()->to('login')->with('message', 'Password successfully reset. You can now log in.');
    }
}