<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Commands\User;
use CodeIgniter\Shield\Models\UserModel;
use App\Models\NotificationModel;

class PayrollController extends BaseController {
    protected $userModel;

    protected $notificationModel;

    public function __construct() {
        $this->userModel = auth()->getProvider();
        $this->notificationModel = new NotificationModel();
    }

    public function index()
    {
        $user = auth()->user();
        if (!$user->inGroup('accountant') && !$user->inGroup('superadmin')) {
            return redirect()->to('/dashboard')->with('error_message', 'You do not have permissions to view this page.');
        }
        $userID = auth()->id();


        // Fetch users based on search query
        $userData = $this->userModel->findAll();
        $notifications = $this->notificationModel->getUnreadNotifications($userID);
        return view('PMS/accountantpayroll.php', [
            'userData' => $userData,
            'notifications' => $notifications
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
