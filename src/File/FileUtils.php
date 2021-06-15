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
    public const USE_INCLUDE_PATH = true;
    public const DO_NOT_USE_INCLUDE_PATH = false;

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
            $lastError = PhpObjectErrorHandlerManager::disable(PhpObjectErrorHandlerManager::DO_NOT_ASSERT_NO_ERROR);
        } else {
            PhpObjectErrorHandlerManager::enable();
            $result = copy($source, $destination);
            $lastError = PhpObjectErrorHandlerManager::disable(PhpObjectErrorHandlerManager::DO_NOT_ASSERT_NO_ERROR);
        }

        PhpObjectErrorHandlerManager::assertNoError();

        if ($result !== true) {
            throw new PhpObjectException("Copy \"$source\" to \"$destination\" fail.", $lastError);
        }
    }

    /**
     * @link https://www.php.net/manual/en/function.file-get-contents.php
     * @param resource|null $context
     */
    public static function read(
        string $filename,
        bool $useIncludePath = self::DO_NOT_USE_INCLUDE_PATH,
        $context = null,
        int $offset = 0,
        int $length = null
    ): string {
        PathUtils::assertIsFile($filename);

        // From PHP 7.1 to 7.4: "TypeError: file_get_contents() expects parameter 5 to be int, null given"
        if (is_int($length) === true) {
            PhpObjectErrorHandlerManager::enable();
            $return = file_get_contents($filename, $useIncludePath, $context, $offset, $length);
            $lastError = PhpObjectErrorHandlerManager::disable(PhpObjectErrorHandlerManager::DO_NOT_ASSERT_NO_ERROR);
        } else {
            PhpObjectErrorHandlerManager::enable();
            $return = file_get_contents($filename, $useIncludePath, $context, $offset);
            $lastError = PhpObjectErrorHandlerManager::disable(PhpObjectErrorHandlerManager::DO_NOT_ASSERT_NO_ERROR);
        }

        if (is_string($return) === false) {
            throw new PhpObjectException("Retrieving content of \"$filename\" fail.", $lastError);
        }

        PhpObjectErrorHandlerManager::assertNoError();

        return $return;
    }
}
