<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit;

use PhpObject\Core\{
    ErrorHandler\PhpError,
    Exception\PhpObjectException
};
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    /** @param class-string $phpErrorClass */
    public static function assertPhpErrorException(
        PhpObjectException $exception,
        string $exceptionError,
        int $exceptionCode,
        int $phpErrorNumber,
        string $phpErrorError,
        string $phpErrorClass,
        int $phpErrorLine
    ): void {
        static::assertEquals($exceptionError, $exception->getMessage());
        static::assertEquals($exceptionCode, $exception->getCode());

        static::assertInstanceOf(PhpError::class, $exception->getPhpError());
        static::assertSame($phpErrorNumber, $exception->getPhpError()->getNumber());
        static::assertSame($phpErrorError, $exception->getPhpError()->getError());
        static::assertSame(
            (new \ReflectionClass($phpErrorClass))->getFileName(),
            $exception->getPhpError()->getFile()
        );
        static::assertSame($phpErrorLine, $exception->getPhpError()->getLine());
    }

    public static function assertNoPhpError(?PhpError $phpError): void
    {
        static::assertNull($phpError, 'A PHP error has been triggered but no one was expected.');
    }

    /** @var PhpError|null */
    protected $lastPhpError;

    /** @var bool */
    protected $testErrorHandledEnabled = false;

    /** @param array<mixed>|null $context */
    public function onPhpError(
        int $number,
        string $error,
        string $file = null,
        int $line = null,
        array $context = null
    ): bool {
        $this->lastPhpError = new PhpError($number, $error, $file, $line, $context);

        return false;
    }

    protected function assertExceptionThrowned(bool $throwned, string $class): self
    {
        if ($throwned === false) {
            $this->disableTestErrorHandler();
            static::fail("$class exception has not been throwned.");
        }

        return $this;
    }

    protected function enableTestErrorHandler(): self
    {
        set_error_handler([$this, 'onPhpError']);
        $this->lastPhpError = null;
        $this->testErrorHandledEnabled = true;

        return $this;
    }

    protected function getLastPhpError(): ?PhpError
    {
        return $this->lastPhpError;
    }

    protected function disableTestErrorHandler(): self
    {
        if ($this->testErrorHandledEnabled === true) {
            restore_error_handler();
            $this->testErrorHandledEnabled = false;
        }

        return $this;
    }

    protected function isPhp71(): bool
    {
        return version_compare(PHP_VERSION, '7.1.0') === 0;
    }

    protected function isPhp72(): bool
    {
        return version_compare(PHP_VERSION, '7.2.0') === 0;
    }

    protected function isPhp73(): bool
    {
        return version_compare(PHP_VERSION, '7.3.0') === 0;
    }

    protected function isPhp74(): bool
    {
        return version_compare(PHP_VERSION, '7.4.0') === 0;
    }
}
