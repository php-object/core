<?php

declare(strict_types=1);

namespace PhpObject\Core\ErrorHandler;

class PhpError
{
    /** @var int */
    protected $number;

    /** @var string */
    protected $error;

    /** @var string|null */
    protected $file;

    /** @var int|null */
    protected $line;

    /** @var array<mixed>|null */
    protected $context;

    /** @param array<mixed>|null $context */
    public function __construct(int $number, string $error, ?string $file, ?int $line, ?array $context)
    {
        $this->number = $number;
        $this->error = $error;
        $this->file = $file;
        $this->line = $line;
        $this->context = $context;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getNumberToString(): string
    {
        switch ($this->getNumber()) {
            case E_PARSE:
                $return = 'E_PARSE';
                break;
            case E_NOTICE:
                $return = 'E_NOTICE';
                break;
            case E_WARNING:
                $return = 'E_WARNING';
                break;
            case E_ERROR:
                $return = 'E_ERROR';
                break;
            case E_DEPRECATED:
                $return = 'E_DEPRECATED';
                break;
            case E_STRICT:
                $return = 'E_STRICT';
                break;
            case E_USER_NOTICE:
                $return = 'E_USER_NOTICE';
                break;
            case E_USER_WARNING:
                $return = 'E_USER_WARNING';
                break;
            case E_USER_ERROR:
                $return = 'E_USER_ERROR';
                break;
            case E_USER_DEPRECATED:
                $return = 'E_USER_DEPRECATED';
                break;
            case E_COMPILE_WARNING:
                $return = 'E_COMPILE_WARNING';
                break;
            case E_COMPILE_ERROR:
                $return = 'E_COMPILE_ERROR';
                break;
            case E_CORE_WARNING:
                $return = 'E_CORE_WARNING';
                break;
            case E_CORE_ERROR:
                $return = 'E_CORE_ERROR';
                break;
            case E_RECOVERABLE_ERROR:
                $return = 'E_RECOVERABLE_ERROR';
                break;
            default:
                $return = 'UNKNOWN';
                break;
        }

        return $return;
    }

    public function getError(): string
    {
        return $this->error;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function getLine(): ?int
    {
        return $this->line;
    }

    /** @return array<mixed>|null */
    public function getContext(): ?array
    {
        return $this->context;
    }
}
