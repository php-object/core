<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Link\SymbolicLinkUtils;

use PhpObject\Core\{
    Link\SymbolicLinkUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class IsSymbolicLinkMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $directory = $this->getTemporaryPath();
        mkdir($directory);

        $result = $this->callPhpObjectMethod(
            function () use ($directory): bool {
                return SymbolicLinkUtils::isSymbolicLink($directory);
            }
        );
        static::assertFalse($result);
    }

    public function testDirectoryNotFound(): void
    {
        $directory = $this->getTemporaryPath();

        $result = $this->callPhpObjectMethod(
            function () use ($directory): bool {
                return SymbolicLinkUtils::isSymbolicLink($directory);
            }
        );
        static::assertFalse($result);
    }

    public function testExistingSymbolicLink(): void
    {
        $directory = $this->getTemporaryPath();
        mkdir($directory);
        $symbolicLink = $this->getTemporaryPath();
        symlink($directory, $symbolicLink);

        $result = $this->callPhpObjectMethod(
            function () use ($symbolicLink): bool {
                return SymbolicLinkUtils::isSymbolicLink($symbolicLink);
            }
        );
        static::assertTrue($result);
    }

    public function testSymbolicLinkNotFound(): void
    {
        $directory = $this->getTemporaryPath();
        mkdir($directory);
        $symbolicLink = $this->getTemporaryPath();

        $result = $this->callPhpObjectMethod(
            function () use ($symbolicLink): bool {
                return SymbolicLinkUtils::isSymbolicLink($symbolicLink);
            }
        );
        static::assertFalse($result);
    }
}
