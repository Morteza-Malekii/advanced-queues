<?php

namespace App\Enums;

enum OrderStatus:string
{
    case PENDING = 'pending';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';
}


