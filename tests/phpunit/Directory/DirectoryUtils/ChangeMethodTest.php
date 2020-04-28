<?php

declare(strict_types=1);

namespace phpunit\Directory\DirectoryUtils;

use PhpObject\{
    Directory\DirectoryUtils,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractPhpObjectTestCase
};

final class ChangeMethodTest extends AbstractPhpObjectTestCase
{
    public function testExistingDirectory(): void
    {
        DirectoryUtils::change(sys_get_temp_dir());

        static::addToAssertionCount(1);
    }

    public function testDirectoryNotFound(): void
    {
        $directory = sys_get_temp_dir() . '/foo';

        static::expectException(DirectoryNotFoundException::class);
        static::expectExceptionMessage("Directory \"$directory\" not found.");
        static::expectExceptionCode(0);

        DirectoryUtils::change($directory);
    }

    public function testPhpError(): void
    {
        $this->enableTestErrorHandler();

        try {
            DirectoryUtils::change('/fifou');
        } catch (DirectoryNotFoundException $exception) {
            // Nothing to do there
        }

        $this->disableTestErrorHandler();

        static::assertLastError(
            $this->lastError,
            E_WARNING,
            'chdir(): No such file or directory (errno 2)',
            '/app/src/Directory/DirectoryUtils.php',
            19
        );

        $this->resetLastError();
    }
}
