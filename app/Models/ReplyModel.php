<?php
namespace App\Models;

use CodeIgniter\Model;

class ReplyModel extends Model {
    protected $table = 'replies';

    protected $allowedFields = ['post_id', 'user_id', 'content'];

    // Add any necessary methods for handling replies
    public function getRepliesByPostId($postId) {
        return $this->db->table('replies')
            ->where('post_id', $postId)
            ->get()
            ->getResultArray();
    }

    public function deleteRepliesByPostId($postId) {
        return $this->db->table('replies')
            ->where('post_id', $postId)
            ->delete();
    }

}