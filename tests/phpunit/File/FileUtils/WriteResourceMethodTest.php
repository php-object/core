<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\File\FileUtils;

use PhpObject\Core\{
    Exception\Directory\DirectoryNotFoundException,
    File\FileUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class WriteResourceMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $filename = sys_get_temp_dir() . '/foo';
        $writtenBytes = $this->callPhpObjectMethod(
            function () use ($filename): int {
                return FileUtils::writeResource($filename, static::createResource());
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
                return FileUtils::writeResource($directory . '/bar', static::createResource());
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
                return FileUtils::writeResource($filename, static::createResource(), LOCK_EX);
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
                return FileUtils::writeResource($filename, static::createResource(), 0, stream_context_create());
            }
        );

        static::assertIsInt($writtenBytes);
        static::assertSame(3, $writtenBytes);
        static::assertSame(file_get_contents($filename), 'foo');
    }

    /** @return resource */
    private static function createResource()
    {
        $return = fopen('php://memory', 'w');
        if (is_resource($return) === false) {
            throw new \Exception('Failed to create resource (should not happen).');
        }
        fwrite($return, 'foo');
        fseek($return, 0);

        return $return;
    }
}
