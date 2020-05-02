<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractTestCase
};

final class ChangeRootDirectoryMethodTest extends AbstractTestCase
{
    public function testChrootNotFound(): void
    {
        // chroot() is not available in PHPUnit context for PHP 7.1 and 7.2
        if ($this->isPhp71() === true || $this->isPhp72() === true) {
            $exceptionThrowned = false;
            $this->enableTestErrorHandler();
            try {
                DirectoryUtils::changeRootDirectory('/');
            } catch (\Error $exception) {
                $this->disableTestErrorHandler();

                static::assertEquals(\Error::class, get_class($exception));
                static::assertEquals(
                    'Call to undefined function PhpObject\Core\Directory\chroot()',
                    $exception->getMessage()
                );
                static::assertEquals(0, $exception->getCode());
                static::assertNoPhpError($this->getLastPhpError());

                $exceptionThrowned = true;
            }

            $this->assertExceptionThrowned($exceptionThrowned, \Error::class);

        } elseif ($this->isPhp73() === true || $this->isPhp74() === true) {
            $this->addToAssertionCount(1);

        } else {
            static::fail('Unknown PHP version.');
        }
    }

    public function testExistingDirectory(): void
    {
        // chroot() is not available in PHPUnit context for PHP 7.1 and 7.2
        if ($this->isPhp71() === true || $this->isPhp72() === true) {
            $this->addToAssertionCount(1);

        } elseif ($this->isPhp73() === true || $this->isPhp74() === true) {
            $this->enableTestErrorHandler();
            DirectoryUtils::changeRootDirectory('/');
            $this->disableTestErrorHandler();

            static::assertSame('/', getcwd());
            static::assertNoPhpError($this->getLastPhpError());

        } else {
            static::fail('Unknown PHP version.');
        }
    }

    public function testDirectoryNotFound(): void
    {
        // chroot() is not available in PHPUnit context for PHP 7.1 and 7.2
        if ($this->isPhp71() === true || $this->isPhp72() === true) {
            $this->addToAssertionCount(1);

        } elseif ($this->isPhp73() === true || $this->isPhp74() === true) {
            $exceptionThrowned = true;
            $this->enableTestErrorHandler();

            try {
                DirectoryUtils::changeRootDirectory('/foo');
            } catch (DirectoryNotFoundException $exception) {
                $this->disableTestErrorHandler();

                static::assertException(
                    $exception,
                    DirectoryNotFoundException::class,
                    'Directory "/foo" not found.',
                    0
                );
                static::assertExceptionWithPhpError(
                    $exception,
                    E_WARNING,
                    'chroot(): No such file or directory (errno 2)',
                    DirectoryUtils::class,
                    55
                );
                static::assertNoPhpError($this->getLastPhpError());

                $exceptionThrowned = true;
            }

            $this->assertExceptionThrowned($exceptionThrowned, DirectoryNotFoundException::class);

        } else {
            static::fail('Unknown PHP version.');
        }
    }
}
