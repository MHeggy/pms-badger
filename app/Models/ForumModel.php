<?php

namespace App\Models;

use CodeIgniter\Model;

class ForumModel extends Model {
    protected $table = 'posts';

    protected $allowedFields = ['title', 'content', 'user_id', 'category_id'];

    public function getAllPosts() {
        return $this->db->table('posts')
            ->select('posts.*, users.username, categories.name as category_name, 
                (SELECT COUNT(*) FROM replies WHERE replies.post_id = posts.id) as reply_count')
            ->join('users', 'users.id = posts.user_id')
            ->join('categories', 'categories.id = posts.category_id')
            ->get()
            ->getResultArray();
    }
    
    public function createPost($data) {
        return $this->insert($data);
    }

    public function getPostById($postId) {
        return $this->db->table('posts')
            ->select('posts.*, users.username, categories.name as category_name')
            ->join('users', 'users.id = posts.user_id')
            ->join('categories', 'categories.id = posts.category_id')
            ->where('posts.id', $postId)
            ->get()
            ->getRowArray();
    }

    public function updatePost($postId, $data) {
        return $this->db->table('posts')
            ->where('id', $postId)
            ->update($data);
    }

    public function deletePost($postId) {
        return $this->db->table('posts')
            ->where('id', $postId)
            ->delete();
    }

    public function getPostsByCategory($categoryId) {
        return $this->db->table('posts')
            ->select('posts.*, users.username, categories.name as category_name')
            ->join('users', 'users.id = posts.user_id')
            ->join('categories', 'categories.id = posts.category_id')
            ->where('posts.category_id', $categoryId)
            ->get()
            ->getResultArray();
    }

    public function getReplyCount($postId) {
        return $this->db->table('replies')
            ->where('post_id', $postId)
            ->countAllResults();
    }    

}
