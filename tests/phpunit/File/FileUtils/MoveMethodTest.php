<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\File\FileUtils;

use PhpObject\Core\{
    Exception\Directory\DirectoryNotFoundException,
    Exception\File\FileNotFoundException,
    File\FileUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class MoveMethodTest extends AbstractTestCase
{
    public function testSourceExists(): void
    {
        $source = $this->getTemporaryPath() . '/foo.txt';
        mkdir(dirname($source));
        touch($source);
        $destination = $this->getTemporaryPath() . '/foo.txt';
        mkdir(dirname($destination));

        $this->callPhpObjectMethod(
            function () use ($source, $destination): void {
                FileUtils::move($source, $destination);
            }
        );
        static::assertTrue(is_file($destination));
    }

    public function testDestinationExists(): void
    {
        $source = $this->getTemporaryPath() . '/foo.txt';
        mkdir(dirname($source));
        touch($source);
        $destination = $this->getTemporaryPath() . '/foo.txt';
        mkdir(dirname($destination));

        // PHP documentation say an E_WARNING should be triggered in case of $destination already exists,
        // but that's not the case.
        $this->callPhpObjectMethod(
            function () use ($source, $destination): void {
                FileUtils::move($source, $destination);
            }
        );
        static::assertTrue(is_file($destination));
    }

    public function testSourceNotFound(): void
    {
        $source = $this->getTemporaryPath() . '/foo.txt';
        $destination = $this->getTemporaryPath() . '/foo.txt';

        $this->assertExceptionIsThrowned(
            function () use ($source, $destination): void {
                FileUtils::move($source, $destination);
            },
            FileNotFoundException::class,
            "Source file \"$source\" not found."
        );
    }

    public function testDestinationDirectoryNotFound(): void
    {
        $source = $this->getTemporaryPath() . '/foo.txt';
        mkdir(dirname($source));
        touch($source);
        $destination = $this->getTemporaryPath() . '/foo/bar.txt';
        $destinationParent = dirname($destination);

        $this->assertExceptionIsThrowned(
            function () use ($source, $destination): void {
                FileUtils::move($source, $destination);
            },
            DirectoryNotFoundException::class,
            "Destination directory \"$destinationParent\" not found."
        );
    }

    public function testSymbolicLink(): void
    {
        $source = $this->getTemporaryPath() . '/foo.txt';
        mkdir(dirname($source));
        touch($source);
        $symbolicLink = $this->getTemporaryPath() . '/foo.txt';
        mkdir(dirname($symbolicLink));
        symlink($source, $symbolicLink);
        $destination = $this->getTemporaryPath() . '/foo.txt';
        mkdir(dirname($destination));

        $this->callPhpObjectMethod(
            function () use ($symbolicLink, $destination): void {
                FileUtils::move($symbolicLink, $destination);
            }
        );
        static::assertTrue(is_file($destination));
    }

    public function testSymbolicLinkNotFound(): void
    {
        $source = $this->getTemporaryPath() . '/foo.txt';
        $destination = $this->getTemporaryPath() . '/foo.txt';
        mkdir(dirname($destination));
        symlink($source, $destination);

        $this->assertExceptionIsThrowned(
            function () use ($source, $destination): void {
                FileUtils::move($source, $destination);
            },
            FileNotFoundException::class,
            "Source file \"$source\" not found."
        );
    }
}
