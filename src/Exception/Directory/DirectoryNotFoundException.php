<?php

declare(strict_types=1);

namespace PhpObject\Core\Exception\Directory;

use PhpObject\Core\{
    ErrorHandler\PhpError,
    Exception\PhpObjectException
};

class DirectoryNotFoundException extends PhpObjectException
{
    public function __construct(
        string $directory,
        PhpError $phpError = null,
        int $code = 0,
        \Throwable $previous = null
    ) {
        parent::__construct("Directory \"$directory\" not found.", $phpError, $code, $previous);
    }
}
