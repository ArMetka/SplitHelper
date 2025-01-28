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
        $data = [
            'HOME',
            'PHP_VERSION',
            'PHP_URL',
            'PWD',
            'HTTP_COOKIE',
            'HTTP_ACCEPT_LANGUAGE',
            'HTTP_ACCEPT',
            'HTTP_USER_AGENT',
            'HTTP_HOST',
            'SCRIPT_FILENAME',
            'SERVER_NAME',
            'SERVER_PORT',
            'SERVER_ADDR',
            'REMOTE_PORT',
            'REMOTE_ADDR',
            'SERVER_PROTOCOL',
            'REQUEST_METHOD',
            'REQUEST_TIME_FLOAT',
            'REQUEST_TIME',
        ];
        ob_start();
        echo '<pre>';
        foreach ($data as $datum) {
            echo $datum . ' => ' . $_SERVER[$datum] . '<br>';
        }
        echo '</pre>';
        session_regenerate_id();
        ob_flush();
        return View::make('test/test');
    }

    #[Get('/test1')]
    public function testimg(): View
    {
        echo '<pre>';
        var_dump($_SERVER);
        echo '</pre>';
        exit;
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