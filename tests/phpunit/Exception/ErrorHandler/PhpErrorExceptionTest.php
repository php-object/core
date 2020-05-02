<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\ErrorHandler;

use PhpObject\Core\{
    ErrorHandler\PhpError,
    Exception\ErrorHandler\PhpErrorException,
    Exception\PhpObjectException,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest
};

class PhpErrorExceptionTest extends AbstractPhpObjectExceptionTest
{
    protected static function getExceptionMessage(): string
    {
        return 'A PHP error has been triggered.';
    }

    protected static function assertDefaultPhpError(PhpObjectException $exception): void
    {
        static::assertInstanceOf(PhpError::class, $exception->getPhpError());
    }

    public function testDefaultValues(): void
    {
        static::assertExceptionDefaultValues(
            new PhpErrorException($this->createPhpError())
        );
    }

    public function testCode(): void
    {
        static::assertExceptionCode(
            new PhpErrorException(
                $this->createPhpError(),
                static::EXCEPTION_CODE
            )
        );
    }

    public function testPrevious(): void
    {
        static::assertExceptionPrevious(
            new PhpErrorException(
                $this->createPhpError(),
                0,
                new \Exception(static::PREVIOUS_EXCEPTION_MESSAGE)
            )
        );
    }
}
