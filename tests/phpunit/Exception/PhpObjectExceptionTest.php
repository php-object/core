<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception;

use PhpObject\Core\Exception\PhpObjectException;

class PhpObjectExceptionTest extends AbstractPhpObjectExceptionTest
{
    public function testDefaultValues(): void
    {
        static::assertExceptionDefaultValues(new PhpObjectException(static::EXCEPTION_MESSAGE));
    }

    public function testPhpError(): void
    {
        $exception = new PhpObjectException(static::EXCEPTION_MESSAGE, $this->createPhpError());

        static::assertExceptionPhpError($exception);
    }

    public function testCode(): void
    {
        static::assertExceptionCode(
            new PhpObjectException(static::EXCEPTION_MESSAGE, null, static::EXCEPTION_CODE)
        );
    }

    public function testPrevious(): void
    {
        static::assertExceptionPrevious(
            new PhpObjectException(
                static::EXCEPTION_MESSAGE,
                null,
                0,
                new \Exception(static::PREVIOUS_EXCEPTION_MESSAGE)
            )
        );
    }
}
