<?php

declare(strict_types=1);

namespace PhpObject\Core\Exception;

class PhpObjectExceptionUtils extends \Exception
{
    /** @var bool */
    protected static $addPhpErrorToMessage = false;

    public static function setAddPhpErrorToMessage(bool $add): void
    {
        static::$addPhpErrorToMessage = $add;
    }

    public static function getAddPhpErrorToMessage(): bool
    {
        return static::$addPhpErrorToMessage;
    }
}
