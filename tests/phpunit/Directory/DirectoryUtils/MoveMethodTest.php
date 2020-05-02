<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractTestCase
};

final class MoveMethodTest extends AbstractTestCase
{
    public function testSourceExists(): void
    {
        $source = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        mkdir($source);
        $destination = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');

        $this->enableTestErrorHandler();
        DirectoryUtils::move($source, $destination);
        $this->disableTestErrorHandler();

        static::assertTrue(is_dir($destination));
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testDestinationExists(): void
    {
        $source = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        mkdir($source);
        $destination = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        mkdir($destination);

        $this->enableTestErrorHandler();
        DirectoryUtils::move($source, $destination);
        $this->disableTestErrorHandler();

        static::assertTrue(is_dir($destination));
        // PHP documentation say an E_WARNING should be triggered in case of $destination alreay exists,
        // but that's not the case.
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testSourceNotFound(): void
    {
        $source = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        $destination = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');

        $exceptionThrowned = false;
        $this->enableTestErrorHandler();
        try {
            DirectoryUtils::move($source, $destination);
        } catch (DirectoryNotFoundException $exception) {
            $this->disableTestErrorHandler();

            static::assertSame(DirectoryNotFoundException::class, get_class($exception));
            static::assertSame("Source directory \"$source\" not found.", $exception->getMessage());
            static::assertNoPhpError($this->getLastPhpError());

            $exceptionThrowned = true;
        }

        $this->assertExceptionThrowned($exceptionThrowned, DirectoryNotFoundException::class);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testDestinationParentNotFound(): void
    {
        $source = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        mkdir($source);
        $destination = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-') . '/foo/bar/baz';
        $destinationParent = dirname($destination);

        $exceptionThrowned = false;
        $this->enableTestErrorHandler();
        try {
            DirectoryUtils::move($source, $destination);
        } catch (DirectoryNotFoundException $exception) {
            $this->disableTestErrorHandler();

            static::assertException(
                $exception,
                DirectoryNotFoundException::class,
                "Destination parent directory \"$destinationParent\" not found.",
                0
            );
            static::assertExceptionWithoutPhpError($exception);

            $exceptionThrowned = true;
        }

        static::assertExceptionThrowned($exceptionThrowned, DirectoryNotFoundException::class);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testFile(): void
    {
        $sourceDirectory = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        mkdir($sourceDirectory);
        $source = "$sourceDirectory/foo";
        touch($source);
        $destination = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');

        $exceptionThrowned = false;
        $this->enableTestErrorHandler();
        try {
            DirectoryUtils::move($source, $destination);
        } catch (DirectoryNotFoundException $exception) {
            $this->disableTestErrorHandler();

            static::assertException(
                $exception,
                DirectoryNotFoundException::class,
                "Source directory \"$source\" not found.",
                0
            );
            static::assertExceptionWithoutPhpError($exception);

            $exceptionThrowned = true;
        }

        static::assertExceptionThrowned($exceptionThrowned, DirectoryNotFoundException::class);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testSymbolicLink(): void
    {
        $sourceDirectory = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        mkdir($sourceDirectory);
        $symLink = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        symlink($sourceDirectory, $symLink);
        $destination = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');

        $this->enableTestErrorHandler();
        DirectoryUtils::move($symLink, $destination);
        $this->disableTestErrorHandler();

        static::assertTrue(is_dir($destination));
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testSymbolicLinkNotFound(): void
    {
        $source = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        $destination = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        symlink($source, $destination);

        $exceptionThrowned = false;
        $this->enableTestErrorHandler();
        try {
            DirectoryUtils::move($source, $destination);
        } catch (DirectoryNotFoundException $exception) {
            $this->disableTestErrorHandler();

            static::assertException(
                $exception,
                DirectoryNotFoundException::class,
                "Source directory \"$source\" not found.",
                0
            );
            static::assertExceptionWithoutPhpError($exception);

            $exceptionThrowned = true;
        }

        static::assertExceptionThrowned($exceptionThrowned, DirectoryNotFoundException::class);
        static::assertNoPhpError($this->getLastPhpError());
    }
}
