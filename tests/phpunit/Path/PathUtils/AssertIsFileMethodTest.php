<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Path\PathUtils;

use PhpObject\Core\{
    Exception\File\FileNotFoundException,
    Path\PathUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class AssertIsFileMethodTest extends AbstractTestCase
{
    public function testExistingFile(): void
    {
        $directory = __FILE__;

        $this->callPhpObjectMethod(
            function () use ($directory): void {
                PathUtils::assertIsFile($directory);
            }
        );
        $this->addToAssertionCount(1);
    }

    public function testErrorMessage(): void
    {
        $this->assertIsNotFile(__DIR__ . '/foo', 'Foo');
    }

    public function testFileNotFound(): void
    {
        $this->assertIsNotFile(__DIR__ . '/foo');
    }

    public function testDirectory(): void
    {
        $this->assertIsNotFile(__DIR__);
    }

    public function testSymbolicLink(): void
    {
        $symbolicLink = sys_get_temp_dir() . '/' . uniqid();
        symlink(__FILE__, $symbolicLink);

        $this->callPhpObjectMethod(
            function () use ($symbolicLink): void {
                PathUtils::assertIsFile($symbolicLink);
            }
        );
        $this->addToAssertionCount(1);
    }

    public function testSymbolicLinkNotFound(): void
    {
        $symbolicLink = sys_get_temp_dir() . '/' . uniqid();
        symlink(__DIR__ . '/foo', $symbolicLink);

        $this->assertIsNotFile($symbolicLink);
    }

    private function assertIsNotFile(string $path, string $errorMessage = null): self
    {
        $this->assertExceptionIsThrowned(
            function () use ($path, $errorMessage): void {
                PathUtils::assertIsFile($path, $errorMessage);
            },
            FileNotFoundException::class,
            $errorMessage ?? "File \"$path\" not found."
        );

        return $this;
    }
}
