<?php

declare(strict_types=1);

namespace PhpObject\Core\DateTime;

use PhpObject\Core\Exception\DateTime\InvalidTimestampException;

class TimestampUtils
{
    public static function assertValid(int $timestamp): void
    {
        if ($timestamp < 0) {
            throw new InvalidTimestampException($timestamp);
        }
    }
}
