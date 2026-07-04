<?php
namespace App\Enums;

enum SaleStatus: string
{
    case ACTIVE = 'active';
    case CANCELLED = 'cancelled';
}