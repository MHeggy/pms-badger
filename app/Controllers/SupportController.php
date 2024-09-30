<?php

namespace App\Controllers;

use App\Models\SupportTicketModel;
use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;

class SupportController extends BaseController
{
    public function reportProblem()
    {
        return view('PMS/report_problem');
    }

    public function submitProblem()
    {
        $supportModel = new SupportTicketModel();

        $data = [
            'userID' => user()->id,  // Assuming user is logged in
            'issue_title' => $this->request->getPost('issue_title'),
            'issue_description' => $this->request->getPost('issue_description'),
        ];

        $supportModel->insert($data);

        return redirect()->to('/report_problem')->with('message', 'Problem reported successfully.');
    }

    public function contact()
    {
        return view('support/contact');
    }
}
