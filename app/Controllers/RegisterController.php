<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;

class RegisterController extends Controller {
    public function register()
    {
        if ($this->request->getMethod() === 'post') {
            $users = model(UserModel::class);

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

            // Create a new User entity
            $user = new User($data);

            // Save the user with the custom fields
            if (!$users->save($user)) {
                return redirect()->back()->with('error', 'Failed to register the user.');
            }

            return redirect()->to('/login')->with('message', 'Registration successful. Please login.');
        }

        return view('PMS/home.php');
    }
}