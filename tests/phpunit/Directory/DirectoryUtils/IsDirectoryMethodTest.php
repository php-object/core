<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class IsDirectoryMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $directory = __DIR__;

        $result = $this->callPhpObjectMethod(
            function () use ($directory): bool {
                return DirectoryUtils::isDirectory($directory);
            }
        );
        static::assertTrue($result, "Directory \"$directory\" does not exists but should exists.");
    }

    public function testDirectoryNotFound(): void
    {
        $directory = __DIR__;

        $result = $this->callPhpObjectMethod(
            function () use ($directory): bool {
                return DirectoryUtils::isDirectory($directory . '/foo');
            }
        );
        static::assertFalse($result, "Directory \"$directory\" exists but should notr exists.");
    }

    public function testFile(): void
    {
        $file = __FILE__;

        $result = $this->callPhpObjectMethod(
            function () use ($file): bool {
                return DirectoryUtils::isDirectory($file);
            }
        );
        static::assertFalse($result, "Path \"$file\" should be a file but is considered as directory.");
    }

    public function testSymbolicLink(): void
    {
        $symbolicLink = sys_get_temp_dir() . '/' . uniqid();
        symlink(__DIR__, $symbolicLink);

        $result = $this->callPhpObjectMethod(
            function () use ($symbolicLink): bool {
                return DirectoryUtils::isDirectory($symbolicLink);
            }
        );

        unlink($symbolicLink);

        static::assertTrue($result, "Symbolic link \"$symbolicLink\" should be considered as a directory.");
    }

    public function testSymbolicLinkNotFound(): void
    {
        $symbolicLink = sys_get_temp_dir() . '/' . uniqid();
        symlink(__DIR__ . '/foo', $symbolicLink);

        $result = $this->callPhpObjectMethod(
            function () use ($symbolicLink): bool {
                return DirectoryUtils::isDirectory($symbolicLink);
            }
        );

        unlink($symbolicLink);

        static::assertFalse($result, "Symbolic link \"$symbolicLink\" should not be considered as a directory.");
    }
}
