<?php

declare(strict_types=1);

namespace PhpObject\Core\Exception\ErrorHandler;

use PhpObject\Core\{
    ErrorHandler\PhpError,
    Exception\PhpObjectException
};

class PhpErrorException extends PhpObjectException
{
    public function __construct(PhpError $phpError, int $code = 0, \Throwable $previous = null)
    {
        parent::__construct('A PHP error has been triggered.', $phpError, $code, $previous);
    }
}
