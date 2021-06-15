<?php

declare(strict_types=1);

namespace PhpObject\Core\Path;

use PhpObject\Core\{
    ErrorHandler\PhpObjectErrorHandlerManager,
    Exception\Directory\DirectoryNotFoundException,
    Exception\File\FileNotFoundException
};

class PathUtils
{
    /** @link https://www.php.net/manual/en/function.basename.php */
    public static function getBaseName(string $path, string $suffix = null): string
    {
        if (is_string($suffix) === true) {
            PhpObjectErrorHandlerManager::enable();
            $return = basename($path, $suffix);
            PhpObjectErrorHandlerManager::disable();
        } else {
            PhpObjectErrorHandlerManager::enable();
            $return = basename($path);
            PhpObjectErrorHandlerManager::disable();
        }

        return $return;
    }

    /** @link https://www.php.net/manual/en/function.is-dir.php */
    public static function isDirectory(string $path): bool
    {
        PhpObjectErrorHandlerManager::enable();
        $return = is_dir($path);
        PhpObjectErrorHandlerManager::disable();

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

    public static function isFile(string $path): bool
    {
        PhpObjectErrorHandlerManager::enable();
        $return = is_file($path);
        PhpObjectErrorHandlerManager::disable();

        return $return;
    }

    public static function assertIsFile(string $path, string $errorMessage = null): void
    {
        if (static::isFile($path) === false) {
            throw new FileNotFoundException(
                $errorMessage ?? FileNotFoundException::createDefaultMessage($path)
            );
        }
    }
}
