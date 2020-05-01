<?php

declare(strict_types=1);

namespace PhpObject\Directory;

use PhpObject\{
    ErrorHandler\PhpObjectErrorHandlerUtils,
    Exception\Directory\DirectoryNotFoundException,
    Exception\PhpObjectException
};

class DirectoryUtils
{
    /** @link https://www.php.net/manual/en/function.chdir.php */
    public static function changeWorkingDirectory(string $directory): void
    {
        PhpObjectErrorHandlerUtils::disableCustomErrorHandler();
        try {
            $executed = chdir($directory);
        } catch (\Throwable $exception) {
            PhpObjectErrorHandlerUtils::restorePreviousErrorHandler();
            throw new DirectoryNotFoundException($directory, 0, $exception);
        }
        PhpObjectErrorHandlerUtils::restorePreviousErrorHandler();

        if ($executed === false) {
            throw new DirectoryNotFoundException($directory);
        }
    }

    /** @link https://www.php.net/manual/en/function.getcwd.php */
    public static function getWorkingDirectory(): string
    {
        PhpObjectErrorHandlerUtils::disableCustomErrorHandler();
        try {
            $return = getcwd();
        } catch (\Throwable $exception) {
            PhpObjectErrorHandlerUtils::restorePreviousErrorHandler();
            throw new PhpObjectException(
                'getcwd() failed, maybe one of the parent directories does not have the readable or search mode set?',
                0,
                $exception
            );
        }
        PhpObjectErrorHandlerUtils::restorePreviousErrorHandler();

        if (is_string($return) === false) {
            throw new PhpObjectException(
                'getcwd() failed, maybe one of the parent directories does not have the readable or search mode set?',
                0
            );
        }

        return $return;
    }

    /** @link https://www.php.net/manual/en/function.chroot.php */
    public static function changeRootDirectory(string $directory): void
    {
        PhpObjectErrorHandlerUtils::disableCustomErrorHandler();
        try {
            $executed = chroot($directory);
        } catch (\Error $exception) {
            PhpObjectErrorHandlerUtils::restorePreviousErrorHandler();
            if ($exception->getMessage() === 'Call to undefined function PhpObject\Directory\chroot()') {
                throw $exception;
            }
            throw new DirectoryNotFoundException($directory, 0, $exception);
        } catch (\Throwable $exception) {
            PhpObjectErrorHandlerUtils::restorePreviousErrorHandler();
            throw new DirectoryNotFoundException($directory, 0, $exception);
        }
        PhpObjectErrorHandlerUtils::restorePreviousErrorHandler();

        if ($executed === false) {
            throw new DirectoryNotFoundException($directory);
        }
    }

    public static function getParentDirectory(string $path, int $levels = 1): string
    {
        PhpObjectErrorHandlerUtils::disableCustomErrorHandler();
        $return = dirname($path, $levels);
        PhpObjectErrorHandlerUtils::restorePreviousErrorHandler();

        return $return;
    }

    public static function exists(string $directory): bool
    {
        PhpObjectErrorHandlerUtils::disableCustomErrorHandler();
        $return = file_exists($directory) && is_dir($directory);
        PhpObjectErrorHandlerUtils::restorePreviousErrorHandler();

        return $return;
    }
}
