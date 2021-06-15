<?php

declare(strict_types=1);

namespace PhpObject\Core\Directory;

use PhpObject\Core\{
    ErrorHandler\PhpObjectErrorHandlerManager,
    Exception\PhpObjectException,
    Exception\Variable\ResourceExpectedException,
    Exception\Variable\ResourceIsClosedException,
    Path\PathUtils,
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
        PathUtils::assertIsDirectory($path);

        // PHP 7.1 and 7.2 do not allow null for $context: if no value, you should not pass this argument
        if ($context === null) {
            PhpObjectErrorHandlerManager::enable();
            $handle = opendir($path);
            $lastError = PhpObjectErrorHandlerManager::disable(PhpObjectErrorHandlerManager::DO_NOT_ASSERT_NO_ERROR);
        } else {
            PhpObjectErrorHandlerManager::enable();
            $handle = opendir($path, $context);
            $lastError = PhpObjectErrorHandlerManager::disable(PhpObjectErrorHandlerManager::DO_NOT_ASSERT_NO_ERROR);
        }

        ResourceUtils::assertIsResource($handle, "Error while opening directory \"$path\".", $lastError);
        PhpObjectErrorHandlerManager::assertNoError();

        return new static($handle);
    }

    /** @var resource */
    protected $resource;

    /** @param resource $resource */
    public function __construct($resource)
    {
        ResourceUtils::assertIsResource(
            $resource,
            ResourceExpectedException::createDefaultMessage('resource', $resource)
        );

        $this->resource = $resource;
    }

    public function __destruct()
    {
        if ($this->isOpen() === true) {
            $this->close();
        }
    }

    /** @return resource */
    public function getResource()
    {
        return $this->resource;
    }

    public function isOpen(): bool
    {
        return ResourceUtils::isResource($this->getResource());
    }

    public function assertIsOpen(): self
    {
        if ($this->isOpen() === false) {
            throw new ResourceIsClosedException(ResourceIsClosedException::createDefaultMessage('resource'));
        }

        return $this;
    }

    /** @link https://www.php.net/manual/en/function.readdir.php */
    public function getNext(): ?string
    {
        $this->assertIsOpen();

        PhpObjectErrorHandlerManager::enable();
        $return = readdir($this->getResource());
        PhpObjectErrorHandlerManager::disable();

        return $return === false ? null : $return;
    }

    /** @link https://www.php.net/manual/en/function.readdir.php */
    public function getNextFileOrDirectory(): ?string
    {
        $this->assertIsOpen();

        $return = $this->getNext();
        while ($return === '.' || $return === '..') {
            $return = $this->getNext();
        }

        return $return;
    }

    /** @link https://www.php.net/manual/en/function.rewinddir.php */
    public function reset(): self
    {
        $this->assertIsOpen();

        PhpObjectErrorHandlerManager::enable();
        /**
         * Official PHP documentation indicate void as result type for rewinddir but it's null|false
         * @var null|false $result
         */
        $result = rewinddir($this->getResource());
        $lastError = PhpObjectErrorHandlerManager::disable(PhpObjectErrorHandlerManager::DO_NOT_ASSERT_NO_ERROR);

        if ($result !== null) {
            throw new PhpObjectException('Error while resetting directory handle.', $lastError);
        }

        PhpObjectErrorHandlerManager::assertNoError();

        return $this;
    }

    /** @link https://www.php.net/manual/en/function.closedir.php */
    public function close(): self
    {
        $this->assertIsOpen();

        PhpObjectErrorHandlerManager::enable();
        closedir($this->getResource());
        PhpObjectErrorHandlerManager::disable();

        return $this;
    }
}
