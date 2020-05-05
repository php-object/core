<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\Directory\DirectoryNotFoundException;

use PhpObject\Core\{
    ErrorHandler\PhpError,
    Exception\Directory\DirectoryNotFoundException,
    Exception\PhpObjectExceptionUtils,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest
};

class ToStringMethodTest extends AbstractPhpObjectExceptionTest
{
    public function testCall(): void
    {
        static::assertSame(
            'foo',
            (new DirectoryNotFoundException('foo'))->__toString()
        );
    }

    public function testCast(): void
    {
        static::assertSame(
            'foo',
            (string) (new DirectoryNotFoundException('foo'))
        );
    }

    public function testAddPhpErrorCall(): void
    {
        PhpObjectExceptionUtils::setAddPhpErrorToMessage(true);
        $message = (new DirectoryNotFoundException('foo', new PhpError(E_NOTICE, 'bar')))->__toString();
        PhpObjectExceptionUtils::setAddPhpErrorToMessage(false);

        static::assertSame('foo PHP error: E_NOTICE (8) bar.', $message);
    }

    public function testAddPhpErrorCast(): void
    {
        PhpObjectExceptionUtils::setAddPhpErrorToMessage(true);
        $message = (string) (new DirectoryNotFoundException('foo', new PhpError(E_NOTICE, 'bar')));
        PhpObjectExceptionUtils::setAddPhpErrorToMessage(false);

        static::assertSame('foo PHP error: E_NOTICE (8) bar.', $message);
    }
}
