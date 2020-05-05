<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\Directory\DirectoryNotFoundException;

use PhpObject\Core\{
    ErrorHandler\PhpError,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest
};

class GetTraceAsStringMethodTest extends AbstractPhpObjectExceptionTest
{
    public function testValid(): void
    {
        static::assertIsString(
            (new DirectoryNotFoundException('foo'))->getTraceAsString()
        );
    }
}
