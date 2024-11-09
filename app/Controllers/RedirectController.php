<?php

namespace App\Controllers;

use App\Attributes\Get;

class RedirectController
{
    #[Get('/auth')]
    #[Get('/login')]
    public function authLoginAliases(): never
    {
        http_response_code(302);
        header('Location: /auth/login');
        exit;
    }

    #[Get('/register')]
    public function authRegisterAliases(): never
    {
        http_response_code(302);
        header('Location: /auth/register');
        exit;
    }

    #[Get('/')]
    public function homeIndexAliases(): never
    {
        http_response_code(302);
        header('Location: /home');
        exit;
    }
}