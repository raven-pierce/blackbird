<?php

namespace App\Enums;

enum CourseStatus: int
{
    case Open = 1;
    case Waitlist = 2;
    case Closed = 3;
}
