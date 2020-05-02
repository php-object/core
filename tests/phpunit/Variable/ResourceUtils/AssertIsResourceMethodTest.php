<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Variable\ResourceUtils;

use PhpObject\Core\{
    Exception\Variable\ResourceExpectedException,
    Tests\PhpUnit\AbstractTestCase,
    Variable\ResourceUtils
};

final class AssertIsResourceMethodTest extends AbstractTestCase
{
    public function testIsResource(): void
    {
        $handle = fopen(__FILE__, 'r');

        $this->callPhpObjectMethod(
            function () use ($handle): void {
                ResourceUtils::assertIsResource(
                    $handle,
                    ResourceExpectedException::createDefaultMessage('handle', $handle)
                );
            }
        );
        $this->addToAssertionCount(1);
    }

    public function testNotResource(): void
    {
        $handle = 'foo';

        $this->assertExceptionIsThrowned(
            function () use ($handle): void {
                ResourceUtils::assertIsResource(
                    $handle,
                    ResourceExpectedException::createDefaultMessage('handle', $handle)
                );
            },
            ResourceExpectedException::class,
            'Variable "$handle" should be a valid resource but is of type string.'
        );
    }

    public function testClosedResource(): void
    {
        $handle = fopen(__FILE__, 'r');
        if (is_resource($handle) === false) {
            static::fail('Unable to open ' . __FILE__ . ' for reading.');
        }
        fclose($handle);

        $this->assertExceptionIsThrowned(
            function () use ($handle): void {
                ResourceUtils::assertIsResource(
                    $handle,
                    ResourceExpectedException::createDefaultMessage('handle', $handle)
                );
            },
            ResourceExpectedException::class,
            $this->isPhp71()
                ? 'Variable "$handle" should be a valid resource but is of type unknown type.'
                : 'Variable "$handle" should be a valid resource but is of type resource (closed).'
        );
    }
}
