<?php

namespace App\Models;

use CodeIgniter\Model;

class NotificationModel extends Model {
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = ['message', 'created_at'];

    public function getUnreadNotifications($userId) {
        // Construct the query to select unread notifications for the given user
        return $this->select('notifications.*')
            ->join('user_notifications', 'user_notifications.notification_id = notifications.id')
            ->where('user_notifications.user_id', $userId)
            ->where('user_notifications.is_read', 0)
            ->orderBy('notifications.created_at', 'DESC')
            ->findAll();
    }

    public function markAsRead($notificationId, $userId) {
        $data = [
            'user_id' => $userId,
            'notification_id' => $notificationId,
            'is_read' => 1,
            'read_at' => date('Y-m-d H:i:s')
        ];

        // Insert the record into the user_notifications table
        $this->db->table('user_notifications')->insert($data);
    }

    public function insertNotification($messageID, $userID) {
        $data = [
            'user_id' => $userID,
            'notification_id' => $messageID,
            'is_read' => 0,
            'read_at' => null
        ];
        $this->db->table('user_notifications')->insert($data);
    }

    // Method to get all user IDs except the specified one
    public function getAllUserIDsExcept($userID) {
        return $this->db->table('users')->select('id')->where('id !=', $userID)->get()->getResultArray();
    }

}
