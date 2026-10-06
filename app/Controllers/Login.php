<?php

namespace App\Controllers;

use App\Models\User;

class Login extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index()
    {
        if (session()->get('is_logged_in')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Login - Puihaha Electric',
            'page' => 'login',
            'error' => session()->getFlashdata('error'),
            'success' => session()->getFlashdata('success'),
            'validation' => session()->getFlashdata('validation'),
        ];

        return view('login', $data);
    }

    public function authenticate()
    {
        $email = trim($this->request->getPost('email'));
        $password = $this->request->getPost('password');

        if (empty($email) || empty($password)) {
            session()->setFlashdata('error', 'Email and password are required.');
            return redirect()->back()->withInput();
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            session()->setFlashdata('error', 'Invalid email or password.');
            return redirect()->back()->withInput();
        }

        if (isset($user['is_active']) && !$user['is_active']) {
            session()->setFlashdata('error', 'Your account is inactive. Please contact support.');
            return redirect()->back()->withInput();
        }

        session()->regenerate(true);
        session()->set([
            'user_id' => $user['id'],
            'user_name' => trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')),
            'user_email' => $user['email'],
            'is_logged_in' => true,
        ]);

        session()->setFlashdata('success', 'Login successful! Welcome back.');

        return redirect()->to('/dashboard');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
