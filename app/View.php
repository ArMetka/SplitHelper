<?php

declare(strict_types=1);

namespace App;

use App\Exceptions\ViewNotFoundException;

class View
{
    public function __construct(
        private string $view,
        private array $params = []
    ) {
    }

    static function make(string $view, array $params = []): static
    {
        return new static($view, $params);
    }

    public function render(): string
    {
        $viewPath = VIEWS_PATH . '/' . $this->view . '.php';

        if (!file_exists($viewPath)) {
            throw new ViewNotFoundException();
        }

        ob_start();

        include $viewPath;

        return (string)ob_get_clean();
    }

    public function getHeader(string $name = 'null', string $active = ''): string
    {
        $home = $ajax = $splits = $github = $test = $username = '';
        if (!empty($active)) {
            $$active = 'class="active"';
        }
        return <<<TEXT
<header class="header">
    <div class="container">
        <div class="user-name">
            <a {$username} href="/me">
                $name
            </a>
        </div>

        <div class="nav">
            <nav class="header-nav">
                <ul class="header-nav-list">
                    <li><a {$home} href="/home">home</a></li>
                    <li><a {$ajax} href="/ajax">ajax</a></li>
                    <li><a {$splits} href="/splits">splits</a></li>
                    <li><a {$github} href="https://github.com/ArMetka/SplitHelper" target="_blank">github</a></li>
                    <li><a {$test} href="/test">test</a></li>
                </ul>
            </nav>
        </div>

        <div class="logout">
            <a href="/auth/logout" class="btn-exit">Log out</a>
        </div>
    </div>
</header>
TEXT;
    }

    public function __toString(): string
    {
        return $this->render();
    }
}