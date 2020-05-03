<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractTestCase,
    Version\PhpVersionUtils
};

final class ChangeRootDirectoryMethodTest extends AbstractTestCase
{
    public function testChrootNotFound(): void
    {
        // chroot() is not available in PHPUnit context for PHP 7.1 and 7.2
        if (PhpVersionUtils::is71() === true || PhpVersionUtils::is72() === true) {
            $this->assertExceptionIsThrowned(
                function (): void {
                    DirectoryUtils::changeRootDirectory('/');
                },
                \Error::class,
                'Call to undefined function PhpObject\Core\Directory\chroot()'
            );

        } elseif (PhpVersionUtils::is73() === true || PhpVersionUtils::is74() === true) {
            $this->addToAssertionCount(1);

        } else {
            static::fail('Unknown PHP version.');
        }
    }

    public function testExistingDirectory(): void
    {
        // chroot() is not available in PHPUnit context for PHP 7.1 and 7.2
        if (PhpVersionUtils::is71() === true || PhpVersionUtils::is72() === true) {
            $this->addToAssertionCount(1);

        } elseif (PhpVersionUtils::is73() === true || PhpVersionUtils::is74() === true) {
            $this->callPhpObjectMethod(
                function (): void {
                    DirectoryUtils::changeRootDirectory('/');
                }
            );
            static::assertSame('/', getcwd());

        } else {
            static::fail('Unknown PHP version.');
        }
    }

    public function testDirectoryNotFound(): void
    {
        // chroot() is not available in PHPUnit context for PHP 7.1 and 7.2
        if (PhpVersionUtils::is71() === true || PhpVersionUtils::is72() === true) {
            $this->addToAssertionCount(1);

        } elseif (PhpVersionUtils::is73() === true || PhpVersionUtils::is74() === true) {
            $this->assertExceptionIsThrowned(
                function (): void {
                    DirectoryUtils::changeRootDirectory('/foo');
                },
                DirectoryNotFoundException::class,
                'Directory "/foo" not found.'
            );

        } else {
            static::fail('Unknown PHP version.');
        }
    }
}
