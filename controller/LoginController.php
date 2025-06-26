<?php

require_once __DIR__ . '/../model/UserModel.php';

class LoginController
{
    public static function login($username, $password)
    {
        $user = UserModel::findByUsername($username);
        if ($user && $user['password'] === $password) {
            $_SESSION['user'] = $user;
            return ['success' => true, 'redirect' => '/home'];
        } else {
            return ['success' => false, 'error' => 'Username atau password salah'];
        }
    }

    public static function logout()
    {
        unset($_SESSION['user']);
        session_destroy();
        return ['success' => true, 'redirect' => '/login'];
    }
}