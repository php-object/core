<?php

declare(strict_types=1);

namespace PhpObject\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\{
    Directory\DirectoryUtils,
    ErrorHandler\PhpObjectErrorHandlerUtils,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractPhpObjectTestCase
};

final class ChangeWorkingDirectoryMethodTest extends AbstractPhpObjectTestCase
{
    public function testExistingDirectory(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        $this->enableTestErrorHandler();

        $workingDirectory = getcwd();
        if (is_string($workingDirectory) === false) {
            static::fail('Could not get working directory.');
        }

        $directory = sys_get_temp_dir();
        DirectoryUtils::changeWorkingDirectory($directory);
        static::assertEquals($directory, getcwd());
        static::assertNoPhpError($this->getLastPhpError());

        DirectoryUtils::changeWorkingDirectory($workingDirectory);
    }

    public function testDirectoryNotFound(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(true);
        $this->enableTestErrorHandler();

        $directory = sys_get_temp_dir() . '/foo';

        try {
            DirectoryUtils::changeWorkingDirectory($directory);
        } catch (DirectoryNotFoundException $exception) {
            static::assertEquals("Directory \"$directory\" not found.", $exception->getMessage());
            static::assertEquals(0, $exception->getCode());
        }

        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testDirectoryNotFoundPhpError(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        $this->enableTestErrorHandler();

        $directory = sys_get_temp_dir() . '/foo';

        try {
            DirectoryUtils::changeWorkingDirectory($directory);
        } catch (DirectoryNotFoundException $exception) {
            static::assertEquals("Directory \"$directory\" not found.", $exception->getMessage());
            static::assertEquals(0, $exception->getCode());
        }

        static::assertLastPhpError(
            $this->lastError,
            E_WARNING,
            'chdir(): No such file or directory (errno 2)',
            '/app/src/Directory/DirectoryUtils.php',
            20
        );
    }
}
