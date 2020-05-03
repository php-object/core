<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit;

use PhpObject\Core\{
    ErrorHandler\PhpError,
    Exception\PhpObjectException,
    Variable\ResourceUtils
};
use PHPUnit\Framework\TestCase;

abstract class AbstractTestCase extends TestCase
{
    protected static function assertException(\Throwable $exception, string $class, string $error, int $code = 0): void
    {
        static::assertInstanceOf($class, $exception);
        static::assertEquals($error, $exception->getMessage());
        static::assertEquals($code, $exception->getCode());
    }

    /** @param class-string $class */
    protected static function assertExceptionWithPhpError(
        PhpObjectException $exception,
        int $number,
        string $error,
        string $class,
        int $line
    ): void {
        static::assertInstanceOf(PhpError::class, $exception->getPhpError());
        static::assertSame($number, $exception->getPhpError()->getNumber());
        static::assertSame($error, $exception->getPhpError()->getError());
        static::assertSame(
            (new \ReflectionClass($class))->getFileName(),
            $exception->getPhpError()->getFile()
        );
        static::assertSame($line, $exception->getPhpError()->getLine());
    }

    protected static function assertExceptionWithoutPhpError(PhpObjectException $exception): void
    {
        static::assertNull($exception->getPhpError(), 'Exception contains a PHP error but no one was expected.');
    }

    protected static function assertNoPhpError(?PhpError $phpError): void
    {
        static::assertNull($phpError, 'A PHP error has been triggered but no one was expected.');
    }

    /** @param mixed $resource */
    protected static function assertIsOpenResource($resource): void
    {
        static::assertTrue(ResourceUtils::isOpen($resource), 'Resource is not closed.');
    }

    /** @param mixed $resource */
    protected static function assertIsClosedResource($resource): void
    {
        static::assertTrue(ResourceUtils::isClosed($resource), 'Resource is not closed.');
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

    protected function assertExceptionIsThrowned(
        callable $callable,
        string $exceptionClass,
        string $exceptionError
    ): self {
        $exceptionThrowned = false;
        $this->enableTestErrorHandler();

        try {
            call_user_func($callable);
        } catch (\Throwable $exception) {
            $this->disableTestErrorHandler();

            static::assertException($exception, $exceptionClass, $exceptionError);
            if ($exception instanceof PhpObjectException) {
                static::assertExceptionWithoutPhpError($exception);
            }

            $exceptionThrowned = true;
        }

        $this->disableTestErrorHandler();

        if ($exceptionThrowned === false) {
            static::fail("$exceptionClass exception has not been throwned.");
        }
        static::assertNoPhpError($this->getLastPhpError());

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

    /** @return mixed */
    protected function callPhpObjectMethod(callable $callable)
    {
        $this->enableTestErrorHandler();
        $return = call_user_func($callable);
        $this->disableTestErrorHandler();

        static::assertNoPhpError($this->getLastPhpError());

        return $return;
    }

    protected function getTemporaryDirectory(): string
    {
        return sys_get_temp_dir() . '/' . uniqid('php-object-phpunit-');
    }
}
