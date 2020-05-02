<?php

declare(strict_types=1);

namespace PhpObject\Core\Exception\Variable;

use PhpObject\Core\{
    Exception\PhpObjectException,
    Variable\VariableUtils
};

class ResourceExpectedException extends PhpObjectException
{
    /** @param mixed $value */
    public static function createDefaultMessage(string $name, $value): string
    {
        return "Variable \"\$$name\" should be a valid resource but is of type " . VariableUtils::getType($value) . '.';
    }
}
