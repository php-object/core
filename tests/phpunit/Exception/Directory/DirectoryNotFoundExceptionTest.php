<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\Directory;

use PhpObject\Core\{
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest
};

class DirectoryNotFoundExceptionTest extends AbstractPhpObjectExceptionTest
{
    public function testCreateDefaultMessage(): void
    {
        static::assertSame(
            DirectoryNotFoundException::createDefaultMessage('foo'),
            'Directory "foo" not found.'
        );
    }

    public function testDefaultValues(): void
    {
        static::assertExceptionDefaultValues(new DirectoryNotFoundException(static::EXCEPTION_MESSAGE));
    }

    public function testPhpError(): void
    {
        $exception = new DirectoryNotFoundException(static::EXCEPTION_MESSAGE, $this->createPhpError());

        static::assertExceptionPhpError($exception);
    }

    public function testCode(): void
    {
        static::assertExceptionCode(
            new DirectoryNotFoundException(static::EXCEPTION_MESSAGE, null, static::EXCEPTION_CODE)
        );
    }

    public function testPrevious(): void
    {
        static::assertExceptionPrevious(
            new DirectoryNotFoundException(
                static::EXCEPTION_MESSAGE,
                null,
                0,
                new \Exception(static::PREVIOUS_EXCEPTION_MESSAGE)
            )
        );
    }
}
