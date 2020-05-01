<?php

declare(strict_types=1);

namespace PhpObject\Core\Exception;

use PhpObject\Core\ErrorHandler\PhpError;

class PhpObjectException extends \Exception
{
    /** @var bool */
    protected static $addPhpErrorToMessage = false;

    public static function setAddPhpErrorToMessage(bool $add): void
    {
        static::$addPhpErrorToMessage = $add;
    }

    /** @var PhpError|null */
    protected $phpError;

    public function __construct(string $message, PhpError $phpError = null, int $code = 0, \Throwable $previous = null)
    {
        if ($phpError instanceof PhpError && static::$addPhpErrorToMessage === true) {
            $message .=
                ' PHP error: '
                . $phpError->getNumberToString() . ' (' . $phpError->getNumber() . ')'
                . ' ' . $phpError->getError();
            if (is_string($phpError->getFile())) {
                $message .= ', file: ' . $phpError->getFile();
            }
            if (is_int($phpError->getLine())) {
                $message .= ', line: ' . $phpError->getLine();
            }
            $message .= '.';
        }

        $this->phpError = $phpError;

        parent::__construct($message, $code, $previous);
    }

    public function getPhpError(): ?PhpError
    {
        return $this->phpError;
    }
}
