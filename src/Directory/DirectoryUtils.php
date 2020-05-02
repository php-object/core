<?php

declare(strict_types=1);

namespace PhpObject\Core\Directory;

use PhpObject\Core\{
    ErrorHandler\PhpObjectErrorHandlerManager,
    Exception\Directory\DirectoryNotFoundException,
    Exception\PhpObjectException,
    Link\SymbolicLinkUtils
};

class DirectoryUtils
{
    /** @link https://www.php.net/manual/en/function.chdir.php */
    public static function changeWorkingDirectory(string $path): void
    {
        static::assertIsDirectory($path);

        PhpObjectErrorHandlerManager::enable();
        $executed = chdir($path);
        $lastError = PhpObjectErrorHandlerManager::disable();

        if ($executed === false) {
            throw new PhpObjectException(
                "Error while changing working directory to \"$path\".",
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
    public static function changeRootDirectory(string $path): void
    {
        static::assertIsDirectory($path);

        PhpObjectErrorHandlerManager::enable();
        $executed = chroot($path);
        $lastError = PhpObjectErrorHandlerManager::disable();

        if ($executed === false) {
            throw new PhpObjectException(
                "Error while changing root directory to \"$path\".",
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

    public static function assertIsDirectory(string $path, string $errorMessage = null): void
    {
        if (static::isDirectory($path) === false) {
            throw new DirectoryNotFoundException(
                $errorMessage ?? DirectoryNotFoundException::createDefaultMessage($path)
            );
        }
    }

    /**
     * @param resource|null $context
     * @link https://www.php.net/manual/en/function.rename.php
     */
    public static function move(string $source, string $destination, $context = null): void
    {
        static::assertIsDirectory($source, "Source directory \"$source\" not found.");
        $destinationParentDirectory = static::getParentDirectory($destination);
        static::assertIsDirectory(
            $destinationParentDirectory,
            "Destination parent directory \"$destinationParentDirectory\" not found."
        );

        // PHP 7.1 and 7.2 do not allow null for $context: if no value, you should not pass this argument
        if ($context === null) {
            PhpObjectErrorHandlerManager::enable();
            $result = rename($source, $destination);
            $lastError = PhpObjectErrorHandlerManager::disable();
        } else {
            PhpObjectErrorHandlerManager::enable();
            $result = rename($source, $destination, $context);
            $lastError = PhpObjectErrorHandlerManager::disable();
        }

        if ($result !== true) {
            throw new PhpObjectException("Directory \"$source\" cannot be moved to \"$destination\".", $lastError);
        }

        PhpObjectErrorHandlerManager::assertNoError();
    }

    /**
     * @param resource|null $context
     * @link https://www.php.net/manual/en/function.rmdir.php
     */
    public static function delete(string $path, $context = null): void
    {
        if (SymbolicLinkUtils::isSymbolicLink($path) === true) {
            throw new DirectoryNotFoundException(
                "Directory \"$path\" is a symbolic link, use " . SymbolicLinkUtils::class . '::delete() to delete it.'
            );
        }

        static::assertIsDirectory($path);

        // PHP 7.1 and 7.2 do not allow null for $context: if no value, you should not pass this argument
        if ($context === null) {
            PhpObjectErrorHandlerManager::enable();
            $result = rmdir($path);
            $lastError = PhpObjectErrorHandlerManager::disable();
        } else {
            PhpObjectErrorHandlerManager::enable();
            $result = rmdir($path, $context);
            $lastError = PhpObjectErrorHandlerManager::disable();
        }

        if ($result !== true) {
            throw new PhpObjectException("Directory \"$path\" cannot be deleted.", $lastError);
        }

        PhpObjectErrorHandlerManager::assertNoError();
    }
}
