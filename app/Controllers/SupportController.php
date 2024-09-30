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
}