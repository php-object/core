<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Path\PathUtils;

use PhpObject\Core\{
    Exception\Directory\DirectoryNotFoundException,
    Path\PathUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class AssertIsDirectoryMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $directory = __DIR__;

        $this->callPhpObjectMethod(
            function () use ($directory): void {
                PathUtils::assertIsDirectory($directory);
            }
        );
        $this->addToAssertionCount(1);
    }

    public function testErrorMessage(): void
    {
        $this->assertIsNotDirectory(__DIR__ . '/foo', 'Foo');
    }

    public function testDirectoryNotFound(): void
    {
        $this->assertIsNotDirectory(__DIR__ . '/foo');
    }

    public function testFile(): void
    {
        $this->assertIsNotDirectory(__FILE__);
    }

    public function testSymbolicLink(): void
    {
        $symbolicLink = sys_get_temp_dir() . '/' . uniqid();
        symlink(__DIR__, $symbolicLink);

        $this->callPhpObjectMethod(
            function () use ($symbolicLink): void {
                PathUtils::assertIsDirectory($symbolicLink);
            }
        );
        $this->addToAssertionCount(1);
    }

    public function testSymbolicLinkNotFound(): void
    {
        $symbolicLink = sys_get_temp_dir() . '/' . uniqid();
        symlink(__DIR__ . '/foo', $symbolicLink);

        $this->assertIsNotDirectory($symbolicLink);
    }

    private function assertIsNotDirectory(string $path, string $errorMessage = null): self
    {
        $this->assertExceptionIsThrowned(
            function () use ($path, $errorMessage): void {
                PathUtils::assertIsDirectory($path, $errorMessage);
            },
            DirectoryNotFoundException::class,
            $errorMessage ?? "Directory \"$path\" not found."
        );

        return $this;
    }
}
