<?php

namespace App\Enums;

enum SplitAccessLevel
{
    case Owner;
    case Editor;
    case Viewer;
}