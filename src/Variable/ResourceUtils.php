<?php

declare(strict_types=1);

namespace PhpObject\Core\Variable;

use PhpObject\Core\{ErrorHandler\PhpError,
    ErrorHandler\PhpObjectErrorHandlerManager,
    Exception\Variable\ResourceExpectedException};

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
        PhpObjectErrorHandlerManager::assertNoError();

        return $return;
    }

    /** @param mixed $value */
    public static function assertIsResource($value, string $error, PhpError $phpError = null): void
    {
        if (static::isResource($value) === false) {
            throw new ResourceExpectedException($error, $phpError);
        }
    }
}
