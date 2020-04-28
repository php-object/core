<?php

declare(strict_types=1);

namespace phpunit\Directory\DirectoryUtils;

use PhpObject\{
    Directory\DirectoryUtils,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractPhpObjectTestCase
};

final class ChangeCurrentDirectoryMethodTest extends AbstractPhpObjectTestCase
{
    public function testExistingDirectory(): void
    {
        DirectoryUtils::changeCurrentDirectory(sys_get_temp_dir());
        $this->addToAssertionCount(1);
        DirectoryUtils::changeCurrentDirectory(__DIR__ . '/../../..');
    }

    public function testDirectoryNotFound(): void
    {
        $directory = sys_get_temp_dir() . '/foo';

        static::expectException(DirectoryNotFoundException::class);
        static::expectExceptionMessage("Directory \"$directory\" not found.");
        static::expectExceptionCode(0);

        DirectoryUtils::changeCurrentDirectory($directory);
    }

    public function testPhpError(): void
    {
        $this->enableTestErrorHandler();

        try {
            DirectoryUtils::changeCurrentDirectory('/fifou');
        } catch (DirectoryNotFoundException $exception) {
            // Nothing to do there
        }

        $this->disableTestErrorHandler();

        static::assertLastPhpError(
            $this->lastError,
            E_WARNING,
            'chdir(): No such file or directory (errno 2)',
            '/app/src/Directory/DirectoryUtils.php',
            19
        );

        $this->resetLastError();
    }
}
