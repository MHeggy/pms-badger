<?php

namespace App\Controllers;

use App\Models\MessageModel;
use App\Models\NotificationModel;
use App\Models\PersonalMessageModel;

class MessageController extends BaseController {
    protected $messageModel;
    protected $notificationModel;
    protected $personalMessageModel;

    public function __construct() {
        $this->messageModel = new MessageModel();
        $this->notificationModel = new NotificationModel();
        $this->personalMessageModel = new PersonalMessageModel();
    }

    public function index() {
        $userID = auth()->id();
        // Fetch messages from the database.
        $data['messages'] = $this->messageModel->getAllMessages();
        $data['notifications'] = $this->notificationModel->getUnreadNotifications($userID);

        // Load the messages view with the data from database.
        return view('PMS/forums.php', $data);
    }

    public function personalMessages($chatPartnerId) {
        $userID = auth()->id();
        $data['messages'] = $this->messageModel->getMessagesBetweenUsers($userID, $chatPartnerId);
        $data['currentUserId'] = $userID;
        $data['chatPartnerId'] = $chatPartnerId;
        $data['chatPartnerName'] = 'Chat Partner'; // Replace with actual chat partner name

        return view('PMS/personalmessages.php', $data);
    }

    public function sendMessage() {
        $sender_id = $this->request->getPost('sender_id');
        $receiver_id = $this->request->getPost('receiver_id');
        $message = $this->request->getPost('message');

        if (empty($message)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Message cannot be empty']);
        }

        $data = [
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message' => $message,
        ];

        if ($this->messageModel->insertMessage($data)) {
            return $this->response->setStatusCode(200)->setJSON(['success' => 'Message sent']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to send message']);
        }
    }

    public function getMessages($currentUserId, $chatPartnerId) {
        $messages = $this->messageModel->getMessagesBetweenUsers($currentUserId, $chatPartnerId);
        return $this->response->setJSON($messages);
    }

    // function to store messages
    public function store() {
        // Get the message from the form input.
        $messageText = $this->request->getPost('message');
        $title = $this->request->getPost('title');
        // Get the userID of the currently logged-in user.
        $userID = auth()->id();
        log_message('debug', 'userID variable upon storing message: ' . print_r($userID, true));


        // Insert new message in the database with following array.
        $data = [
            'messageText' => $messageText,
            'title' => $title,
            'timestamp' => date('Y-m-d H:i:s') // current timestamp
        ];

        // Insert the message into the database.
        $messageID = $this->messageModel->insertMessage($data);

        // Associate the message with the current user in the userMessages table.
        $this->messageModel->associateMessageWithUser($messageID, $userID);

        // Redirect back to messages page after post is made.
        return redirect()->to('/messages');
    }

    // function to send notifications to all users except for the current user.
    protected function sendNotifications($messageID, $userID) {
        $userIDs = $this->notificationModel->getAllUserIDsExcept($userID);

        // Create notifications for each user
        foreach ($userIDs as $id) {
            // Insert notification into the database
            $this->notificationModel->insertNotification($messageID, $id);
        }
    }

    public function delete($messageID) {
        // Check if the message exists.
        if (!$this->messageModel->getMessageByID($messageID)) {
            return redirect()->to('/messages');
        }

        // delete the message.
        $this->messageModel->deleteMessage($messageID);

        return redirect()->to('/messages');
    }

    public function update() {
        // Get the message ID and text from the form input.
        $messageID = $this->request->getPost('messageID');
        $messageText = $this->request->getPost('message');

        // Check if both message ID and text are not empty.
        if (!empty($messageID) && !empty($messageText)) {
            // update the message in the database.
            if ($this->messageModel->updateMessage($messageID, $messageText)) {
                return redirect()->to('/messages');
            } else {
                // Log or display error if update fails.
                log_message('error', 'Failed to update message. Message ID: ' . $messageID);
            }
        } else {
            // Log or display error if message ID or text is empty.
            log_message('error', 'Empty message ID or message text.');
        }

        return redirect()->to('/messages');
    }


}