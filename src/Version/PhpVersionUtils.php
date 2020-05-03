<?php

declare(strict_types=1);

namespace PhpObject\Core\Version;

class PhpVersionUtils
{
    public static function is71(): bool
    {
        return version_compare(PHP_VERSION, '7.1.0', '>=') === true && version_compare(PHP_VERSION, '7.2.0', '<');
    }

    public static function is72(): bool
    {
        return version_compare(PHP_VERSION, '7.2.0', '>=') === true && version_compare(PHP_VERSION, '7.3.0', '<');
    }

    public static function is73(): bool
    {
        return version_compare(PHP_VERSION, '7.3.0', '>=') === true && version_compare(PHP_VERSION, '7.4.0', '<');
    }

    public static function is74(): bool
    {
        return version_compare(PHP_VERSION, '7.4.0', '>=') === true && version_compare(PHP_VERSION, '7.5.0', '<');
    }
}
