<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Email\Email;

class EmailController extends Controller {
    public function sendTestEmail() {
        $email = \Config\Services::Email();

        $email->setFrom('mhegeduis@badgerengr.com', 'PMSBadger');
        $email->setTo('mhegeduis@gmail.com');
        $email->setSubject('Test Email');
        $email->setMessage('This is a test email sent from the application.');

        if ($email->send()) {
            echo 'Email sent successfully.';
        } else {
            $data = $email->printDebugger();
            print_r($data);
        }
    }
}