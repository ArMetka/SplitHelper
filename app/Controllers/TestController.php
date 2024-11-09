<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Enums\HttpMethod;
use App\View;

class TestController
{
    #[Get('/test')]
    public function test(): View
    {
        return View::make('test');
    }
}