<?php

declare(strict_types=1);

namespace PhpObject\Core\DateTime;

use PhpObject\Core\ErrorHandler\PhpObjectErrorHandlerManager;

class DateTimeImmutableUtils
{
    public static function createFromTimestamp(int $timestamp): \DateTimeImmutable
    {
        TimestampUtils::assertValid($timestamp);

        PhpObjectErrorHandlerManager::enable();
        $return = (new \DateTimeImmutable())->setTimestamp($timestamp);
        PhpObjectErrorHandlerManager::disable();

        return $return;
    }
}
