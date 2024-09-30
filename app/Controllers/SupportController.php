<?php

namespace App\Controllers;

use App\Models\SupportTicketModel;
use App\Models\TicketRepliesModel;
use App\Controllers\BaseController;

class SupportController extends BaseController
{

    public function __construct()
    {
        $this->supportModel = new SupportTicketModel();
        $this->repliesModel = new TicketRepliesModel();
    }
    
    public function reportProblem()
    {
        $userID = auth()->id();

        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }
        return view('PMS/report_problem');
    }

    public function submitProblem()
    {
        $user = auth()->user();
        $supportModel = new SupportTicketModel();

        // Handle the file upload
        $attachment = $this->request->getFile('attachment');
        $attachmentName = null;

        if ($attachment && $attachment->isValid() && !$attachment->hasMoved()) {
            // Move the uploaded file to the 'uploads' folder
            $attachmentName = $attachment->getRandomName();
            $attachment->move(WRITEPATH . 'uploads', $attachmentName);
        }

        $data = [
            'userID' => $user->id,  // Assuming user is logged in
            'issue_title' => $this->request->getPost('problemTitle'),
            'issue_description' => $this->request->getPost('problemDescription'),
            'priority' => $this->request->getPost('priority'),
            'attachment' => $attachmentName,  // Save file name
            'status' => 'open',  // Default status is "open"
            'created_at' => date('Y-m-d H:i:s'),
        ];

        // Insert the data into the database
        $supportModel->insert($data);

        // Redirect back with success message
        return redirect()->to('/report_problem')->with('message', 'Problem reported successfully.');
    }

    // Method to display all support tickets
    public function viewSupportTickets()
    {
        $user = auth()->user();
        $userID = auth()->id();

        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        if (!$user->inGroup('superadmin')) {
            return redirect()->to('/dashboard')->with('error_message', 'You do not have permission to access this page.');
        }
        $supportModel = new SupportTicketModel();

        // Perform a join to get user details along with the support tickets
        $tickets = $supportModel
            ->select('support_tickets.*, users.firstName, users.lastName')
            ->join('users', 'users.id = support_tickets.userID')
            ->orderBy('support_tickets.created_at', 'DESC')
            ->findAll();

        // Pass the tickets data to the view
        return view('PMS/view_tickets', ['tickets' => $tickets]);
    }

    public function viewTicket($ticketID)
    {
        $user = auth()->user();
        $userID = auth()->id();

        // Check if the user is authenticated
        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        // Fetch the ticket with the user's details
        $ticket = $this->supportModel
            ->select('support_tickets.*, users.firstName, users.lastName') // Select ticket fields and user details
            ->join('users', 'users.id = support_tickets.userID') // Join with users table
            ->where('support_tickets.ticketID', $ticketID)
            ->first();

        // Check if the ticket exists and if the user has permission to view it
        if (!$ticket || ($ticket['userID'] !== $userID)) {
            return redirect()->to('/view_user_tickets')->with('error', 'You do not have permission to view this ticket.');
        }

        // Fetch replies associated with this ticket
        $replies = $this->repliesModel->where('ticketID', $ticketID)
            ->orderBy('created_at', 'ASC')
            ->findAll();

        return view('PMS/ticket_details', [
            'ticket' => $ticket,
            'replies' => $replies
        ]);
    }


    // Update the ticket's status
    public function updateTicketStatus($ticketID)
    {
        $supportModel = new SupportTicketModel();

        // Check if the ticket exists
        $ticket = $supportModel->find($ticketID);

        if (!$ticket) {
            return redirect()->to('/support_tickets')->with('error_message', 'Ticket not found.');
        }

        // Get the new status from the form submission
        $newStatus = $this->request->getPost('status');

        // Update the ticket's status
        $supportModel->update($ticketID, ['status' => $newStatus]);

        return redirect()->to('/support_ticket/' . $ticketID)->with('message', 'Ticket status updated successfully.');
    }

    public function viewUserTickets()
    {
        $user = auth()->user();
        $userID = auth()->id();

        if (!$userID) {
            return redirect()->to('/login')->with('error', 'You must login to access this page.');
        }

        $supportModel = new SupportTicketModel();

        // Check if the user is a superadmin
        if ($user->inGroup('superadmin')) {
            // Superadmins can view all tickets
            $tickets = $supportModel
                ->select('support_tickets.*, users.firstName, users.lastName')
                ->join('users', 'users.id = support_tickets.userID')
                ->orderBy('support_tickets.created_at', 'DESC')
                ->findAll();
        } else {
            // Regular users can only view their own tickets
            $tickets = $supportModel
                ->select('support_tickets.*, users.firstName, users.lastName')
                ->join('users', 'users.id = support_tickets.userID')
                ->where('support_tickets.userID', $userID)
                ->orderBy('support_tickets.created_at', 'DESC')
                ->findAll();
        }

        return view('PMS/user_tickets', ['tickets' => $tickets]);
    }

    public function addReply($ticketID)
    {
        $userID = auth()->id();
        
        $replyText = $this->request->getPost('reply_text');
        
        if (!$replyText) {
            return redirect()->back()->with('error', 'Reply text cannot be empty.');
        }

        $this->repliesModel->save([
            'ticketID' => $ticketID,
            'userID' => $userID,
            'reply_text' => $replyText
        ]);

        return redirect()->to('/support/ticket/' . $ticketID)->with('success', 'Reply added successfully.');
    }
}