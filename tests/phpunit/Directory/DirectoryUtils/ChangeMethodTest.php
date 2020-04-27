<?php

declare(strict_types=1);

namespace phpunit\Directory\DirectoryUtils;

use PhpObject\{
    Directory\DirectoryUtils,
    Exception\Directory\DirectoryNotFoundException
};
use PHPUnit\Framework\TestCase;

final class ChangeMethodTest extends TestCase
{
    public function testExistingDirectory(): void
    {
        DirectoryUtils::change(sys_get_temp_dir());

        static::addToAssertionCount(1);
    }

    public function testDirectoryNotFound(): void
    {
        $directory = sys_get_temp_dir() . '/foo';

        static::expectException(DirectoryNotFoundException::class);
        static::expectExceptionMessage("Directory \"$directory\" not found.");
        static::expectExceptionCode(0);

        DirectoryUtils::change($directory);
    }
}
