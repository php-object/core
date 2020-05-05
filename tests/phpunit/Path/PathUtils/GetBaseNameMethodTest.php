<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Path\PathUtils;

use PhpObject\Core\Path\PathUtils;

final class GetBaseNameMethodTest extends \PhpObject\Core\Tests\PhpUnit\AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $directory = __DIR__;

        $result = $this->callPhpObjectMethod(
            function () use ($directory): string {
                return PathUtils::getBaseName($directory);
            }
        );
        static::assertEquals('PathUtils', $result);
    }

    public function testDirectoryNotFound(): void
    {
        $directory = __DIR__ . '/foo';

        $result = $this->callPhpObjectMethod(
            function () use ($directory): string {
                return PathUtils::getBaseName($directory);
            }
        );
        static::assertEquals('foo', $result);
    }

    public function testSuffix(): void
    {
        $directory = __DIR__;

        $result = $this->callPhpObjectMethod(
            function () use ($directory): string {
                return PathUtils::getBaseName($directory, 'Utils');
            }
        );
        static::assertEquals('Path', $result);
    }

    public function testSuffixNotFound(): void
    {
        $directory = __DIR__;

        $result = $this->callPhpObjectMethod(
            function () use ($directory): string {
                return PathUtils::getBaseName($directory, 'Foo');
            }
        );
        static::assertEquals('PathUtils', $result);
    }
}
