<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Guest;
use App\Attributes\Post;
use App\Attributes\Secure;
use App\Exceptions\InvalidArgumentsException;
use App\Services\UserService;
use App\View;

class AuthController
{
    public function __construct(private UserService $userService)
    {
    }

    #[Get('/auth/login')]
    #[Guest]
    public function loginPage(): View
    {
        return View::make('auth/login');
    }

    #[Post('/auth/login')]
    #[Guest]
    public function loginUser(): never
    {
        try {
            $this->userService->loginUser($_POST['username'], $_POST['password']);
        } catch (InvalidArgumentsException $e) {
            http_response_code(401);
            header('Location: /auth/login');
            $_SESSION['errors'] = ['login' => $e->getMessage()];
            exit;
        }
        http_response_code(302);
        header('Location: /home');
        session_regenerate_id();
        exit;
    }

    #[Get('/auth/register')]
    #[Guest]
    public function registerPage(): View
    {
        return View::make('auth/register');
    }

    #[Post('/auth/register')]
    #[Guest]
    public function registerUser(): never
    {
        try {
            $this->userService->registerUser($_POST['username'], $_POST['password']);
        } catch (InvalidArgumentsException $e) {
            http_response_code(401);
            header('Location: /auth/register');
            $_SESSION['errors'] = ['register' => $e->getMessage()];
            exit;
        }
        http_response_code(302);
        header('Location: /home');
        session_regenerate_id();
        exit;
    }

    #[Get('/auth/logout')]
    #[Secure]
    public function logoutUser(): never
    {
        http_response_code(401);
        header('Location: /auth/login');
        unset($_SESSION['user']);
        unset($_SESSION['username']);
        session_regenerate_id();
        exit;
    }
}