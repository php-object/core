<?php

declare(strict_types=1);

namespace PhpObject\Core\Exception\DateTime;

use PhpObject\Core\Exception\PhpObjectException;

class InvalidTimestampException extends PhpObjectException
{
    public function __construct(int $timestamp)
    {
        parent::__construct('Invalid timestamp "' . $timestamp . '".');
    }
}
