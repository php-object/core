<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\File\FileUtils;

use PhpObject\Core\{
    Exception\Directory\DirectoryNotFoundException,
    File\FileUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class WriteStringMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $filename = sys_get_temp_dir() . '/foo';
        $writtenBytes = $this->callPhpObjectMethod(
            function () use ($filename): int {
                return FileUtils::writeString($filename, 'foo');
            }
        );

        static::assertIsInt($writtenBytes);
        static::assertSame(3, $writtenBytes);
        static::assertSame(file_get_contents($filename), 'foo');
    }

    public function testDirectoryNotFound(): void
    {
        $directory = sys_get_temp_dir() . '/foo';
        $this->assertExceptionIsThrowned(
            function () use ($directory): int {
                return FileUtils::writeString($directory . '/bar', 'foo');
            },
            DirectoryNotFoundException::class,
            'Directory "' . $directory . '" not found.'
        );
    }

    public function testFlag(): void
    {
        $filename = sys_get_temp_dir() . '/foo';
        $writtenBytes = $this->callPhpObjectMethod(
            function () use ($filename): int {
                return FileUtils::writeString($filename, 'foo', LOCK_EX);
            }
        );

        static::assertIsInt($writtenBytes);
        static::assertSame(3, $writtenBytes);
        static::assertSame(file_get_contents($filename), 'foo');
    }

    public function testContext(): void
    {
        $filename = sys_get_temp_dir() . '/foo';
        $writtenBytes = $this->callPhpObjectMethod(
            function () use ($filename): int {
                return FileUtils::writeString($filename, 'foo', 0, stream_context_create());
            }
        );

        static::assertIsInt($writtenBytes);
        static::assertSame(3, $writtenBytes);
        static::assertSame(file_get_contents($filename), 'foo');
    }
}
