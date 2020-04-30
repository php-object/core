<?php

declare(strict_types=1);

namespace PhpObject\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\{
    Directory\DirectoryUtils,
    ErrorHandler\PhpObjectErrorHandlerUtils,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractPhpObjectTestCase
};

final class ChangeRootDirectoryMethodTest extends AbstractPhpObjectTestCase
{
    public function testChrootNotFound(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);

        // chroot() is not available in PHPUnit context for PHP 7.1 and 7.2
        if ($this->isPhp71() === true || $this->isPhp72() === true) {
            $this->enableTestErrorHandler();
            try {
                DirectoryUtils::changeRootDirectory('/');
            } catch (\Error $exception) {
                static::assertEquals(
                    'Call to undefined function PhpObject\Directory\chroot()',
                    $exception->getMessage()
                );
                static::assertEquals(0, $exception->getCode());
            }
            static::assertNoPhpError($this->getLastPhpError());

        } elseif ($this->isPhp73() === true || $this->isPhp74() === true) {
            $this->addToAssertionCount(1);

        } else {
            static::fail('Unknown PHP version.');
        }
    }

    public function testExistingDirectory(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);

        // chroot() is not available in PHPUnit context for PHP 7.1 and 7.2
        if ($this->isPhp71() === true || $this->isPhp72() === true) {
            $this->addToAssertionCount(1);

        } elseif ($this->isPhp73() === true || $this->isPhp74() === true) {
            $this->enableTestErrorHandler();
            DirectoryUtils::changeRootDirectory('/');
            static::assertNoPhpError($this->getLastPhpError());

        } else {
            static::fail('Unknown PHP version.');
        }
    }

    public function testDirectoryNotFound(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(true);

        // chroot() is not available in PHPUnit context for PHP 7.1 and 7.2
        if ($this->isPhp71() === true || $this->isPhp72() === true) {
            $this->addToAssertionCount(1);

        } elseif ($this->isPhp73() === true || $this->isPhp74() === true) {
            $this->enableTestErrorHandler();
            try {
                DirectoryUtils::changeRootDirectory('/foo');
            } catch (DirectoryNotFoundException $excetion) {
                static::assertEquals('Directory "/foo" not found.', $excetion->getMessage());
                static::assertEquals(0, $excetion->getCode());
            }
            static::assertNoPhpError($this->getLastPhpError());

        } else {
            static::fail('Unknown PHP version.');
        }
    }

    public function testDirectoryNotFoundPhpError(): void
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);

        // chroot() is not available in PHPUnit context for PHP 7.1 and 7.2
        if ($this->isPhp71() === true || $this->isPhp72() === true) {
            $this->addToAssertionCount(1);

        } elseif ($this->isPhp73() === true || $this->isPhp74() === true) {
            $this->enableTestErrorHandler();
            try {
                DirectoryUtils::changeRootDirectory('/foo');
            } catch (DirectoryNotFoundException $excetion) {
                static::assertEquals('Directory "/foo" not found.', $excetion->getMessage());
                static::assertEquals(0, $excetion->getCode());
            }
            static::assertLastPhpError(
                $this->getLastPhpError(),
                E_WARNING,
                'chroot(): No such file or directory (errno 2)',
                '/app/src/Directory/DirectoryUtils.php',
                63
            );

        } else {
            static::fail('Unknown PHP version.');
        }
    }

    private function isPhp71(): bool
    {
        return version_compare(PHP_VERSION, '7.1.0') === 0;
    }

    private function isPhp72(): bool
    {
        return version_compare(PHP_VERSION, '7.2.0') === 0;
    }

    private function isPhp73(): bool
    {
        return version_compare(PHP_VERSION, '7.3.0') === 0;
    }

    private function isPhp74(): bool
    {
        return version_compare(PHP_VERSION, '7.4.0') === 0;
    }
}
