<?php

declare(strict_types=1);

namespace PhpObject\Core\ErrorHandler;

use PhpObject\Core\Exception\ErrorHandler\PhpErrorException;

class PhpObjectErrorHandlerManager
{
    /** @var bool */
    protected static $errorHandledEnabled = false;

    /** @var PhpError|null */
    protected static $lastError;

    public static function enable(): void
    {
        static::$lastError = null;

        set_error_handler(
            function (
                int $number,
                string $error,
                string $file = null,
                int $line = null,
                array $context = null
            ): bool {
                static::$lastError = new PhpError($number, $error, $file, $line, $context);

                return true;
            }
        );
        static::$errorHandledEnabled = true;
    }

    public static function disable(): ?PhpError
    {
        if (static::$errorHandledEnabled === true) {
            restore_error_handler();
            static::$errorHandledEnabled = false;

            return static::getLastError();
        }

        return null;
    }

    public static function getLastError(): ?PhpError
    {
        return static::$lastError;
    }

    public static function assertNoError(): void
    {
        $lastError = static::getLastError();
        if (
            $lastError instanceof PhpError && (
                $lastError->getNumber() !== E_DEPRECATED
                && $lastError->getNumber() !== E_USER_DEPRECATED
            )
        ) {
            throw new PhpErrorException($lastError);
        }
    }
}
