<?php

declare(strict_types=1);

namespace PhpObject\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\{
    Directory\DirectoryUtils,
    ErrorHandler\PhpObjectErrorHandlerUtils,
    Tests\PhpUnit\AbstractPhpObjectTestCase
};

final class GetWorkingDirectoryMethodTest extends AbstractPhpObjectTestCase
{
    public function testExistingDirectory(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        $this->enableTestErrorHandler();

        static::assertEquals('/app', DirectoryUtils::getWorkingDirectory());
        static::assertNoPhpError($this->getLastPhpError());
    }
}
