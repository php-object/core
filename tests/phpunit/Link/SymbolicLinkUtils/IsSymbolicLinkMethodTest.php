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
        $directory = $this->getTemporaryDirectory();
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
        $directory = $this->getTemporaryDirectory();

        $result = $this->callPhpObjectMethod(
            function () use ($directory): bool {
                return SymbolicLinkUtils::isSymbolicLink($directory);
            }
        );
        static::assertFalse($result);
    }

    public function testExistingSymbolicLink(): void
    {
        $directory = $this->getTemporaryDirectory();
        mkdir($directory);
        $symbolicLink = $this->getTemporaryDirectory();
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
        $directory = $this->getTemporaryDirectory();
        mkdir($directory);
        $symbolicLink = $this->getTemporaryDirectory();

        $result = $this->callPhpObjectMethod(
            function () use ($symbolicLink): bool {
                return SymbolicLinkUtils::isSymbolicLink($symbolicLink);
            }
        );
        static::assertFalse($result);
    }
}
