<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Email\Email;

class EmailController extends Controller {
    public function sendTestEmail() {
        $email = \Config\Services::email();

        $email->setTo('mhegeduis@gmail.com');
        $email->setFrom('no-reply@pmsbadger.com', 'PMSBadger');
        $email->setSubject('Test Email');
        $email->setMessage('This is a test email to check the configuration.');

        if ($email->send()) {
            echo 'Email sent successfully!';
        } else {
            $data = $email->printDebugger();
            echo 'Failed to send email. Debug info: <pre>' . print_r($data, true) . '</pre>';
        }
    }
}