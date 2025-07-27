<?php
namespace Controllers;

use Core\Controller;
use Core\Auth;

class AuthController extends Controller
{
    public function showLogin(): void
    {
        $this->view('auth/login', ['error' => null], 'plain');
    }

    public function login(): void
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        if ($this->auth->attempt($username, $password)) {
            $this->redirect('/');
        }

        $this->view('auth/login', ['error' => 'Invalid credentials'], 'plain');
    }

    public function logout(): void
    {
        $this->auth->logout();
        $this->redirect('/login');
    }
}