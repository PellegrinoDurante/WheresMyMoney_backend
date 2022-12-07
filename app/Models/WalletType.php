<?php

namespace App\Models;

use App\Traits\EnumValues;

enum WalletType: string
{
    use EnumValues;

    case Cash = 'cash';
    case Bank = 'bank';
}
