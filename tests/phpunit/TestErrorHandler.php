<?php

declare(strict_types=1);

namespace PhpObject\Tests\PhpUnit;

class TestErrorHandler
{
    /** @var AbstractPhpObjectTestCase */
    protected static $testCase;

    public static function enable(AbstractPhpObjectTestCase $testCase): void
    {
        static::$testCase = $testCase;
        set_error_handler([static::class, 'onError']);
    }

    public static function disable(): void
    {
        restore_error_handler();
    }

    public static function onError(
        int $number,
        string $error,
        string $file = null,
        int $line = null
    ): void {
        static::$testCase->setLastPhpError($number, $error, $file, $line);
    }
}
