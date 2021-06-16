<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Exception\Directory\DirectoryNotFoundException;

use PhpObject\Core\{
    ErrorHandler\PhpError,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\Exception\AbstractPhpObjectExceptionTest
};

class GetPreviousMethodTest extends AbstractPhpObjectExceptionTest
{
    public function testValid(): void
    {
        $previous = new DirectoryNotFoundException('bar');

        try {
            throw new DirectoryNotFoundException(
                'foo',
                new PhpError(E_NOTICE, 'bar'),
                0,
                $previous
            );
        } catch (DirectoryNotFoundException $exception) {
            static::assertSame(spl_object_hash($previous), spl_object_hash($exception->getPrevious()));
        }
    }
}
