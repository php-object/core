<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Variable\ResourceUtils;

use PhpObject\Core\{Exception\Variable\ResourceExpectedException,
    Tests\PhpUnit\AbstractTestCase,
    Variable\ResourceUtils,
    Version\PhpVersionUtils};

final class AssertIsResourceMethodTest extends AbstractTestCase
{
    public function testIsResource(): void
    {
        $resource = fopen(__FILE__, 'r');

        $this->callPhpObjectMethod(
            function () use ($resource): void {
                ResourceUtils::assertIsResource(
                    $resource,
                    ResourceExpectedException::createDefaultMessage('resource', $resource)
                );
            }
        );
        $this->addToAssertionCount(1);
    }

    public function testNotResource(): void
    {
        $resource = 'foo';

        $this->assertExceptionIsThrowned(
            function () use ($resource): void {
                ResourceUtils::assertIsResource(
                    $resource,
                    ResourceExpectedException::createDefaultMessage('resource', $resource)
                );
            },
            ResourceExpectedException::class,
            'Variable "$resource" should be a valid resource but is of type string.'
        );
    }

    public function testClosedResource(): void
    {
        $resource = fopen(__FILE__, 'r');
        if (is_resource($resource) === false) {
            static::fail('Unable to open ' . __FILE__ . ' for reading.');
        }
        fclose($resource);

        $this->assertExceptionIsThrowned(
            function () use ($resource): void {
                ResourceUtils::assertIsResource(
                    $resource,
                    ResourceExpectedException::createDefaultMessage('resource', $resource)
                );
            },
            ResourceExpectedException::class,
            PhpVersionUtils::is71()
                ? 'Variable "$resource" should be a valid resource but is of type unknown type.'
                : 'Variable "$resource" should be a valid resource but is of type resource (closed).'
        );
    }
}
