<?php

declare(strict_types=1);

namespace App\Enums;

use App\Enums\Concerns\EnumHelpers;

enum PropertyType: string
{
    use EnumHelpers;

    case House = 'house';
    case Apartment = 'apartment';
    case Land = 'land';
    case Villa = 'villa';
    case Studio = 'studio';
    case Office = 'office';
    case Commercial = 'commercial';
}
