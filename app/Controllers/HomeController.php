<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Config;
use App\DB;
use App\View;

class HomeController
{
    #[Get('/home')]
    public function index(): View
    {
        return View::make('index');
    }
}