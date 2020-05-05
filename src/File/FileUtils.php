<?php

declare(strict_types=1);

namespace PhpObject\Core\File;

use PhpObject\Core\{
    ErrorHandler\PhpObjectErrorHandlerManager,
    Exception\PhpObjectException,
    Path\PathUtils,
    Variable\ResourceUtils
};

class FileUtils
{
    /**
     * @link https://www.php.net/manual/en/function.copy.php
     * @param resource|null $context
     */
    public static function copy(string $source, string $destination, $context = null): void
    {
        PathUtils::assertIsFile($source);

        if (ResourceUtils::isResource($context) === true) {
            PhpObjectErrorHandlerManager::enable();
            $result = copy($source, $destination);
            $lastError = PhpObjectErrorHandlerManager::disable();
        } else {
            PhpObjectErrorHandlerManager::enable();
            $result = copy($source, $destination);
            $lastError = PhpObjectErrorHandlerManager::disable();
        }

        PhpObjectErrorHandlerManager::assertNoError();

        if ($result !== true) {
            throw new PhpObjectException("Copy \"$source\" to \"$destination\" fail.", $lastError);
        }
    }
}
