<?php

namespace App\Controllers;

use App\Models\SupportTicketModel;
use App\Controllers\BaseController;

class SupportController extends BaseController
{
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

    // View individual ticket details
    public function viewTicket($ticketID)
    {
        $supportModel = new SupportTicketModel();
        
        // Find ticket by ID and join with the users table to get the submitter's details
        $ticket = $supportModel
            ->select('support_tickets.*, users.firstName, users.lastName')
            ->join('users', 'users.id = support_tickets.userID')
            ->where('support_tickets.ticketID', $ticketID)
            ->first();

        if (!$ticket) {
            return redirect()->to('/support_tickets')->with('error_message', 'Ticket not found.');
        }

        return view('PMS/ticket_details', ['ticket' => $ticket]);
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
}