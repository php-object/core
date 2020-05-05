<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\File\FileNotFoundException;

use PhpObject\Core\{
    Exception\File\FileNotFoundException,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest
};

class CreateDefaultMessageMethodTest extends AbstractPhpObjectExceptionTest
{
    public function testCreateDefaultMessage(): void
    {
        static::assertSame(
            FileNotFoundException::createDefaultMessage('foo'),
            'File "foo" not found.'
        );
    }
}
