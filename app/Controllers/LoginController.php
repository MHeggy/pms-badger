<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Shield\Models\UserModel;

class LoginController extends Controller
{
    public function login()
    {
        if ($this->request->getMethod() === 'post') {
            $users = model(UserModel::class);

            // Capture input data
            $email = $this->request->getPost('email');
            $password = $this->request->getPost('password');

            // Find the user by email
            $user = $users->where('email', $email)->first();

            // Check if the user exists and the password is correct
            if ($user && service('passwords')->verify($password, $user->password_hash)) {
                
                // Check if the account is active
                if ($user->active == 0) {
                    return redirect()->back()->with('resend_verification', true)
                                             ->with('email', $email)
                                             ->with('error', 'Your account is not verified. Please check your email for the verification link.');
                }

                // Log the user in
                service('auth')->login($user);

                return redirect()->to('/dashboard')->with('message', 'Login successful.');
            } else {
                return redirect()->back()->with('error', 'Invalid credentials.');
            }
        }

        return view('auth/login');
    }

    public function resendVerification()
    {
        $email = $this->request->getPost('email');
        $users = model(UserModel::class);
        $emailService = service('email');

        // Find the user by email
        $user = $users->where('email', $email)->first();

        if ($user && $user->active == 0) {
            // Generate a new token
            $newToken = bin2hex(random_bytes(50));
            $users->update($user->id, ['verification_token' => $newToken]);

            // Prepare email
            $emailService->setTo($user->email);
            $emailService->setFrom('no-reply@pmsbadger.com', 'PMSBadger');
            $emailService->setSubject('Email Verification');
            $emailService->setMessage(
                "Please click the following link to verify your email address: " .
                site_url("register/verify/{$newToken}")
            );

            // Send email
            if ($emailService->send()) {
                return redirect()->to('/login')->with('message', 'A new verification email has been sent. Please check your email.');
            } else {
                return redirect()->to('/login')->with('error', 'Failed to send verification email.');
            }
        } else {
            return redirect()->to('/login')->with('error', 'Invalid email address or account already verified.');
        }
    }
}