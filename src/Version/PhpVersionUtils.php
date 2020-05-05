<?php

declare(strict_types=1);

namespace PhpObject\Core\Version;

class PhpVersionUtils
{
    public static function is71(): bool
    {
        return PHP_MAJOR_VERSION === 7 && PHP_MINOR_VERSION === 1;
    }

    public static function is72(): bool
    {
        return PHP_MAJOR_VERSION === 7 && PHP_MINOR_VERSION === 2;
    }

    public static function is73(): bool
    {
        return PHP_MAJOR_VERSION === 7 && PHP_MINOR_VERSION === 3;
    }

    public static function is74(): bool
    {
        return PHP_MAJOR_VERSION === 7 && PHP_MINOR_VERSION === 4;
    }
}
