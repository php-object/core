<?php

declare(strict_types=1);

namespace PhpObject\Core\Variable;

use PhpObject\Core\ErrorHandler\PhpObjectErrorHandlerManager;

class VariableUtils
{
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_DOUBLE = 'double';
    public const TYPE_STRING = 'string';
    public const TYPE_ARRAY = 'array';
    public const TYPE_OBJECT = 'object';
    public const TYPE_RESOURCE = 'resource';
    public const TYPE_CLOSED_RESOURCE = 'resource (closed)';
    public const TYPE_NULL = 'NULL';
    public const TYPE_UNKNOWN_TYPE = 'unknown type';

    /**
     * @param mixed $value
     * @link https://www.php.net/manual/en/function.gettype.php
     */
    public static function getType($value): string
    {
        PhpObjectErrorHandlerManager::enable();
        $return = gettype($value);
        PhpObjectErrorHandlerManager::disable();
        PhpObjectErrorHandlerManager::assertNoError();

        return $return;
    }
}
