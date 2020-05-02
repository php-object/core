<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception;

use PhpObject\Core\{
    ErrorHandler\PhpError,
    Exception\PhpObjectException
};
use PHPUnit\Framework\TestCase;

abstract class AbstractPhpObjectExceptionTest extends TestCase
{
    protected const EXCEPTION_CODE = 42;
    protected const EXCEPTION_MESSAGE = 'Foo';
    protected const PREVIOUS_EXCEPTION_MESSAGE = 'Bar';
    protected const PHP_ERROR_ERROR = 'PHP error';
    protected const PHP_ERROR_NUMBER = E_NOTICE;
    protected const PHP_ERROR_FILE = '/file';
    protected const PHP_ERROR_LINE = 43;

    protected static function assertExceptionDefaultValues(PhpObjectException $exception): void
    {
        static::assertSame(static::getExceptionMessage(), $exception->getMessage());
        static::assertDefaultPhpError($exception);
        static::assertSame(0, $exception->getCode());
        static::assertNull($exception->getPrevious());
    }

    protected static function assertExceptionPhpError(PhpObjectException $exception): void
    {
        static::assertSame(static::getExceptionMessage(), $exception->getMessage());
        static::assertInstanceOf(PhpError::class, $exception->getPhpError());
        static::assertSame(static::PHP_ERROR_ERROR, $exception->getPhpError()->getError());
        static::assertSame(static::PHP_ERROR_NUMBER, $exception->getPhpError()->getNumber());
        static::assertSame(static::PHP_ERROR_FILE, $exception->getPhpError()->getFile());
        static::assertSame(static::PHP_ERROR_LINE, $exception->getPhpError()->getLine());
        static::assertSame(0, $exception->getCode());
        static::assertNull($exception->getPrevious());
    }

    protected static function assertExceptionCode(PhpObjectException $exception): void
    {
        static::assertSame(static::getExceptionMessage(), $exception->getMessage());
        static::assertDefaultPhpError($exception);
        static::assertSame(static::EXCEPTION_CODE, $exception->getCode());
        static::assertNull($exception->getPrevious());
    }

    protected static function assertExceptionPrevious(PhpObjectException $exception): void
    {
        static::assertSame(static::getExceptionMessage(), $exception->getMessage());
        static::assertDefaultPhpError($exception);
        static::assertSame(0, $exception->getCode());
        static::assertInstanceOf(\Exception::class, $exception->getPrevious());
        static::assertSame(static::PREVIOUS_EXCEPTION_MESSAGE, $exception->getPrevious()->getMessage());
    }

    protected static function getExceptionMessage(): string
    {
        return static::EXCEPTION_MESSAGE;
    }

    protected static function assertDefaultPhpError(PhpObjectException $exception): void
    {
        static::assertNull($exception->getPhpError());
    }

    protected function createPhpError(): PhpError
    {
        return new PhpError(
            static::PHP_ERROR_NUMBER,
            static::PHP_ERROR_ERROR,
            static::PHP_ERROR_FILE,
            static::PHP_ERROR_LINE
        );
    }
}
