<?php

declare(strict_types=1);

namespace PhpObject\Tests\PhpUnit;

use PhpObject\ErrorHandler\PhpObjectErrorHandlerUtils;
use PHPUnit\Framework\TestCase;

abstract class AbstractPhpObjectTestCase extends TestCase
{
    public static function assertLastError(
        array $lastError,
        int $number,
        string $error,
        string $file = null,
        int $line = null
    ): void {
        if (count($lastError) === 0) {
            static::fail('No PHP error was triggered.');
        }

        static::assertEquals($number, $lastError['number']);
        static::assertEquals($error, $lastError['error']);
        static::assertEquals($file, $lastError['file']);
        static::assertEquals($line, $lastError['line']);
    }

    /** @var array */
    protected $lastError = [];

    public function setLastError(int $number, string $error, string $file = null, int $line = null): self
    {
        $this->lastError = [
            'number' => $number,
            'error' => $error,
            'file' => $file,
            'line' => $line
        ];

        return $this;
    }

    protected function getLastError(): array
    {
        return $this->lastError;
    }

    protected function enableTestErrorHandler(): self
    {
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(false);
        TestErrorHandler::enable($this);
        $this->lastError = [];

        return $this;
    }

    protected function disableTestErrorHandler(): self
    {
        TestErrorHandler::disable();
        PhpObjectErrorHandlerUtils::setDisableCustomErrorHandler(true);

        return $this;
    }

    protected function resetLastError(): self
    {
        $this->lastError = [];

        return $this;
    }
}
