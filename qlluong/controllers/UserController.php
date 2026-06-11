<?php

require_once __DIR__ . '/../models/User.php';

class UserController
{
    public function index(): void
    {
        $this->login();
    }

    public function login(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = '';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $name = $_POST['name'] ?? '';
            $password = $_POST['password'] ?? '';
            $user = new User();

            if ($user->login($name, $password)) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_role'] = $user->role;
                header('Location: /qlluong/views/salary/home.php');
                exit;
            }

            $error = 'Sai username hoac password';
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    public function register(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $error = '';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $role = (int) ($_POST['role'] ?? 1);
            $user = new User();

            if ($name === '' || $password === '' || $confirmPassword === '') {
                $error = 'Vui long nhap day du thong tin';
            } elseif (!in_array($role, [0, 1], true)) {
                $error = 'Role khong hop le';
            } elseif (strlen($password) < 6) {
                $error = 'Password phai co it nhat 6 ky tu';
            } elseif ($password !== $confirmPassword) {
                $error = 'Password nhap lai khong khop';
            } elseif (!$user->register($name, $password, $role)) {
                $error = 'Username da ton tai';
            } else {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['user_name'] = $user->name;
                $_SESSION['user_role'] = $user->role;
                header('Location: /qlluong/views/salary/home.php');
                exit;
            }
        }

        require __DIR__ . '/../views/auth/register.php';
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];
        session_destroy();

        header('Location: /qlluong/views/auth/login.php');
        exit;
    }
}
