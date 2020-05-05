<?php

declare(strict_types=1);

namespace PhpObject\Core\Exception\File;

use PhpObject\Core\Exception\PhpObjectException;

class FileNotFoundException extends PhpObjectException
{
    public static function createDefaultMessage(string $file): string
    {
        return "File \"$file\" not found.";
    }
}
