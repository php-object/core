<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractTestCase
};

final class DeleteMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $directory = $this->getTemporaryDirectory();
        mkdir($directory);

        $this->callPhpObjectMethod(
            function () use ($directory): void {
                DirectoryUtils::delete($directory);
            }
        );
        static::assertFalse(is_dir($directory));
    }

    public function testDirectoryNotFound(): void
    {
        $directory = $this->getTemporaryDirectory();

        $this->assertExceptionIsThrowned(
            function () use ($directory): void {
                DirectoryUtils::delete($directory);
            },
            DirectoryNotFoundException::class,
            "Directory \"$directory\" not found."
        );
    }

    public function testFile(): void
    {
        $directory = $this->getTemporaryDirectory();
        mkdir($directory);
        $file = "$directory/foo";
        touch($file);

        $this->assertExceptionIsThrowned(
            function () use ($file): void {
                DirectoryUtils::delete($file);
            },
            DirectoryNotFoundException::class,
            "Directory \"$file\" not found."
        );
    }

    public function testSymbolicLink(): void
    {
        $sourceDirectory = $this->getTemporaryDirectory();
        mkdir($sourceDirectory);
        $directory = $this->getTemporaryDirectory();
        symlink($sourceDirectory, $directory);

        $this->assertExceptionIsThrowned(
            function () use ($directory): void {
                DirectoryUtils::delete($directory);
            },
            DirectoryNotFoundException::class,
            "Directory \"$directory\" is a symbolic link, "
                . 'use PhpObject\Core\Link\SymbolicLinkUtils::delete() to delete it.'
        );
    }

    public function testSymbolicLinkNotFound(): void
    {
        $directory = $this->getTemporaryDirectory();
        $destination = $this->getTemporaryDirectory();
        symlink($directory, $destination);

        $this->assertExceptionIsThrowned(
            function () use ($directory): void {
                DirectoryUtils::delete($directory);
            },
            DirectoryNotFoundException::class,
            "Directory \"$directory\" not found."
        );
    }
}
