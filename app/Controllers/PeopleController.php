<?php

namespace App\Controllers;

use App\Models\ProjectModel;
use CodeIgniter\Shield\Models\UserModel;
use App\Models\ForumModel;
use App\Models\CalendarModel;


class PeopleController extends BaseController {
    protected $userModel;
    protected $projectModel;
    protected $forumModel;
    protected $calendarModel;

    public function __construct() {
        $this->userModel = new UserModel();
        $this->projectModel = new ProjectModel();
        $this->forumModel = new ForumModel();
        $this->calendarModel = new CalendarModel();
    }

    public function index() {
        $user = auth()->user();

        if (!$user) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        $data['users'] = $this->userModel->findAll(); // fetch all users.
        return view('PMS/people.php', $data);
    }

    public function home()
    {
        $user = auth()->user();
        // check if the user exists
        if (!$user) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        } else {
            $userID = $user->id;
            // fetch projects
            $assignedProjects = $this->projectModel->getAssignedProjects($userID);
            $completedProjects = $this->projectModel->getCompletedProjects($userID);
            $ongoingProjects = $this->projectModel->getOngoingProjects($userID);
            // get total projects count
            $totalProjects = $this->projectModel->countAllResults();
            // Fetch upcoming events
            $calendarModel = new \App\Models\CalendarModel();
            $upcomingEvents = $calendarModel->where('start_date >=', date('Y-m-d H:i:s'))
                ->orderBy('start_date', 'ASC')
                ->findAll();
            $upcomingEventsCount = count($upcomingEvents); // Count the number of upcoming events
        
            $forumPosts = $this->forumModel->getAllPosts();
        
            return view('PMS/home.php', [
                'totalProjects' => $totalProjects,
                'assignedProjects' => $assignedProjects,
                'completedProjects' => $completedProjects,
                'ongoingProjects' => $ongoingProjects,
                'upcomingEventsCount' => $upcomingEventsCount,
                'forumPosts' => $forumPosts,
                'errorMessage' => session()->getFlashdata('error')
            ]);
        }
    }
    
    // function to display the My Settings page to users.
    public function myProfileView($userId)
    {
        // check if the logged-in user is viewing their own profile or if they are superadmin.
        $loggedInUserId = auth()->id();
        $user = auth()->user();

        // if user not logged in redirect to the login page with message telling them to login.
        if (!$user) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        if ($loggedInUserId == $userId || $user->inGroup('superadmin')) {
            // load the profile view.
            return view('PMS/myprofile.php', [
                'userId' => $userId
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