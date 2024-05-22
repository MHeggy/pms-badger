<?php


namespace Controllers;

use App\Controllers\BaseController;
use App\Models\ProjectModel;
use CodeIgniter\Shield\Models\UserModel;


class PMSController extends BaseController
{
    protected $projectModel;
    protected $userModel;

    public function __construct()
    {
        helper('url');
        $this->projectModel = new ProjectModel();
        $this->userModel = new UserModel();
    }

    public function index(): string
    {
        $user = auth()->user();
        $userID = $user->id;
        // fetch projects.
        $assignedProjects = $this->projectModel->getAssignedProjects($userID);
        // create instance of ProjectModel
        $totalProjects = $this->projectModel->countAllResults();

        return view('PMS/home.php', [
            'totalProjects' => $totalProjects,
            'assignedProjects' => $assignedProjects,
            'errorMessage' => session()->getFlashdata('error')
        ]);
    }

    public function reportsView()
    {
        return view('PMS/reports.php');
    }

    public function activityView()
    {
        return view('PMS/activity.php');
    }

    // function to display the accountant payroll page.
    public function accountant()
    {
        $payrollData = $this->userModel->findAll();
        return view('PMS/accountantpayroll.php', [
            'payrollData' => $payrollData,
        ]);
    }

    // function to display the My Settings page to users.
    public function myProfileView($userId)
    {
        // check if the logged-in user is viewing their own profile or if they are superadmin.
        $loggedInUserId = auth()->id();
        $user = auth()->user();

        if ($loggedInUserId == $userId || $user->inGroup('superadmin')) {
            // load the profile view.
            return view('PMS/myprofile.php', [
                'userId' => $userId,
            ]);
        } else {
            return redirect()->back()->with('error_message', 'Unauthorized access.');
        }
    }

    public function updateProfileView($userId)
    {
        // check if the logged-in user is viewing their own profile or if they are part of superadmin group.
        $loggedInUserId = auth()->id();
        $user = auth()->user();

        if ($loggedInUserId == $userId || $user->inGroup('superadmin')) {
            return view('PMS/updateprofile.php', [
                'userId' => $userId,
            ]);
        } else {
            return redirect()->back()->with('error_message', 'Unauthorized access.');
        }
    }

    // function to update the user's profile.
    public function updateProfile()
    {
        // Check if the form is submitted.
        if ($this->request->getMethod(true) === 'POST') {
            // Get the user ID from the form data.
            $userId = $this->request->getPost('userId');

            // Get other form data.
            $email = $this->request->getPost('email');
            $first_name = $this->request->getPost('first_name');
            $last_name = $this->request->getPost('last_name');
            $username = $this->request->getPost('username');

            // Debugging: Log form data
            log_message('debug', 'User ID: ' . $userId);
            log_message('debug', 'Email: ' . $email);
            log_message('debug', 'First Name: ' . $first_name);
            log_message('debug', 'Last Name: ' . $last_name);
            log_message('debug', 'Username: ' . $username);

            $loggedInUserId = auth()->id();
            $user = auth()->user();

            if ($loggedInUserId == $userId || $user->inGroup('superadmin')) {
                // Prepare the data to update.
                $userData = [];
                if (!empty($email)) {
                    $userData['email'] = $email;
                }
                if (!empty($first_name)) {
                    $userData['first_name'] = $first_name;
                }
                if (!empty($last_name)) {
                    $userData['last_name'] = $last_name;
                }
                if (!empty($username)) {
                    $userData['username'] = $username;
                }

                // Debugging: Log update data
                log_message('debug', 'Update Data: ' . print_r($userData, true));

                // Check if there are any fields to update.
                if (!empty($userData)) {
                    // Update the user's profile using the UserModel.
                    $this->userModel->where('id', $userId)->update($userData);

                    // Redirect back to the profile view with a success message.
                    return redirect()->to(base_url("/update_profile/{$userId}"))->with('success_message', 'Profile updated successfully.');
                } else {
                    return redirect()->back()->with('error_message', 'No fields to update.');
                }
            } else {
                return redirect()->back()->with('error_message', 'Unauthorized access.');
            }
        } else {
            return redirect()->back();
        }
    }


    // function to display the settings page.
    public function settingsView()
    {
        return view('PMS/settings.php');
    }
}