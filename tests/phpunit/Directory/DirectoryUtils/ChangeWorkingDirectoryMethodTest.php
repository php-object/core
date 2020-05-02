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

        $this->callPhpObjectMethod(
            function () use ($directory): void {
                DirectoryUtils::changeWorkingDirectory($directory);
            }
        );
        static::assertEquals($directory, getcwd());
        static::assertTrue(chdir($workingDirectory));
    }

    public function testDirectoryNotFound(): void
    {
        $directory = sys_get_temp_dir() . '/foo';

        $this->assertExceptionIsThrowned(
            function () use ($directory): void {
                DirectoryUtils::changeWorkingDirectory($directory);
            },
            DirectoryNotFoundException::class,
            "Directory \"$directory\" not found."
        );
    }
}
