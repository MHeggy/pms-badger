<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Email\Email;
use CodeIgniter\Shield\Controllers\RegisterController as ShieldRegisterController;

class MyRegisterController extends ShieldRegisterController {

    public function registerView()
    {
        // Customize the view logic if needed
        if (auth()->loggedIn()) {
            return redirect()->to(config('Auth')->registerRedirect());
        }

        // Check if registration is allowed
        if (! setting('Auth.allowRegistration')) {
            return redirect()->back()->withInput()
                ->with('error', lang('Auth.registerDisabled'));
        }

        // If an action has been defined, start it up.
        $authenticator = auth('session')->getAuthenticator();
        if ($authenticator->hasAction()) {
            return redirect()->route('auth-action-show');
        }

        // Customize or use the default registration view
        return view('PMS/register');  // Use a custom view
    }
    
    public function register()
    {
        // Step 1: Get reCAPTCHA response
        $recaptchaResponse = $this->request->getPost('g-recaptcha-response');
        $secretKey = '6LfE21YqAAAAAM4rv9anUrB9ZyDw8IWTijhJXY8j'; // Use your actual secret key

        // Step 2: Verify reCAPTCHA
        $recaptchaVerifyUrl = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $recaptchaResponse;
        $recaptcha = file_get_contents($recaptchaVerifyUrl);
        $recaptcha = json_decode($recaptcha);

        if (!$recaptcha->success) {
            // If reCAPTCHA verification fails, redirect back with error
            return redirect()->back()->withInput()->with('error', 'reCAPTCHA verification failed. Please try again.');
        }

        // If reCAPTCHA passes, continue with Shield's registration process
        return parent::register(); // Call Shield's default register logic
    }

    public function verify($token) {
        $users = model(UserModel::class);
    
        $user = $users->where('verification_token', $token)->first();
    
        if ($user) {
            // Access object properties using object notation
            $users->update($user->id, [
                'active' => 1,
                'verification_token' => null
            ]);
    
            return redirect()->to('/login')->with('message', 'Email verified successfully! You can now log in.');
        } else {
            return redirect()->to('/login')->with('error', 'Invalid or expired token.');
        }
    }    

    public function resendVerification() {
        $email = $this->request->getPost('email');
        $users = model(UserModel::class);
        $emailService = service('email');
    
        // Find the user by email
        $user = $users->where('email', $email)->first();
    
        if ($user && !$user->active) {  // Corrected object notation
            // Generate a new token
            $newToken = bin2hex(random_bytes(50));
            $users->update($user->id, ['verification_token' => $newToken]);
    
            // Prepare email
            $emailService->setTo($user->email);  // Corrected object notation
            $emailService->setFrom('no-reply@pmsbadger.com', 'PMSBadger');
            $emailService->setSubject('Email Verification');
            $emailService->setMessage(
                "Please click the following link to verify your email address: " .
                site_url("register/verify/{$newToken}")
            );
    
            // Send email
            if ($emailService->send()) {
                return redirect()->to('/login')->with('message', 'A new verification email has been sent. Please check your email.');
            } else {
                return redirect()->to('/login')->with('error', 'Failed to send verification email.');
            }
        } else {
            return redirect()->to('/login')->with('error', 'Invalid email address or account already verified.');
        }
    }

}