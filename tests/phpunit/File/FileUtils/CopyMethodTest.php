<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\File\FileUtils;

use PhpObject\Core\{
    Exception\File\FileNotFoundException,
    Exception\PhpObjectException,
    File\FileUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class CopyMethodTest extends AbstractTestCase
{
    public function testExistingSource(): void
    {
        $source = __FILE__;
        $destination = $this->getTemporaryPath();

        $this->callPhpObjectMethod(
            function () use ($source, $destination): void {
                FileUtils::copy($source, $destination);
            }
        );
        static::assertTrue(is_file($destination));
    }

    public function testSourceNotFound(): void
    {
        PhpObjectException::setAddPhpErrorToMessage(true);

        $source = __FILE__ . '/foo';
        $destination = $this->getTemporaryPath();

        $this->assertExceptionIsThrowned(
            function () use ($source, $destination): void {
                FileUtils::copy($source, $destination);
            },
            FileNotFoundException::class,
            "File \"$source\" not found."
        );
        static::assertFalse(is_file($destination));
    }

    public function testExistingDestination(): void
    {
        $source = __FILE__;
        $destination = $this->getTemporaryPath();

        touch($destination);

        static::assertSame('', file_get_contents($destination));

        $this->callPhpObjectMethod(
            function () use ($source, $destination): void {
                FileUtils::copy($source, $destination);
            }
        );
        static::assertTrue(is_file($destination));
        static::assertSame(file_get_contents($source), file_get_contents($destination));
    }

    public function testSourceIsDirectory(): void
    {
        $source = __DIR__;
        $destination = $this->getTemporaryPath();

        $this->assertExceptionIsThrowned(
            function () use ($source, $destination): void {
                FileUtils::copy($source, $destination);
            },
            FileNotFoundException::class,
            "File \"$source\" not found."
        );
    }
}
