<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Variable\ResourceUtils;

use PhpObject\Core\{
    Tests\PhpUnit\AbstractTestCase,
    Variable\ResourceUtils
};

class IsResourceMethodTest extends AbstractTestCase
{
    public function testTrue(): void
    {
        $resource = fopen(__FILE__, 'r');
        if (is_resource($resource) === false) {
            static::fail('Unable to open ' . __FILE__ . ' for reading.');
        }

        $result = $this->callPhpObjectMethod(
            function () use ($resource): bool {
                return ResourceUtils::isResource($resource);
            }
        );

        fclose($resource);

        static::assertTrue($result);
    }

    public function testFalse(): void
    {
        $result = $this->callPhpObjectMethod(
            function (): bool {
                return ResourceUtils::isResource('foo');
            }
        );
        static::assertFalse($result);
    }

    public function testClosedResource(): void
    {
        $resource = fopen(__FILE__, 'r');
        if (is_resource($resource) === false) {
            static::fail('Unable to open ' . __FILE__ . ' for reading.');
        }
        fclose($resource);

        $result = $this->callPhpObjectMethod(
            function () use ($resource): bool {
                return ResourceUtils::isResource($resource);
            }
        );
        static::assertFalse($result);
    }
}
