<?php

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Secure;
use App\View;

class SplitController
{
    #[Secure]
    #[Get('/splits')]
    public function listSplits(): View
    {
    }
}