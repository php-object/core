<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\Directory\DirectoryNotFoundException;

use PhpObject\Core\{
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest
};

class GetFileMethodTest extends AbstractPhpObjectExceptionTest
{
    public function testValid(): void
    {
        try {
            throw new DirectoryNotFoundException('foo');
        } catch (DirectoryNotFoundException $exception) {
            static::assertSame(__FILE__, $exception->getFile());
        }
    }
}
