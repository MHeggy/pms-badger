<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use CodeIgniter\Shield\Entities\User;
use Codeigniter\Shield\Authentiation\Authenticators\Session;

class AuthController extends Controller {
    public function register()
    {
        $data = [
            'pageTitle' => 'Register'
        ];

        if ($this->request->getMethod() === 'post') {
            $users = new UserModel();

            // Validate the form input
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
                    'pageTitle' => 'Register'
                ]);
            }

            // Create the user entity
            $user = new User([
                'email' => $this->request->getPost('email'),
                'username' => $this->request->getPost('username'),
                'firstName' => $this->request->getPost('firstName'),
                'lastName' => $this->request->getPost('lastName'),
                'password' => $this->request->getPost('password'),
            ]);

            $users->save($user);

            // Redirect to login page after successful registration
            return redirect()->to('/login')->with('message', 'Registration successful. Please login.');
        }

        return view('auth/register', $data);
    }
}