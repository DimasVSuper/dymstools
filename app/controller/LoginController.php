<?php

require_once __DIR__ . '/../model/UserModel.php';
require_once __DIR__ . '/../routes/helper.php'; // Pastikan helper tersedia

class LoginController
{
    /**
     * Proses login user.
     * @param string $username
     * @param string $password
     * @return array
     */
    public static function login($username, $password)
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $user = UserModel::findByUsername($username);
        if ($user && $user['password'] === $password) {
            $_SESSION['user'] = $user;
            return ['success' => true, 'redirect' => base_url('home')];
        } else {
            return ['success' => false, 'error' => 'Username atau password salah'];
        }
    }

    /**
     * Logout user.
     * @return array
     */
    public static function logout()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        unset($_SESSION['user']);
        session_destroy();
        return ['success' => true, 'redirect' => base_url('login')];
    }
}