<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Concerns\EnumHelpers;

enum PropertyStatus: string
{
    use EnumHelpers;

    case Available = 'available';
    case Sold = 'sold';
    case Pending = 'pending';
}