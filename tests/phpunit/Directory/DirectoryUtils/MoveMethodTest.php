<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractTestCase
};

final class MoveMethodTest extends AbstractTestCase
{
    public function testSourceExists(): void
    {
        $source = $this->getTemporaryPath();
        mkdir($source);
        $destination = $this->getTemporaryPath();

        $this->callPhpObjectMethod(
            function () use ($source, $destination): void {
                DirectoryUtils::move($source, $destination);
            }
        );
        static::assertTrue(is_dir($destination));
    }

    public function testDestinationExists(): void
    {
        $source = $this->getTemporaryPath();
        mkdir($source);
        $destination = $this->getTemporaryPath();
        mkdir($destination);

        // PHP documentation say an E_WARNING should be triggered in case of $destination already exists,
        // but that's not the case.
        $this->callPhpObjectMethod(
            function () use ($source, $destination): void {
                DirectoryUtils::move($source, $destination);
            }
        );
        static::assertTrue(is_dir($destination));
    }

    public function testSourceNotFound(): void
    {
        $source = $this->getTemporaryPath();
        $destination = $this->getTemporaryPath();

        $this->assertExceptionIsThrowned(
            function () use ($source, $destination): void {
                DirectoryUtils::move($source, $destination);
            },
            DirectoryNotFoundException::class,
            "Source directory \"$source\" not found."
        );
    }

    public function testDestinationParentNotFound(): void
    {
        $source = $this->getTemporaryPath();
        mkdir($source);
        $destination = $this->getTemporaryPath() . '/foo/bar/baz';
        $destinationParent = dirname($destination);

        $this->assertExceptionIsThrowned(
            function () use ($source, $destination): void {
                DirectoryUtils::move($source, $destination);
            },
            DirectoryNotFoundException::class,
            "Destination parent directory \"$destinationParent\" not found."
        );
    }

    public function testFile(): void
    {
        $sourceDirectory = $this->getTemporaryPath();
        mkdir($sourceDirectory);
        $source = "$sourceDirectory/foo";
        touch($source);
        $destination = $this->getTemporaryPath();

        $this->assertExceptionIsThrowned(
            function () use ($source, $destination): void {
                DirectoryUtils::move($source, $destination);
            },
            DirectoryNotFoundException::class,
            "Source directory \"$source\" not found."
        );
    }

    public function testSymbolicLink(): void
    {
        $sourceDirectory = $this->getTemporaryPath();
        mkdir($sourceDirectory);
        $symbolicLink = $this->getTemporaryPath();
        symlink($sourceDirectory, $symbolicLink);
        $destination = $this->getTemporaryPath();

        $this->callPhpObjectMethod(
            function () use ($symbolicLink, $destination): void {
                DirectoryUtils::move($symbolicLink, $destination);
            }
        );
        static::assertTrue(is_dir($destination));
    }

    public function testSymbolicLinkNotFound(): void
    {
        $source = $this->getTemporaryPath();
        $destination = $this->getTemporaryPath();
        symlink($source, $destination);

        $this->assertExceptionIsThrowned(
            function () use ($source, $destination): void {
                DirectoryUtils::move($source, $destination);
            },
            DirectoryNotFoundException::class,
            "Source directory \"$source\" not found."
        );
    }
}
