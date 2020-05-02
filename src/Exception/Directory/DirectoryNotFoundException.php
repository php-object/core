<?php

declare(strict_types=1);

namespace PhpObject\Core\Exception\Directory;

use PhpObject\Core\Exception\PhpObjectException;

class DirectoryNotFoundException extends PhpObjectException
{
    public static function createDefaultMessage(string $directory): string
    {
        return "Directory \"$directory\" not found.";
    }
}
