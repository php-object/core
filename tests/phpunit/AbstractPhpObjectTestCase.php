<?php

declare(strict_types=1);

namespace PhpObject\Tests\PhpUnit;

use PhpObject\ErrorHandler\PhpObjectErrorHandlerUtils;
use PHPUnit\Framework\TestCase;

abstract class AbstractPhpObjectTestCase extends TestCase
{
    public static function assertLastPhpError(
        array $lastError,
        int $number,
        string $error,
        string $file = null,
        int $line = null
    ): void {
        if (count($lastError) === 0) {
            static::fail('No PHP error was triggered but "' . $error . '" is expected.');
        }

        static::assertEquals($number, $lastError['number'] ?? null);
        static::assertEquals($error, $lastError['error'] ?? null);
        static::assertEquals($file, $lastError['file'] ?? null);
        static::assertEquals($line, $lastError['line'] ?? null);
    }

    public static function assertNoPhpError(array $lastError): void
    {
        static::assertCount(
            0,
            $lastError,
            'PHP error "' . ($lastError['error'] ?? null) . '" has been triggered but no one expected.'
        );
    }

    /** @var array */
    protected $lastError = [];

    public function setLastPhpError(int $number, string $error, string $file = null, int $line = null): self
    {
        $this->lastError = [
            'number' => $number,
            'error' => $error,
            'file' => $file,
            'line' => $line
        ];

        return $this;
    }

    protected function getLastPhpError(): array
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
