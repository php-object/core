<?php

declare(strict_types=1);

namespace PhpObject\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\{
    Directory\DirectoryUtils,
    ErrorHandler\PhpObjectErrorHandlerUtils,
    Tests\PhpUnit\AbstractPhpObjectTestCase
};

final class ExistsMethodTest extends AbstractPhpObjectTestCase
{
    public function testExistingDirectory(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        $this->enableTestErrorHandler();

        static::assertTrue(DirectoryUtils::exists(__DIR__));
        static::assertNoPhpError($this->getLastPhpError());

        $this->disableTestErrorHandler();
    }

    public function testNotFoundDirectory(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        $this->enableTestErrorHandler();

        static::assertFalse(DirectoryUtils::exists(__DIR__ . '/foo'));
        static::assertNoPhpError($this->getLastPhpError());

        $this->disableTestErrorHandler();
    }

    public function testFile(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        $this->enableTestErrorHandler();

        static::assertFalse(DirectoryUtils::exists(__FILE__));
        static::assertNoPhpError($this->getLastPhpError());

        $this->disableTestErrorHandler();
    }
}
