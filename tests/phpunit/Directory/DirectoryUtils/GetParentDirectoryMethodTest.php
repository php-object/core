<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class GetParentDirectoryMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::getParentDirectory(__DIR__);
        $this->disableTestErrorHandler();

        static::assertSame(dirname(__DIR__), $result);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testUnknownDirectory(): void
    {
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::getParentDirectory(__DIR__ . '/foo');
        $this->disableTestErrorHandler();

        static::assertSame(__DIR__, $result);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testLevel3(): void
    {
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::getParentDirectory(__DIR__ . '/foo/bar/baz', 3);
        $this->disableTestErrorHandler();

        static::assertSame(__DIR__, $result);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testNoParent(): void
    {
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::getParentDirectory('/', 3);
        $this->disableTestErrorHandler();

        static::assertSame('/', $result);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testTooManyLevels(): void
    {
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::getParentDirectory(__DIR__, 1000);
        $this->disableTestErrorHandler();

        static::assertSame('/', $result);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testEmptyPath(): void
    {
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::getParentDirectory('', 1000);
        $this->disableTestErrorHandler();

        static::assertSame('', $result);
        static::assertNoPhpError($this->getLastPhpError());
    }
}
