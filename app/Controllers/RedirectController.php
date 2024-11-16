<?php

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Guest;
use App\Attributes\Secure;

class RedirectController
{
    #[Get('/auth')]
    #[Get('/login')]
    #[Guest]
    public function authLoginAliases(): never
    {
        http_response_code(302);
        header('Location: /auth/login');
        exit;
    }

    #[Get('/register')]
    #[Guest]
    public function authRegisterAliases(): never
    {
        http_response_code(302);
        header('Location: /auth/register');
        exit;
    }

    #[Get('/')]
    #[Secure]
    public function homeIndexAliases(): never
    {
        http_response_code(302);
        header('Location: /home');
        exit;
    }

    #[Get('/splits/list')]
    #[Secure]
    public function splitsAliases(): never
    {
        http_response_code(302);
        header('Location: /splits');
        exit;
    }
}