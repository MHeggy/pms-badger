<?php

namespace App\Models;

use CodeIgniter\Model;

class MessageModel extends Model {
    protected $table = 'messages';
    protected $primaryKey = 'messageID';

    protected $allowedFields = ['id', 'messageText', 'timestamp', 'title'];

    public function insertMessage($data) {
        // insert message into database.
        return $this->insert($data);
    }

    public function getAllMessages() {
        return $this->db->table('messages')
            ->select('messages.*, users.username')
            ->join('userMessages', 'messages.messageID = userMessages.message_id')
            ->join('users', 'users.id = userMessages.user_id')
            ->where('messages.deletedAt', null) // Exclude soft-deleted messages
            ->get()
            ->getResultArray();
    }

    public function associateMessageWithUser($messageID, $userID) {
        // Insert the association into the users_messages table.
        $data = [
            'message_id' => $messageID,
            'user_id' => $userID
        ];
        $this->db->table('userMessages')->insert($data);
    }

    public function deleteMessage($messageID) {
        // Soft delete by updating the 'deletedAt' column
        return $this->db->table('messages')
            ->where('messageID', $messageID)
            ->update(['deletedAt' => date('Y-m-d H:i:s')]);
    }

    public function getMessageByID($messageID) {
        return $this->db->table('messages')
            ->where('messageID', $messageID)
            ->get()
            ->getResultArray();
    }

    public function updateMessage($messageID, $messageText) {
        // Check if message ID is not empty.
        if (!empty($messageID)) {
            // Update the message and return true if successful.
            return $this->update(['messageText' => $messageText], ['messageID' => $messageID]);
        } else {
            // Log or display error if message ID is empty.
            log_message('error', 'Empty message ID.');
            return false;
        }
    }


}