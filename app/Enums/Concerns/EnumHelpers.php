<?php

declare(strict_types=1);

namespace App\Enums\Concerns;

trait EnumHelpers
{
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function asArray(): array
    {
        return array_combine(
            self::names(),
            self::values()
        );
    }
}
