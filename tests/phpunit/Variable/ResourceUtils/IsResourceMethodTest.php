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
        $handle = fopen(__FILE__, 'r');
        if (is_resource($handle) === false) {
            static::fail('Unable to open ' . __FILE__ . ' for reading.');
        }

        $result = $this->callPhpObjectMethod(
            function () use ($handle): bool {
                return ResourceUtils::isResource($handle);
            }
        );

        fclose($handle);

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
        $handle = fopen(__FILE__, 'r');
        if (is_resource($handle) === false) {
            static::fail('Unable to open ' . __FILE__ . ' for reading.');
        }
        fclose($handle);

        $result = $this->callPhpObjectMethod(
            function () use ($handle): bool {
                return ResourceUtils::isResource($handle);
            }
        );
        static::assertFalse($result);
    }
}
