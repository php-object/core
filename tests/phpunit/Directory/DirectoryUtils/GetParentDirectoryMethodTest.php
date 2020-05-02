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
        $result = $this->callPhpObjectMethod(
            function (): string {
                return DirectoryUtils::getParentDirectory(__DIR__);
            }
        );
        static::assertSame(dirname(__DIR__), $result);
    }

    public function testUnknownDirectory(): void
    {
        $result = $this->callPhpObjectMethod(
            function (): string {
                return DirectoryUtils::getParentDirectory(__DIR__ . '/foo');
            }
        );
        static::assertSame(__DIR__, $result);
    }

    public function testLevel3(): void
    {
        $result = $this->callPhpObjectMethod(
            function (): string {
                return DirectoryUtils::getParentDirectory(__DIR__ . '/foo/bar/baz', 3);
            }
        );
        static::assertSame(__DIR__, $result);
    }

    public function testNoParent(): void
    {
        $result = $this->callPhpObjectMethod(
            function (): string {
                return DirectoryUtils::getParentDirectory('/', 3);
            }
        );
        static::assertSame('/', $result);
    }

    public function testTooManyLevels(): void
    {
        $result = $this->callPhpObjectMethod(
            function (): string {
                return DirectoryUtils::getParentDirectory(__DIR__, 1000);
            }
        );
        static::assertSame('/', $result);
    }

    public function testEmptyPath(): void
    {
        $result = $this->callPhpObjectMethod(
            function (): string {
                return DirectoryUtils::getParentDirectory('', 1000);
            }
        );
        static::assertSame('', $result);
    }
}
