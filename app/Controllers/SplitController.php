<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\Attributes\Secure;
use App\Enums\SplitAccessLevel;
use App\Exceptions\InvalidArgumentsException;
use App\Services\SplitService;
use App\View;

class SplitController
{
    public function __construct(private SplitService $splitService)
    {
    }

    #[Secure]
    #[Get('/splits')]
    public function listSplits(): View
    {
        $userSplits = $this->splitService->findByOwnerId($_SESSION['user']);
        $publicSplits = $this->splitService->findAllPublic();
        return View::make('splits/list', [
            'user' => $userSplits,
            'public' => $publicSplits
        ]);
    }

    #[Secure]
    #[Get('/splits/create')]
    public function splitCreationPage(): View
    {
        return View::make('splits/create');
    }

    #[Secure]
    #[Post('/splits/create')]
    public function createSplit(): never
    {
        try {
            $splitId = $this->splitService->createSplit(
                $_POST['title'],
                isset($_POST['is_public'])
            );
        } catch (InvalidArgumentsException $e) {
            http_response_code(422);
            header('Location: /splits/create');
            $_SESSION['errors'] = ['splits_create' => $e->getMessage()];
            exit;
        }
        http_response_code(302);
        header('Location: /splits/edit?s=' . $splitId);
        exit;
    }

    #[Secure]
    #[Get('/splits/view')]
    public function viewSplit(): View
    {
        if (!isset($_GET['s']) || !$this->splitService->checkSplitAccess(
                $_GET['s'],
                $_SESSION['user'],
                SplitAccessLevel::Viewer
            )) {
            http_response_code(403);
            View::make('errors/403');
            exit;
        }

        $params = $this->splitService->fetchAllById($_GET['s']);

        echo '<pre>';
        var_dump($params);
        echo '</pre>';
        exit;

        return View::make('splits/view', $params);
    }
}