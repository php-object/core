<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\File\FileUtils;

use PhpObject\Core\{
    Exception\File\FileNotFoundException,
    File\FileUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class ReadMethodTest extends AbstractTestCase
{
    public function testExistingFilename(): void
    {
        $content = $this->callPhpObjectMethod(
            function (): string {
                return FileUtils::read(__FILE__);
            }
        );

        static::assertIsString($content);
        static::assertSame($this->getCurrentFileLength(), strlen($content));
    }

    public function testUseIncludePath(): void
    {
        $content = $this->callPhpObjectMethod(
            function (): string {
                return FileUtils::read(__FILE__, FileUtils::USE_INCLUDE_PATH);
            }
        );

        static::assertIsString($content);
        static::assertSame($this->getCurrentFileLength(), strlen($content));
    }

    public function testWithContext(): void
    {
        $content = $this->callPhpObjectMethod(
            function (): string {
                return FileUtils::read(__FILE__, FileUtils::DO_NOT_USE_INCLUDE_PATH, stream_context_create());
            }
        );

        static::assertIsString($content);
        static::assertSame($this->getCurrentFileLength(), strlen($content));
    }

    public function testOffset(): void
    {
        $content = $this->callPhpObjectMethod(
            function (): string {
                return FileUtils::read(__FILE__, FileUtils::DO_NOT_USE_INCLUDE_PATH, null, 1);
            }
        );

        static::assertIsString($content);
        static::assertSame($this->getCurrentFileLength() - 1, strlen($content));
    }

    public function testLength(): void
    {
        $content = $this->callPhpObjectMethod(
            function (): string {
                return FileUtils::read(__FILE__, FileUtils::DO_NOT_USE_INCLUDE_PATH, null, 0, 10);
            }
        );

        static::assertIsString($content);
        static::assertSame(10, strlen($content));
    }

    public function testFilenameNotFoud(): void
    {
        $filename = __FILE__ . 'NotFound';

        $this->assertExceptionIsThrowned(
            function () use ($filename): void {
                FileUtils::read($filename);
            },
            FileNotFoundException::class,
            'File "' . $filename . '" not found.'
        );
    }

    private function getCurrentFileLength(): int
    {
        $content = file_get_contents(__FILE__);
        if (is_string($content) === false) {
            throw new \Exception('Unable to get current file content.');
        }

        return strlen($content);
    }
}
