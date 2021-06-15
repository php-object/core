<?php

declare(strict_types=1);

namespace PhpObject\Core\Variable;

use PhpObject\Core\{
    ErrorHandler\PhpError,
    ErrorHandler\PhpObjectErrorHandlerManager,
    Exception\Variable\ResourceExpectedException
};

class ResourceUtils
{
    /**
     * @param mixed $resource
     * @link https://www.php.net/manual/en/function.is-resource.php
     */
    public static function isResource($resource): bool
    {
        PhpObjectErrorHandlerManager::enable();
        $return = is_resource($resource);
        PhpObjectErrorHandlerManager::disable();

        return $return;
    }

    /** @param mixed $value */
    public static function assertIsResource($value, string $error, PhpError $phpError = null): void
    {
        if (static::isResource($value) === false) {
            throw new ResourceExpectedException($error, $phpError);
        }
    }

    /**
     * @param mixed $resource
     * @link https://www.php.net/manual/en/function.get-resource-type.php
     */
    public static function getType($resource): string
    {
        PhpObjectErrorHandlerManager::enable();
        try {
            $return = get_resource_type($resource);
        } catch (\TypeError $exception) {
            PhpObjectErrorHandlerManager::disable();

            throw new ResourceExpectedException(
                ResourceExpectedException::createDefaultMessage('resource', $resource),
                null,
                0,
                $exception
            );
        }

        PhpObjectErrorHandlerManager::disable();

        return $return;
    }

    /** @param mixed $resource */
    public static function isOpen($resource): bool
    {
        return static::getType($resource) !== 'Unknown';
    }

    /** @param mixed $resource */
    public static function isClosed($resource): bool
    {
        return static::getType($resource) === 'Unknown';
    }
}
