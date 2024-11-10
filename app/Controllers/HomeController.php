<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Secure;
use App\View;

class HomeController
{
    #[Get('/home')]
    #[Secure]
    public function index(): View
    {
        return View::make('home/home');
    }
}