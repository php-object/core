<?php

declare(strict_types=1);

namespace PhpObject\Core\Directory;

use PhpObject\Core\{
    ErrorHandler\PhpObjectErrorHandlerManager,
    Exception\Variable\ResourceExpectedException,
    Variable\ResourceUtils
};

class DirectoryManager
{
    /**
     * @param resource|null $context
     * @link https://www.php.net/manual/en/function.opendir.php
     */
    public static function createFromPath(string $path, $context = null): self
    {
        DirectoryUtils::assertIsDirectory($path);

        // PHP 7.1 and 7.2 do not allow null for $context: if no value, you should not pass this argument
        if ($context === null) {
            PhpObjectErrorHandlerManager::enable();
            $handle = opendir($path);
            $lastError = PhpObjectErrorHandlerManager::disable();
        } else {
            PhpObjectErrorHandlerManager::enable();
            $handle = opendir($path, $context);
            $lastError = PhpObjectErrorHandlerManager::disable();
        }

        ResourceUtils::assertIsResource($handle, "Error while opening directory \"$path\".", $lastError);
        PhpObjectErrorHandlerManager::assertNoError();

        return new static($handle);
    }

    /** @var resource */
    protected $handle;

    /** @param resource $handle */
    public function __construct($handle)
    {
        ResourceUtils::assertIsResource($handle, ResourceExpectedException::createDefaultMessage('handle', $handle));

        $this->handle = $handle;
    }

    /** @return resource */
    public function getHandle()
    {
        return $this->handle;
    }
}
