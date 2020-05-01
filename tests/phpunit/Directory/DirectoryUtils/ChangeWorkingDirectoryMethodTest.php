<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractTestCase
};

final class ChangeWorkingDirectoryMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $workingDirectory = getcwd();
        if (is_string($workingDirectory) === false) {
            static::fail('Could not get working directory.');
        }

        $directory = sys_get_temp_dir();

        $this->enableTestErrorHandler();
        DirectoryUtils::changeWorkingDirectory($directory);
        $this->disableTestErrorHandler();

        static::assertEquals($directory, getcwd());
        static::assertNoPhpError($this->getLastPhpError());

        static::assertTrue(chdir($workingDirectory));
    }

    public function testDirectoryNotFound(): void
    {
        $directory = sys_get_temp_dir() . '/foo';

        $exceptionThrowned = false;
        $this->enableTestErrorHandler();
        try {
            DirectoryUtils::changeWorkingDirectory($directory);
        } catch (DirectoryNotFoundException $exception) {
            $this->disableTestErrorHandler();

            static::assertPhpErrorException(
                $exception,
                "Directory \"$directory\" not found.",
                0,
                E_WARNING,
                'chdir(): No such file or directory (errno 2)',
                DirectoryUtils::class,
                19
            );
            static::assertNoPhpError($this->getLastPhpError());

            $exceptionThrowned = true;
        }

        $this->assertExceptionThrowned($exceptionThrowned, DirectoryNotFoundException::class);
    }
}
