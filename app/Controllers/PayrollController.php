<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Commands\User;
use CodeIgniter\Shield\Models\UserModel;

class PayrollController extends BaseController {
    protected $userModel;

    public function __construct() {
        $this->userModel = auth()->getProvider();
    }

    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        if (!$user->inGroup('accountant') && !$user->inGroup('superadmin')) {
            return redirect()->to('/dashboard')->with('error_message', 'You do not have permissions to view this page.');
        }
        $userID = auth()->id();


        // Fetch users based on search query
        $userData = $this->userModel->findAll();
        return view('PMS/accountantpayroll.php', [
            'userData' => $userData,
        ]);
    }

    public function search()
    {
        // Retrieve search term from the URL query parameters
        $searchTerm = $this->request->getGet('search');

        // Check if the search term is empty
        if (empty($searchTerm)) {
            // If empty, redirect back to the index page
            return redirect()->to('/accountantpayroll')->with('error_message', 'Please provide a search term.');
        }

        // Fetch users based on the search term
        $userData = $this->userModel->like('username', $searchTerm)->findAll();

        // Pass the search results to the view
        return view('PMS/accountantpayroll.php', [
            'userData' => $userData,
        ]);
    }

}
