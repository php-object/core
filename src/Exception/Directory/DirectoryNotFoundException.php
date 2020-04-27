<?php

declare(strict_types=1);

namespace PhpObject\Exception\Directory;

use PhpObject\Exception\PhpObjectException;

class DirectoryNotFoundException extends PhpObjectException
{
    public function __construct(string $directory, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct("Directory \"$directory\" not found.", $code, $previous);
    }
}
