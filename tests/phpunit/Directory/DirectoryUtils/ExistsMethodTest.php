<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class ExistsMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $directory = __DIR__;
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::exists($directory);
        $this->disableTestErrorHandler();

        static::assertTrue($result, "Directory \"$directory\" does not exists but should exists.");
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testDirectoryNotFound(): void
    {
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::exists(__DIR__ . '/foo');
        $this->disableTestErrorHandler();

        static::assertFalse($result);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testFile(): void
    {
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::exists(__FILE__);
        $this->disableTestErrorHandler();

        static::assertFalse($result);
        static::assertNoPhpError($this->getLastPhpError());
    }
}
