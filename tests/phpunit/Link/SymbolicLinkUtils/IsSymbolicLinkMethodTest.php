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
        $directory = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        mkdir($directory);

        $this->enableTestErrorHandler();
        $result = SymbolicLinkUtils::isSymbolicLink($directory);
        $this->disableTestErrorHandler();

        static::assertFalse($result);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testDirectoryNotFound(): void
    {
        $directory = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');

        $this->enableTestErrorHandler();
        $result = SymbolicLinkUtils::isSymbolicLink($directory);
        $this->disableTestErrorHandler();

        static::assertFalse($result);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testExistingSymbolicLink(): void
    {
        $directory = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        mkdir($directory);
        $symbolicLink = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        symlink($directory, $symbolicLink);

        $this->enableTestErrorHandler();
        $result = SymbolicLinkUtils::isSymbolicLink($symbolicLink);
        $this->disableTestErrorHandler();

        static::assertTrue($result);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testSymbolicLinkNotFound(): void
    {
        $directory = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        mkdir($directory);
        $symbolicLink = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');

        $this->enableTestErrorHandler();
        $result = SymbolicLinkUtils::isSymbolicLink($symbolicLink);
        $this->disableTestErrorHandler();

        static::assertFalse($result);
        static::assertNoPhpError($this->getLastPhpError());
    }
}
