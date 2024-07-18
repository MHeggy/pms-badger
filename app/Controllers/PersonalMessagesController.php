<?php
namespace App\Controllers;

use App\Models\PersonalMessageModel;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Controller;

class PersonalMessagesController extends Controller
{
    protected $personalMessageModel;
    protected $userModel;

    public function __construct()
    {
        $this->personalMessageModel = new PersonalMessageModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $user_id = auth()->id();

        if (!$user_id) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        $threads = $this->personalMessageModel->getThreadsForUser($user_id);

        // Fetch unread message counts for each thread
        foreach ($threads as &$thread) {
            $unreadCount = $this->personalMessageModel->getUnreadMessageCount($user_id, $thread['recipient_id']);
            $thread['unread_count'] = $unreadCount;
        }

        $data['threads'] = $threads;
        $data['users'] = $this->userModel->findAll();
        return view('PMS/message_hub', $data);
    }



    public function personalMessages($chatPartnerId)
    {
        $userID = auth()->id();
        $data['messages'] = $this->personalMessageModel->getMessagesInThread($userID, $chatPartnerId);
        $data['currentUserId'] = $userID;
        $data['chatPartnerId'] = $chatPartnerId;
        $data['chatPartnerName'] = 'Chat Partner'; // Replace with actual chat partner name
        return view('PMS/personalmessages', $data);
    }

    public function sendMessage()
    {
        $sender_id = auth()->id();
        $receiver_id = $this->request->getPost('receiver_id');
        $message = $this->request->getPost('message');

        if (empty($message)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Message cannot be empty']);
        }

        if ($this->personalMessageModel->insertMessageInThread($sender_id, $receiver_id, $message)) {
            return $this->response->setStatusCode(200)->setJSON(['success' => 'Message sent']);
        } else {
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to send message']);
        }
    }

    // Add this method to your controller
    public function getMessages()
    {
        $currentUserId = $this->request->getGet('currentUserId');
        $chatPartnerId = $this->request->getGet('chatPartnerId');

        // Fetch messages for the conversation
        $messages = $this->personalMessageModel->getMessagesInThread($currentUserId, $chatPartnerId);

        // Pass messages to a view and return as HTML
        return $this->response->setJSON($messages);
    }

    // function to edit messages.
    public function editMessage() {
        $messageId = $this->request->getPost('message_id');
        $newMessage = $this->request->getPost('new_message');

        if ($this->personalMessageModel->updateMessage($messageId, $newMessage)) {
            return $this->response->setStatusCode(200)->setJSON(['success'  => 'Message updated']);
        } else {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Unauthorized to edit this message.']);
        }
    }

    public function markAsRead()
    {
        $userId = auth()->id();
        $chatPartnerId = $this->request->getPost('chatPartnerId');

        if (!$chatPartnerId) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Chat partner ID is required']);
        }

        $this->personalMessageModel->markMessagesAsRead($userId, $chatPartnerId);

        return $this->response->setStatusCode(200)->setJSON(['success' => 'Messages marked as read']);
    }




}
