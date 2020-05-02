<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryUtils;

use PhpObject\Core\{
    Directory\DirectoryUtils,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractTestCase
};

final class DeleteMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $directory = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        mkdir($directory);

        $this->enableTestErrorHandler();
        DirectoryUtils::delete($directory);
        $this->disableTestErrorHandler();

        static::assertFalse(is_dir($directory));
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testDirectoryNotFound(): void
    {
        $directory = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');

        $exceptionThrowned = false;
        $this->enableTestErrorHandler();
        try {
            DirectoryUtils::delete($directory);
        } catch (DirectoryNotFoundException $exception) {
            $this->disableTestErrorHandler();

            static::assertException(
                $exception,
                DirectoryNotFoundException::class,
                "Directory \"$directory\" not found."
            );
            static::assertExceptionWithoutPhpError($exception);

            $exceptionThrowned = true;
        }

        $this->assertExceptionThrowned($exceptionThrowned, DirectoryNotFoundException::class);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testFile(): void
    {
        $directory = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        mkdir($directory);
        $file = "$directory/foo";
        touch($file);

        $exceptionThrowned = false;
        $this->enableTestErrorHandler();
        try {
            DirectoryUtils::delete($file);
        } catch (DirectoryNotFoundException $exception) {
            $this->disableTestErrorHandler();

            static::assertException(
                $exception,
                DirectoryNotFoundException::class,
                "Directory \"$file\" not found."
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
        $directory = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        symlink($sourceDirectory, $directory);

        $exceptionThrowned = false;
        $this->enableTestErrorHandler();
        try {
            DirectoryUtils::delete($directory);
        } catch (DirectoryNotFoundException $exception) {
            $this->disableTestErrorHandler();

            static::assertException(
                $exception,
                DirectoryNotFoundException::class,
                "Directory \"$directory\" is a symbolic link, "
                    . 'use PhpObject\Core\Link\SymbolicLinkUtils::delete() to delete it.'
            );
            static::assertExceptionWithoutPhpError($exception);

            $exceptionThrowned = true;
        }

        static::assertExceptionThrowned($exceptionThrowned, DirectoryNotFoundException::class);
        static::assertNoPhpError($this->getLastPhpError());
    }

    public function testSymbolicLinkNotFound(): void
    {
        $directory = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        $destination = sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
        symlink($directory, $destination);

        $exceptionThrowned = false;
        $this->enableTestErrorHandler();
        try {
            DirectoryUtils::delete($directory);
        } catch (DirectoryNotFoundException $exception) {
            $this->disableTestErrorHandler();

            static::assertException(
                $exception,
                DirectoryNotFoundException::class,
                "Directory \"$directory\" not found."
            );
            static::assertExceptionWithoutPhpError($exception);

            $exceptionThrowned = true;
        }

        static::assertExceptionThrowned($exceptionThrowned, DirectoryNotFoundException::class);
        static::assertNoPhpError($this->getLastPhpError());
    }
}
