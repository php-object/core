<?php

declare(strict_types=1);

namespace PhpObject\Core\Exception\Variable;

use PhpObject\Core\Exception\PhpObjectException;

class ResourceIsClosedException extends PhpObjectException
{
    public static function createDefaultMessage(string $name): string
    {
        return "Variable \"\$$name\" should be a valid open resource but is a closed resource.";
    }
}
