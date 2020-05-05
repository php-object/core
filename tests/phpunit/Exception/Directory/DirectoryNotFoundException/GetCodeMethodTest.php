<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\Directory\DirectoryNotFoundException;

use PhpObject\Core\{
    ErrorHandler\PhpError,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest
};

class GetCodeMethodTest extends AbstractPhpObjectExceptionTest
{
    public function testValid(): void
    {
        static::assertSame(
            42,
            (new DirectoryNotFoundException('foo', new PhpError(42, 'bar'), 42))->getCode()
        );
    }
}
