<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Email\Email;

class RegisterController extends Controller {
    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            $users = model(UserModel::class);
            $emailService = service('email');

            // Capture input data
            $data = [
                'email'       => $this->request->getPost('email'),
                'username'    => $this->request->getPost('username'),
                'firstName'   => $this->request->getPost('firstName'),
                'lastName'    => $this->request->getPost('lastName'),
                'password'    => $this->request->getPost('password'),
                'password_confirm' => $this->request->getPost('password_confirm'),
            ];

            // Validate the input data
            $validationRules = [
                'firstName'        => 'required|min_length[2]',
                'lastName'         => 'required|min_length[2]',
                'email'            => 'required|valid_email|is_unique[users.email]',
                'username'         => 'required|min_length[3]|is_unique[users.username]',
                'password'         => 'required|min_length[8]',
                'password_confirm' => 'required|matches[password]',
            ];

            if (!$this->validate($validationRules)) {
                return view('auth/register', [
                    'validation' => $this->validator,
                ]);
            }

            // Hash the password using Shield’s built-in hashing
            $data['password_hash'] = service('passwords')->hash($data['password']);
            unset($data['password']); // Don’t store the plain password

            // Generate a unique token
            $data['verification_token'] = bin2hex(random_bytes(50));
            $data['is_active'] = 0; // Mark user as inactive until email is verified

            // Create a new User entity
            $user = new User($data);

            // Save the user with the custom fields
            if (!$users->save($user)) {
                return redirect()->back()->with('error', 'Failed to register the user.');
            }

            // Prepare email
            $emailService->setTo($data['email']);
            $emailService->setFrom('no-reply@pmsbadger.com', 'PMSBadger');
            $emailService->setSubject('Email Verification');
            $emailService->setMessage(
                "Please click the following link to verify your email address: " .
                site_url("register/verify/{$data['verification_token']}")
            );

            // Send email
            if ($emailService->send()) {
                return redirect()->to('/login')->with('message', 'Registration successful. Please check your email to verify your account.');
            } else {
                return redirect()->to('/register')->with('error', 'Failed to send verification email.');
            }
        }

        return view('auth/register');
    }

    public function verify($token)
    {
        $users = model(UserModel::class);

        $user = $users->where('verification_token', $token)->first();

        if ($user) {
            $users->update($user['id'], [
                'is_active' => 1,
                'verification_token' => null
            ]);

            return redirect()->to('/login')->with('message', 'Email verified successfully! You can now log in.');
        } else {
            return redirect()->to('/login')->with('error', 'Invalid or expired token.');
        }
    }
}
