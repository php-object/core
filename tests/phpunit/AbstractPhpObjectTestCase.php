<?php

declare(strict_types=1);

namespace PhpObject\Tests\PhpUnit;

use PHPUnit\Framework\TestCase;

abstract class AbstractPhpObjectTestCase extends TestCase
{
    /** @param array<int|string|null> $lastError */
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

    /** @param array<int|string|null> $lastError */
    public static function assertNoPhpError(array $lastError): void
    {
        static::assertCount(
            0,
            $lastError,
            'PHP error "' . ($lastError['error'] ?? null) . '" has been triggered but no one expected.'
        );
    }

    /** @var array<int|string|null> */
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

    /** @return array<int|string|null> */
    protected function getLastPhpError(): array
    {
        return $this->lastError;
    }

    protected function enableTestErrorHandler(): self
    {
        TestErrorHandler::enable($this);
        $this->resetLastError();

        return $this;
    }

    protected function disableTestErrorHandler(): self
    {
        TestErrorHandler::disable();

        return $this;
    }

    protected function resetLastError(): self
    {
        $this->lastError = [];

        return $this;
    }
}
