<?php

namespace App\Enums;

enum DeliveryMethod: int
{
    case Virtual = 0;
    case Physical = 1;
    case Hybrid = 2;
}
