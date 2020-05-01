<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class GetWorkingDirectoryMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $this->enableTestErrorHandler();
        $result = DirectoryUtils::getWorkingDirectory();
        $this->disableTestErrorHandler();

        static::assertSame('/app', $result);
        static::assertNoPhpError($this->getLastPhpError());
    }
}
