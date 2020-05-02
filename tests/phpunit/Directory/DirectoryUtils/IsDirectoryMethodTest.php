<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class IsDirectoryMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $directory = __DIR__;
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::isDirectory($directory);
        $this->disableTestErrorHandler();

        static::assertTrue($result, "Directory \"$directory\" does not exists but should exists.");
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testDirectoryNotFound(): void
    {
        $directory = __DIR__;
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::isDirectory($directory . '/foo');
        $this->disableTestErrorHandler();

        static::assertFalse($result, "Directory \"$directory\" exists but should notr exists.");
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testFile(): void
    {
        $file = __FILE__;
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::isDirectory($file);
        $this->disableTestErrorHandler();

        static::assertFalse($result, "Path \"$file\" should be a file but is considered as directory.");
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testSymbolicLink(): void
    {
        $symLink = sys_get_temp_dir() . '/' . uniqid();
        symlink(__DIR__, $symLink);

        $this->enableTestErrorHandler();
        $result = DirectoryUtils::isDirectory($symLink);
        $this->disableTestErrorHandler();

        unlink($symLink);

        static::assertTrue($result, "Symbolic link \"$symLink\" should be considered as a directory.");
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testSymbolicLinkNotFound(): void
    {
        $symLink = sys_get_temp_dir() . '/' . uniqid();
        symlink(__DIR__ . '/foo', $symLink);

        $this->enableTestErrorHandler();
        $result = DirectoryUtils::isDirectory($symLink);
        $this->disableTestErrorHandler();

        unlink($symLink);

        static::assertFalse($result, "Symbolic link \"$symLink\" should not be considered as a directory.");
        static::assertNoPhpError($this->getLastPhpError());
    }
}
