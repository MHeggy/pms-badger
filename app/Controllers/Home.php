<?php
namespace Controllers;

use App\Controllers\BaseController;
use App\Models\ProjectModel;
use App\Models\NotificationModel;
use App\Models\ForumModel;
use CodeIgniter\Shield\Models\UserModel;


class Home extends BaseController
{
    protected $projectModel;
    protected $userModel;
    protected $notificationModel;
    protected $forumModel;

    public function __construct()
    {
        helper('url');
        $this->projectModel = new ProjectModel();
        $this->userModel = new UserModel();
        $this->notificationModel = new NotificationModel();
        $this->forumModel = new ForumModel();
    }

    public function index(): string
    {
        $user = auth()->user();
        $userID = $user->id;


        // fetch projects.
        $assignedProjects = $this->projectModel->getAssignedProjects($userID);

        // create instance of ProjectModel
        $totalProjects = $this->projectModel->countAllResults();

        // fetch unreadnotifications
        $notifications = $this->notificationModel->getUnreadNotifications($userID);

        // fetch forum posts
        $forumPosts = $this->forumModel->getAllPosts();
        if ($forumPosts === null) {
            $forumPosts = [];
        }

        return view('PMS/home.php', [
            'totalProjects' => $totalProjects,
            'assignedProjects' => $assignedProjects,
            'notifications' => $notifications,
            'forumPosts' => $forumPosts,
            'errorMessage' => session()->getFlashdata('error')
        ]);
    }
}