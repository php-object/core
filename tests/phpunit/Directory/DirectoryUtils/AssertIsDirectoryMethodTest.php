<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractTestCase
};

final class AssertIsDirectoryMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $directory = __DIR__;

        $this->callPhpObjectMethod(
            function () use ($directory): void {
                DirectoryUtils::assertIsDirectory($directory);
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

        $this->assertIsNotDirectory(__FILE__);
    }

    public function testSymbolicLinkNotFound(): void
    {
        $symbolicLink = sys_get_temp_dir() . '/' . uniqid();
        symlink(__DIR__ . '/foo', $symbolicLink);

        $this->assertIsNotDirectory(__FILE__);
    }

    private function assertIsNotDirectory(string $path, string $errorMessage = null): self
    {
        $this->assertExceptionIsThrowned(
            function () use ($path, $errorMessage): void {
                DirectoryUtils::assertIsDirectory($path, $errorMessage);
            },
            DirectoryNotFoundException::class,
            $errorMessage ?? "Directory \"$path\" not found."
        );

        return $this;
    }
}
