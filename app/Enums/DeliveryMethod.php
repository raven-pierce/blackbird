<?php

namespace App\Enums;

enum DeliveryMethod: int
{
    case Online = 0;
    case Physical = 1;
    case Hybrid = 2;
}
