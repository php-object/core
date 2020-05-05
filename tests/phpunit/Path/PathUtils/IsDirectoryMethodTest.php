<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Path\PathUtils;

use PhpObject\Core\{
    Path\PathUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class IsDirectoryMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $path = __DIR__;

        $result = $this->callPhpObjectMethod(
            function () use ($path): bool {
                return PathUtils::isDirectory($path);
            }
        );
        static::assertTrue($result, "Directory \"$path\" does not exists but should exists.");
    }

    public function testDirectoryNotFound(): void
    {
        $path = __DIR__ . '/foo';

        $result = $this->callPhpObjectMethod(
            function () use ($path): bool {
                return PathUtils::isDirectory($path);
            }
        );
        static::assertFalse($result, "Directory \"$path\" exists but should not exists.");
    }

    public function testFile(): void
    {
        $path = __FILE__;

        $result = $this->callPhpObjectMethod(
            function () use ($path): bool {
                return PathUtils::isDirectory($path);
            }
        );
        static::assertFalse($result, "Path \"$path\" should be a file but is considered as a directory.");
    }

    public function testSymbolicLink(): void
    {
        $path = sys_get_temp_dir() . '/' . uniqid();
        symlink(__DIR__, $path);

        $result = $this->callPhpObjectMethod(
            function () use ($path): bool {
                return PathUtils::isDirectory($path);
            }
        );

        unlink($path);

        static::assertTrue($result, "Symbolic link \"$path\" should be considered as a directory.");
    }

    public function testSymbolicLinkNotFound(): void
    {
        $path = sys_get_temp_dir() . '/' . uniqid();
        symlink(__DIR__ . '/foo', $path);

        $result = $this->callPhpObjectMethod(
            function () use ($path): bool {
                return PathUtils::isDirectory($path);
            }
        );

        unlink($path);

        static::assertFalse($result, "Symbolic link \"$path\" should not be considered as a directory.");
    }
}
