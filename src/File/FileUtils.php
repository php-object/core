<?php

declare(strict_types=1);

namespace PhpObject\Core\File;

use PhpObject\Core\{
    Directory\DirectoryUtils,
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
        if (is_null($context) === false) {
            ResourceUtils::assertIsResource($context, '$context should be a valid resource or null.');
        }

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
        if (is_null($context) === false) {
            ResourceUtils::assertIsResource($context, '$context should be a valid resource or null.');
        }

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

    /**
     * @link https://www.php.net/manual/en/function.file-put-contents.php
     * @param resource|null $context
     * @return int Number of bytes written
     */
    public static function writeString(string $filename, string $data, int $flags = 0, $context = null): int
    {
        return static::write($filename, $data, $flags, $context);
    }

    /**
     * @link https://www.php.net/manual/en/function.file-put-contents.php
     * @param array<mixed> $data
     * @param resource|null $context
     * @return int Number of bytes written
     */
    public static function writeArray(string $filename, array $data, int $flags = 0, $context = null): int
    {
        return static::write($filename, $data, $flags, $context);
    }

    /**
     * @link https://www.php.net/manual/en/function.file-put-contents.php
     * @param resource $data
     * @param resource|null $context
     * @return int Number of bytes written
     */
    public static function writeResource(string $filename, $data, int $flags = 0, $context = null): int
    {
        ResourceUtils::assertIsResource($data, '$data should be a valid resource.');

        return static::write($filename, $data, $flags, $context);
    }

    public static function getLastAccess(string $filename): \DateTimeImmutable
    {
        PathUtils::assertIsFile($filename);

        PhpObjectErrorHandlerManager::enable();
        $timestamp = fileatime($filename);
        $lastError = PhpObjectErrorHandlerManager::disable(PhpObjectErrorHandlerManager::DO_NOT_ASSERT_NO_ERROR);
        if (is_int($timestamp) === false || $timestamp < 0) {
            throw new PhpObjectException("Error while getting last access time of file \"$filename\".", $lastError);
        }
        PhpObjectErrorHandlerManager::assertNoError();

        PhpObjectErrorHandlerManager::enable();
        $return = (new \DateTimeImmutable())->setTimestamp($timestamp);
        PhpObjectErrorHandlerManager::disable();

        return $return;
    }

    /**
     * @param string|array<mixed>|resource $data
     * @param resource|null $context
     * @return int Number of bytes written
     */
    protected static function write(string $filename, $data, int $flags = 0, $context = null): int
    {
        if (is_null($context) === false) {
            ResourceUtils::assertIsResource($context, '$context should be a valid resource or null.');
        }

        PathUtils::assertIsDirectory(DirectoryUtils::getParentDirectory($filename));

        PhpObjectErrorHandlerManager::enable();
        $return = file_put_contents($filename, $data, $flags, $context);
        $lastError = PhpObjectErrorHandlerManager::disable(PhpObjectErrorHandlerManager::DO_NOT_ASSERT_NO_ERROR);

        if (is_int($return) === false) {
            throw new PhpObjectException("Error while writing file \"$filename\".", $lastError);
        }

        PhpObjectErrorHandlerManager::assertNoError();

        return $return;
    }
}
