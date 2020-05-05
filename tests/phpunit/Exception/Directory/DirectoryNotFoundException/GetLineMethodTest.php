<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\Directory\DirectoryNotFoundException;

use PhpObject\Core\{
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest
};

class GetLineMethodTest extends AbstractPhpObjectExceptionTest
{
    public function testValid(): void
    {
        try {
            throw new DirectoryNotFoundException('foo');
        } catch (DirectoryNotFoundException $exception) {
            static::assertSame(__LINE__ - 2, $exception->getLine());
        }
    }
}
