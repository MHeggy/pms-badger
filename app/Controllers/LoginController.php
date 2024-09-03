<?php

namespace App\Controllers;

use CodeIgniter\Shield\Controllers\LoginController as ShieldLogin;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Shield\Models\UserModel;

class LoginController extends ShieldLogin
{
    
    public function loginAction(): RedirectResponse {
        // Validate the login form input
        $rules = $this->getValidationRules();

        if (! $this->validateData($this->request->getPost(), $rules, [], config('Auth')->DBGroup)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        /** @var array $credentials */
        $credentials = $this->request->getPost(setting('Auth.validFields')) ?? [];
        $credentials = array_filter($credentials);
        $credentials['password'] = $this->request->getPost('password');
        $remember = (bool) $this->request->getPost('remember');

        // Find the user by the credentials provided (usually email or username)
        $users = model(UserModel::class);
        $user = $users->where(setting('Auth.validFields')[0], $credentials[setting('Auth.validFields')[0]])->first();

        // Check if the account is active (email verified)
        if ($user && $user->active == 0) {
            return redirect()->back()->withInput()->with('error', 'Your account is not verified. Please check your email for the verification link.')
                         ->with('resend_verification', true)
                         ->with('email', $user->email);
        }

        // Attempt to log the user in
        $authenticator = auth('session')->getAuthenticator();
        $result = $authenticator->remember($remember)->attempt($credentials);

        if (! $result->isOK()) {
            return redirect()->route('login')->withInput()->with('error', $result->reason());
        }

        // If an action has been defined for login, start it up
        if ($authenticator->hasAction()) {
            return redirect()->route('auth-action-show')->withCookies();
        }

        // Redirect to the desired page after login
        return redirect()->to(config('Auth')->loginRedirect())->withCookies();
    }

    // Resend verification email method (if needed)
    public function resendVerification()
    {
        $email = $this->request->getPost('email');
        $users = model(UserModel::class);
        $emailService = service('email');

        // Find the user by email
        $user = $users->where('email', $email)->first();

        if ($user && $user->active == 0) {
            // Generate a new token
            $newToken = bin2hex(random_bytes(50));
            $users->update($user->id, ['verification_token' => $newToken]);

            // Prepare email
            $emailService->setTo($user->email);
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