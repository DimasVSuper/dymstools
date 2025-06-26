<?php

class AuthMiddleware
{
    public static function check()
    {
        if (empty($_SESSION['user'])) {
            $_SESSION['auth_error'] = true;
            header('Location: /login');
            exit;
        }
    }
}