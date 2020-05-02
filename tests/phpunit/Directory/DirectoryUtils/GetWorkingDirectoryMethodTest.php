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
        $result = $this->callPhpObjectMethod(
            function (): string {
                return DirectoryUtils::getWorkingDirectory();
            }
        );
        static::assertSame('/app', $result);
    }
}
