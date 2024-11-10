<?php

namespace App\Middleware;

class AccessMiddleware
{
    public function process(string $access)
    {
        $is_authenticated = isset($_SESSION['user']);

        if ($access === 'default') {
            return;
        }

        if ($access === 'secure') {
            if ($is_authenticated) {
                return;
            } else {
                http_response_code(401);
                header('Location: /auth/login');
            }
        }

        if ($access === 'guest') {
            if (!$is_authenticated) {
                return;
            } else {
                http_response_code(302);
                header('Location: /home');
            }
        }
    }
}