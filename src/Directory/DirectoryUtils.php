<?php

declare(strict_types=1);

namespace PhpObject\Directory;

use PhpObject\{
    ErrorHandler\PhpObjectErrorHandlerUtils,
    Exception\Directory\DirectoryNotFoundException
};

class DirectoryUtils
{
    /** @link https://www.php.net/manual/en/function.chdir.php */
    public static function changeCurrentDirectory(string $directory): void
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
}
