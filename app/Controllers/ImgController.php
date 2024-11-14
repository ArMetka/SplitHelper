<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Attributes\Get;
use App\Attributes\Secure;
use App\View;
use DirectoryIterator;

class ImgController
{
    #[Secure]
    #[Get('/img')]
    #[Get('/gif')]
    public function getImage(): never
    {
        $img = $_GET['i'] ?? null;
        if (!$img || str_contains($img, '/') || str_contains($img, '\\') || str_contains($img, '..')) {
            http_response_code(403);
            View::make('errors/403');
            exit;
        }

        $allowedFiles = [];
        foreach (new DirectoryIterator(STORAGE_PATH . '/img') as $file) {
            if ($file->isFile()) {
                $allowedFiles[] = $file->getFilename();
            }
        }

        $img = pathinfo($img, PATHINFO_BASENAME);
        if (!is_file(STORAGE_PATH . '/img/' . $img) || !in_array($img, $allowedFiles)) {
            http_response_code(403);
            View::make('errors/403');
            exit;
        }

        $extension = pathinfo($img, PATHINFO_EXTENSION);
        $fp = fopen(STORAGE_PATH . '/img/' . $img, 'rb');
        header("Content-Type: image/" . $extension);
        header("Content-Length: " . filesize(STORAGE_PATH . '/img/' . $img));
        fpassthru($fp);
        exit;
    }

    #[Get('/favicon.ico')]
    public function getIcon(): never
    {
        if (file_exists(STORAGE_PATH . '/img/' . 'favicon.ico')) {
            $fp = fopen(STORAGE_PATH . '/img/' . 'favicon.ico', 'rb');
            header("Content-Type: image/png");
            header("Content-Length: " . filesize(STORAGE_PATH . '/img/' . 'favicon.ico'));
            fpassthru($fp);
        } else {
            http_response_code(503);
        }
        exit;
    }
}