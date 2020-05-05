<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\Directory\DirectoryNotFoundException;

use PhpObject\Core\{
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest
};

class CreateDefaultMessageMethodTest extends AbstractPhpObjectExceptionTest
{
    public function testCreateDefaultMessage(): void
    {
        static::assertSame(
            'Directory "foo" not found.',
            DirectoryNotFoundException::createDefaultMessage('foo')
        );
    }
}
