<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Controller;
use App\Attributes\Get;
use App\Attributes\Post;
use App\Attributes\Secure;
use App\Exceptions\InvalidArgumentsException;
use App\Services\UserService;
use App\View;

#[Controller]
class MeController
{
    public function __construct(private UserService $userService)
    {
    }

    #[Secure]
    #[Get('/me')]
    public function me(): View
    {
        return View::make('me/me');
    }

    #[Secure]
    #[Post('/me/update')]
    public function updateDisplayedName(): never
    {
        try {
            $this->userService->updateDisplayedName($_SESSION['user'], $_POST['displayed_name']);
        } catch (InvalidArgumentsException $e) {
            http_response_code(422);
            header('Location: /me');
            $_SESSION['errors'] = ['updateDisplayedName' => $e->getMessage()];
            exit;
        }
        http_response_code(302);
        header('Location: /me');
        exit;
    }
}