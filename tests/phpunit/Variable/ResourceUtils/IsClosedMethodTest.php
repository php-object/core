<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Variable\ResourceUtils;

use PhpObject\Core\{
    Exception\Variable\ResourceExpectedException,
    Tests\PhpUnit\AbstractTestCase,
    Variable\ResourceUtils
};

class IsClosedMethodTest extends AbstractTestCase
{
    public function testOpen(): void
    {
        $resource = fopen(__FILE__, 'r');
        if (is_resource($resource) === false) {
            static::fail('Unable to open ' . __FILE__ . ' for reading.');
        }

        $result = $this->callPhpObjectMethod(
            function () use ($resource): bool {
                return ResourceUtils::isClosed($resource);
            }
        );

        fclose($resource);

        static::assertFalse($result);
    }

    public function testClosed(): void
    {
        $resource = fopen(__FILE__, 'r');
        if (is_resource($resource) === false) {
            static::fail('Unable to open ' . __FILE__ . ' for reading.');
        }
        fclose($resource);

        $result = $this->callPhpObjectMethod(
            function () use ($resource): bool {
                return ResourceUtils::isClosed($resource);
            }
        );

        static::assertTrue($result);
    }

    public function testInvalidType(): void
    {
        $this->assertExceptionIsThrowned(
            function (): bool {
                return ResourceUtils::isClosed('foo');
            },
            ResourceExpectedException::class,
            'Variable "$resource" should be a valid resource but is of type string.'
        );
    }
}
