<?php

declare(strict_types=1);

namespace PhpObject\Core\Directory;

use PhpObject\Core\{
    ErrorHandler\PhpObjectErrorHandlerManager,
    Exception\Directory\DirectoryNotFoundException,
    Exception\PhpObjectException
};

class DirectoryUtils
{
    /** @link https://www.php.net/manual/en/function.chdir.php */
    public static function changeWorkingDirectory(string $directory): void
    {
        PhpObjectErrorHandlerManager::enable();
        $executed = chdir($directory);
        $lastError = PhpObjectErrorHandlerManager::disable();

        if ($executed === false) {
            throw new DirectoryNotFoundException(
                DirectoryNotFoundException::createDefaultMessage($directory),
                $lastError
            );
        }

        PhpObjectErrorHandlerManager::assertNoError();
    }

    /** @link https://www.php.net/manual/en/function.getcwd.php */
    public static function getWorkingDirectory(): string
    {
        PhpObjectErrorHandlerManager::enable();
        $return = getcwd();
        $lastError = PhpObjectErrorHandlerManager::disable();

        if (is_string($return) === false) {
            throw new PhpObjectException(
                'getcwd() failed, maybe one of the parent directories does not have the readable or search mode set?',
                $lastError
            );
        }

        PhpObjectErrorHandlerManager::assertNoError();

        return $return;
    }

    /** @link https://www.php.net/manual/en/function.chroot.php */
    public static function changeRootDirectory(string $directory): void
    {
        PhpObjectErrorHandlerManager::enable();
        $executed = chroot($directory);
        $lastError = PhpObjectErrorHandlerManager::disable();

        if ($executed === false) {
            throw new DirectoryNotFoundException(
                DirectoryNotFoundException::createDefaultMessage($directory),
                $lastError
            );
        }

        PhpObjectErrorHandlerManager::assertNoError();
    }

    /** @link https://www.php.net/manual/en/function.dirname.php */
    public static function getParentDirectory(string $path, int $levels = 1): string
    {
        PhpObjectErrorHandlerManager::enable();
        $return = dirname($path, $levels);
        PhpObjectErrorHandlerManager::disable();
        PhpObjectErrorHandlerManager::assertNoError();

        return $return;
    }

    /** @link https://www.php.net/manual/en/function.is-dir.php */
    public static function isDirectory(string $path): bool
    {
        PhpObjectErrorHandlerManager::enable();
        $return = is_dir($path);
        PhpObjectErrorHandlerManager::disable();
        PhpObjectErrorHandlerManager::assertNoError();

        return $return;
    }

    /**
     * @param resource|null $context
     * @link https://www.php.net/manual/en/function.rename.php
     */
    public static function move(string $source, string $destination, $context = null): void
    {
        if (static::isDirectory($source) === false) {
            throw new DirectoryNotFoundException("Source directory \"$source\" not found.");
        }

        if (static::isDirectory(static::getParentDirectory($destination)) === false) {
            throw new DirectoryNotFoundException(
                'Destination parent directory "' . static::getParentDirectory($destination) . '" not found.'
            );
        }

        PhpObjectErrorHandlerManager::enable();
        // PHP 7.1 and 7.2 do not allow null for $context: if no value, you should not pass this argument
        if ($context === null) {
            $result = rename($source, $destination);
        } else {
            $result = rename($source, $destination, $context);
        }
        $lastError = PhpObjectErrorHandlerManager::disable();

        if ($result !== true) {
            throw new PhpObjectException("Directory \"$source\" cannot be moved to \"$destination\".", $lastError);
        }

        PhpObjectErrorHandlerManager::assertNoError();
    }
}
