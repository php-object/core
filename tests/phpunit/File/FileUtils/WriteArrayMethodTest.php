<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\File\FileUtils;

use PhpObject\Core\{
    Exception\Directory\DirectoryNotFoundException,
    File\FileUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class WriteArrayMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $filename = sys_get_temp_dir() . '/foo';
        $writtenBytes = $this->callPhpObjectMethod(
            function () use ($filename): int {
                return FileUtils::writeArray($filename, ['foo', 'bar']);
            }
        );

        static::assertIsInt($writtenBytes);
        static::assertSame(6, $writtenBytes);
        static::assertSame(file_get_contents($filename), 'foobar');
    }

    public function testDirectoryNotFound(): void
    {
        $directory = sys_get_temp_dir() . '/foo';
        $this->assertExceptionIsThrowned(
            function () use ($directory): int {
                return FileUtils::writeArray($directory . '/bar', ['foo', 'bar']);
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
                return FileUtils::writeArray($filename, ['foo', 'bar'], LOCK_EX);
            }
        );

        static::assertIsInt($writtenBytes);
        static::assertSame(6, $writtenBytes);
        static::assertSame(file_get_contents($filename), 'foobar');
    }

    public function testContext(): void
    {
        $filename = sys_get_temp_dir() . '/foo';
        $writtenBytes = $this->callPhpObjectMethod(
            function () use ($filename): int {
                return FileUtils::writeArray($filename, ['foo', 'bar'], 0, stream_context_create());
            }
        );

        static::assertIsInt($writtenBytes);
        static::assertSame(6, $writtenBytes);
        static::assertSame(file_get_contents($filename), 'foobar');
    }
}
