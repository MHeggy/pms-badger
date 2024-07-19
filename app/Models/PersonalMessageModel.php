<?php

namespace App\Models;

use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Model;

class PersonalMessageModel extends Model {
    protected $table = 'personalmessages';
    protected $primaryKey = 'id';
    protected $allowedFields = ['sender_id', 'receiver_id', 'message', 'sent_at'];

    // Function to get all messages between two users
    public function getMessagesBetweenUsers($sender_id, $receiver_id) {
        return $this->where('sender_id', $sender_id)
            ->where('receiver_id', $receiver_id)
            ->orWhere('sender_id', $receiver_id)
            ->where('receiver_id', $sender_id)
            ->orderBy('sent_at', 'ASC')
            ->findAll();
    }

    // Function to insert a new message
    public function insertMessage($data) {
        return $this->insert($data);
    }

    // Function to get all messages for a user
    public function getMessagesForUser($user_id) {
        return $this->select('personalmessages.*, users.username AS sender_name')
            ->join('users', 'users.id = personalmessages.sender_id')
            ->where('personalmessages.sender_id', $user_id)
            ->orWhere('personalmessages.receiver_id', $user_id)
            ->orderBy('personalmessages.sent_at', 'ASC')
            ->findAll();
    }

    public function getSenderName($sender_id) {
        $userModel = new UserModel(); // Assuming UserModel is your user model
        $sender = $userModel->find($sender_id);
        return $sender ? $sender['username'] : 'Unknown';
    }

    public function getThreadsForUser($user_id)
    {
        $threads = [];

        // Fetch all unique user IDs from both sender_id and receiver_id
        $query = $this->select('sender_id, receiver_id')
            ->distinct()
            ->where('receiver_id', $user_id)
            ->orWhere('sender_id', $user_id)
            ->findAll();

        // Iterate over each thread
        foreach ($query as $row) {
            $otherUserId = ($row['sender_id'] == $user_id) ? $row['receiver_id'] : $row['sender_id'];

            // Get the last message in the thread
            $lastMessage = $this->select('message, sent_at')
                ->where('sender_id', $user_id)
                ->where('receiver_id', $otherUserId)
                ->orWhere('sender_id', $otherUserId)
                ->where('receiver_id', $user_id)
                ->orderBy('sent_at', 'DESC')
                ->first();

            // Fetch username of the other participant
            $userModel = new UserModel();
            $otherUser = $userModel->find($otherUserId);
            $thread['username'] = $otherUser->username; // Assuming 'username' is the field in your users table
            $thread['recipient_id'] = $otherUserId; // Add the recipient ID

            // Add the last message to the thread
            if ($lastMessage) {
                $thread['last_message'] = is_array($lastMessage) ? $lastMessage['message'] : $lastMessage->message;
                $thread['last_message_time'] = is_array($lastMessage) ? $lastMessage['sent_at'] : $lastMessage->sent_at;
            } else {
                $thread['last_message'] = '';
                $thread['last_message_time'] = '';
            }

            $threads[] = $thread;
        }
        return $threads;
    }


    // Function to get all messages within a thread
    public function getMessagesInThread($currentUserId, $chatPartnerId)
    {
        // Fetch messages for the conversation including sender's username
        $builder = $this->db->table('personalmessages');
        $builder->select('personalmessages.*, users.username as sender_username');
        $builder->join('users', 'users.id = personalmessages.sender_id');
        $builder->where('personalmessages.sender_id', $currentUserId);
        $builder->where('personalmessages.receiver_id', $chatPartnerId);
        $builder->orWhere('personalmessages.sender_id', $chatPartnerId);
        $builder->where('personalmessages.receiver_id', $currentUserId);
        $builder->orderBy('personalmessages.sent_at', 'ASC');
        $query = $builder->get();

        return $query->getResultArray();
    }


    // Function to insert a new message into a thread
    public function insertMessageInThread($userId, $otherUserId, $message)
    {
        $data = [
            'sender_id' => $userId,
            'receiver_id' => $otherUserId,
            'message' => $message,
            'sent_at' => date('Y-m-d H:i:s') // Assuming 'sent_at' is a datetime field
        ];

        return $this->insert($data);
    }

    // method to update a message by its ID
    public function updateMessage($messageId, $newMessage) {
        $message = $this->find($messageId);

        // check if the message exists and if the sender ID matches the authenticated user.
        if ($message && $message['sender_id'] == auth()->id()) {
            $message['message'] = $newMessage;
            return $this->update($messageId, $message);
        }
        return false; // message not found or unauthorized to edit message.
    }

    public function getUnreadMessageCount($userId, $chatPartnerId)
    {
        return $this->where('receiver_id', $userId)
            ->where('sender_id', $chatPartnerId)
            ->where('is_read', 0)
            ->countAllResults();
    }

    public function markMessagesAsRead($userId, $chatPartnerId)
    {
        $this->where('receiver_id', $userId)
            ->where('sender_id', $chatPartnerId)
            ->set(['is_read' => 1])
            ->update();
    }


}
