<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\Variable;

use PhpObject\Core\{
    Exception\Variable\ResourceExpectedException,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest,
    Variable\VariableUtils
};

class ResourceExpectedExceptionTest extends AbstractPhpObjectExceptionTest
{
    public function testCreateDefaultMessage(): void
    {
        static::assertSame(
            ResourceExpectedException::createDefaultMessage('foo', 42),
            'Variable "$foo" should be a valid resource but is of type ' . VariableUtils::getType(42) . '.'
        );
    }

    public function testDefaultValues(): void
    {
        static::assertExceptionDefaultValues(new ResourceExpectedException(static::EXCEPTION_MESSAGE));
    }

    public function testPhpError(): void
    {
        $exception = new ResourceExpectedException(static::EXCEPTION_MESSAGE, $this->createPhpError());

        static::assertExceptionPhpError($exception);
    }

    public function testCode(): void
    {
        static::assertExceptionCode(
            new ResourceExpectedException(static::EXCEPTION_MESSAGE, null, static::EXCEPTION_CODE)
        );
    }

    public function testPrevious(): void
    {
        static::assertExceptionPrevious(
            new ResourceExpectedException(
                static::EXCEPTION_MESSAGE,
                null,
                0,
                new \Exception(static::PREVIOUS_EXCEPTION_MESSAGE)
            )
        );
    }
}
