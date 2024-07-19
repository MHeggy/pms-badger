<?php

namespace App\Controllers;

use App\Models\ForumModel;
use App\Models\ReplyModel;
use CodeIgniter\Controller;

class ForumController extends Controller {

    protected $forumModel;
    protected $replyModel;

    public function __construct() {
        $this->forumModel = new ForumModel();
        $this->replyModel = new ReplyModel();
    }

    public function index() {
        $userID = auth()->id();
        $categoryId = $this->request->getGet('category_id');

        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        if ($categoryId) {
            $data['posts'] = $this->forumModel->getPostsByCategory($categoryId);
        } else {
            $data['posts'] = $this->forumModel->getAllPosts();
        }

        $data['categories'] = $this->forumModel->db->table('categories')->get()->getResultArray();
        return view('PMS/forums.php', $data);
    }


    public function createPost() {
        $userID = auth()->id();
        $postData = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'user_id' => $userID,
            'category_id' => $this->request->getPost('category_id')  // Get category_id from the form
        ];

        $this->forumModel->createPost($postData);
        return redirect()->to('/forums');
    }

    public function viewPost($postId) {
        $data['post'] = $this->forumModel->getPostById($postId);
        // fetch replies using the replymodel.
        $data['replies'] = $this->replyModel->getRepliesByPostId($postId);
        return view('PMS/forumdetails.php', $data);
    }

    public function replyToPost($postId) {
        // Handle form submission for replying to a post
        $userID = auth()->id();
        $replyData = [
            'post_id' => $postId,
            'user_id' => $userID,
            'content' => $this->request->getPost('reply_content')
        ];

        $this->replyModel->insert($replyData);

        // Redirect back to the view post page
        return redirect()->to("/forums/view/{$postId}");
    }

    public function updatePost($postId) {
        $postData = [
            'title' => $this->request->getPost('title'),
            'content' => $this->request->getPost('content'),
            'category_id' => $this->request->getPost('category_id')  // Get category_id from the form
        ];

        $this->forumModel->update($postId, $postData);

        return redirect()->to("/forums/view/{$postId}");
    }

    public function deletePost($postId) {
        // Delete all replies associated with the post
        $this->replyModel->deleteRepliesByPostId($postId);

        // Now delete the post
        $this->forumModel->deletePost($postId);

        return redirect()->to('/forums')->with('success', 'Post and its replies have been deleted.');
    }

}