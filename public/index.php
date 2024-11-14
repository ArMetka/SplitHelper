<?php

declare(strict_types=1);

use App\App;

require __DIR__ . '/../vendor/autoload.php';

const VIEWS_PATH = __DIR__ . '/../views';
const STORAGE_PATH = __DIR__ . '/../storage';

session_start();

Dotenv\Dotenv::createImmutable(__DIR__ . '/..')->load();

(new App())->run();