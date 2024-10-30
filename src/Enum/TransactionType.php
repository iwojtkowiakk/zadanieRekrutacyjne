<?php

namespace App\Enum;

enum TransactionType: string
{
    case IN = 'IN';
    case OUT = 'OUT';
}
