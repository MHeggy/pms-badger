<?php

namespace App\Controllers;

use CodeIgniter\Shield\Controllers\UserController as ShieldUserController;
use App\Models\UserModel;

class UserController extends ShieldUserController
{
    public function register()
    {
        $data = [
            'pageTitle' => 'Register',
        ];

        if ($this->request->getMethod() === 'post') {
            $users = new UserModel();

            // Validate input
            $rules = [
                'firstName' => 'required|min_length[2]',
                'lastName'  => 'required|min_length[2]',
                'email'     => 'required|valid_email|is_unique[users.email]',
                'username'  => 'required|min_length[3]|is_unique[users.username]',
                'password'  => 'required|min_length[8]',
                'password_confirm' => 'required|matches[password]',
            ];

            if (!$this->validate($rules)) {
                return view('auth/register', [
                    'validation' => $this->validator,
                    'pageTitle' => 'Register',
                ]);
            }

            // Create a new user
            $user = new \CodeIgniter\Shield\Entities\User([
                'email' => $this->request->getPost('email'),
                'username' => $this->request->getPost('username'),
                'firstName' => $this->request->getPost('firstName'),
                'lastName' => $this->request->getPost('lastName'),
                'password' => $this->request->getPost('password'),
            ]);

            // Save the user
            $users->save($user);

            // Optionally, log the user in after registration or redirect to login
            return redirect()->to('/login')->with('message', 'Registration successful. Please login.');
        }

        return view('auth/register', $data);
    }
    
}