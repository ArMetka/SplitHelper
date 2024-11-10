<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Secure extends Access
{
    public function __construct()
    {
        parent::__construct(true, false);
    }
}