<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class Guest extends Access
{
    public function __construct()
    {
        parent::__construct(false, true);
    }
}