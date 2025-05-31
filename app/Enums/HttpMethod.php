<?php

namespace App\Enums;

enum HttpMethod: string
{
    case Get = 'get';
    case Post = 'post';
    case Delete = 'delete';
}