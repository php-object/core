<?php

declare(strict_types=1);

namespace PhpObject\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\{
    Directory\DirectoryUtils,
    ErrorHandler\PhpObjectErrorHandlerUtils,
    Tests\PhpUnit\AbstractPhpObjectTestCase
};

final class GetParentDirectoryMethodTest extends AbstractPhpObjectTestCase
{
    public function testExistingDirectory(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        $this->enableTestErrorHandler();

        static::assertSame(dirname(__DIR__), DirectoryUtils::getParentDirectory(__DIR__));
        static::assertNoPhpError($this->getLastPhpError());

        $this->disableTestErrorHandler();
    }

    public function testUnknownDirectory(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        $this->enableTestErrorHandler();

        static::assertSame(__DIR__, DirectoryUtils::getParentDirectory(__DIR__ . '/foo'));
        static::assertNoPhpError($this->getLastPhpError());

        $this->disableTestErrorHandler();
    }

    public function testLevel(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        $this->enableTestErrorHandler();

        static::assertSame(__DIR__, DirectoryUtils::getParentDirectory(__DIR__ . '/foo/bar', 2));
        static::assertNoPhpError($this->getLastPhpError());

        $this->disableTestErrorHandler();
    }

    public function testNoParent(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        $this->enableTestErrorHandler();

        static::assertSame('/', DirectoryUtils::getParentDirectory('/'));
        static::assertNoPhpError($this->getLastPhpError());

        $this->disableTestErrorHandler();
    }

    public function testTooManyLevels(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        $this->enableTestErrorHandler();

        static::assertSame('/', DirectoryUtils::getParentDirectory('/foo/bar', 1000));
        static::assertNoPhpError($this->getLastPhpError());

        $this->disableTestErrorHandler();
    }

    public function testEmptyPath(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        $this->enableTestErrorHandler();

        static::assertSame('', DirectoryUtils::getParentDirectory(''));
        static::assertNoPhpError($this->getLastPhpError());

        $this->disableTestErrorHandler();
    }
}
