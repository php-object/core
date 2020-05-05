<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\File\FileNotFoundException;

use PhpObject\Core\{
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest
};

class CreateDefaultMessageMethodTest extends AbstractPhpObjectExceptionTest
{
    public function testCreateDefaultMessage(): void
    {
        static::assertSame(
            DirectoryNotFoundException::createDefaultMessage('foo'),
            'Directory "foo" not found.'
        );
    }
}
