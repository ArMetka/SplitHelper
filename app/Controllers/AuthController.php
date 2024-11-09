<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\Exceptions\InvalidArgumentsException;
use App\Services\UserService;
use App\View;

class AuthController
{
    public function __construct(private UserService $userService)
    {
    }

    #[Get('/auth/login')]
    public function loginPage(): View
    {
        return View::make('auth/login');
    }

    #[Get('/auth/register')]
    public function registerPage(): View
    {
        return View::make('auth/register');
    }

    #[Post('/auth/register')]
    public function registerUser(): never
    {
        try {
            $this->userService->registerUser($_POST['username'], $_POST['password']);
        } catch (InvalidArgumentsException) {
            http_response_code(401);
            header('Location: /auth/register');
            exit;
        }
        http_response_code(302);
        header('Location: /home');
        exit;
    }
}