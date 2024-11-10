<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Post;
use App\View;

class TestController
{
    #[Get('/test')]
    public function test(): View
    {
        return View::make('test/test');
    }

    #[Post('/test')]
    public function fetchTest(): never
    {
        echo '<pre>';
        var_dump($_POST);
        echo '</pre>';
        exit;
    }
}