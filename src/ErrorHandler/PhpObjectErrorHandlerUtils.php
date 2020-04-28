<?php

declare(strict_types=1);

namespace PhpObject\ErrorHandler;

class PhpObjectErrorHandlerUtils
{
    /** @var bool */
    protected static $disableCustomErrorHandler = true;

    /** @var bool */
    protected static $customErrorHandlerDisabled = false;

    public static function setDisableCustomErrorHandler(bool $disable): void
    {
        static::$disableCustomErrorHandler = $disable;
    }

    public static function disableCustomErrorHandler(): void
    {
        if (static::$disableCustomErrorHandler === true) {
            set_error_handler(
                function (
                    int $number,
                    string $error,
                    string $file = null,
                    int $line = null,
                    array $context = null
                ): bool {
                    return true;
                }
            );
            static::$customErrorHandlerDisabled = true;
        }
    }

    public static function restorePreviousErrorHandler(): void
    {
        if (static::$customErrorHandlerDisabled === true) {
            restore_error_handler();
            static::$customErrorHandlerDisabled = false;
        }
    }
}
