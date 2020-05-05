<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\Directory\DirectoryNotFoundException;

use PhpObject\Core\{
    ErrorHandler\PhpError,
    Exception\Directory\DirectoryNotFoundException,
    Exception\PhpObjectExceptionUtils,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest
};

class GetMessageMethodTest extends AbstractPhpObjectExceptionTest
{
    public function testValid(): void
    {
        static::assertSame(
            'foo',
            (new DirectoryNotFoundException('foo'))->getMessage()
        );
    }

    public function testAddPhpError(): void
    {
        PhpObjectExceptionUtils::setAddPhpErrorToMessage(true);
        $message = (new DirectoryNotFoundException('foo', new PhpError(E_NOTICE, 'bar')))->getMessage();
        PhpObjectExceptionUtils::setAddPhpErrorToMessage(false);

        static::assertSame('foo PHP error: E_NOTICE (8) bar.', $message);
    }
}
