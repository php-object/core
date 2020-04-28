<?php

declare(strict_types=1);

namespace PhpObject\Directory;

use PhpObject\{
    ErrorHandler\PhpObjectErrorHandlerUtils,
    Exception\Directory\DirectoryNotFoundException
};

class DirectoryUtils
{
    /** @link https://www.php.net/manual/fr/function.chdir.php */
    public static function change(string $directory): void
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
}
