<?php

declare(strict_types=1);

namespace PhpObject\Core\Exception;

use PhpObject\Core\ErrorHandler\PhpError;

class PhpObjectException extends \Exception
{
    /** @var PhpError|null */
    protected $phpError;

    public function __construct(string $message, PhpError $phpError = null, int $code = 0, \Throwable $previous = null)
    {
        $this->phpError = $phpError;

        parent::__construct($this->getFinalMessage($message, $phpError), $code, $previous);
    }

    public function getPhpError(): ?PhpError
    {
        return $this->phpError;
    }

    public function __toString(): string
    {
        return $this->getMessage();
    }

    protected function getFinalMessage(string $message, ?PhpError $phpError): string
    {
        $return = $message;

        if ($phpError instanceof PhpError && PhpObjectExceptionUtils::getAddPhpErrorToMessage() === true) {
            $return .=
                ' PHP error: '
                . $phpError->getNumberToString() . ' (' . $phpError->getNumber() . ')'
                . ' ' . $phpError->getError();
            if (is_string($phpError->getFile())) {
                $return .= ', file: ' . $phpError->getFile();
            }
            if (is_int($phpError->getLine())) {
                $return .= ', line: ' . $phpError->getLine();
            }
            $return .= '.';
        }

        return $return;
    }
}
