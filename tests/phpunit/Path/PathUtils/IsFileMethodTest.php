<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Path\PathUtils;

use PhpObject\Core\{
    Path\PathUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class IsFileMethodTest extends AbstractTestCase
{
    public function testExistingFile(): void
    {
        $path = __FILE__;

        $result = $this->callPhpObjectMethod(
            function () use ($path): bool {
                return PathUtils::isFile($path);
            }
        );
        static::assertTrue($result, "File \"$path\" does not exists but should exists.");
    }

    public function testFileNotFound(): void
    {
        $path = __FILE__ . 'foo';

        $result = $this->callPhpObjectMethod(
            function () use ($path): bool {
                return PathUtils::isFile($path);
            }
        );
        static::assertFalse($result, "File \"$path\" exists but should not exists.");
    }

    public function testDirectory(): void
    {
        $path = __DIR__;

        $result = $this->callPhpObjectMethod(
            function () use ($path): bool {
                return PathUtils::isFile($path);
            }
        );
        static::assertFalse($result, "Path \"$path\" should be a directory but is considered as a file.");
    }

    public function testSymbolicLink(): void
    {
        $path = sys_get_temp_dir() . '/' . uniqid();
        symlink(__FILE__, $path);

        $result = $this->callPhpObjectMethod(
            function () use ($path): bool {
                return PathUtils::isFile($path);
            }
        );

        unlink($path);

        static::assertTrue($result, "Symbolic link \"$path\" should be considered as a file.");
    }

    public function testSymbolicLinkNotFound(): void
    {
        $path = sys_get_temp_dir() . '/' . uniqid();
        symlink(__DIR__ . '/foo', $path);

        $result = $this->callPhpObjectMethod(
            function () use ($path): bool {
                return PathUtils::isFile($path);
            }
        );

        unlink($path);

        static::assertFalse($result, "Symbolic link \"$path\" should not be considered as a file.");
    }
}
