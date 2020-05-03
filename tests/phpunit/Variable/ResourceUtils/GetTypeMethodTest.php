<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Variable\ResourceUtils;

use PhpObject\Core\{
    Exception\Variable\ResourceExpectedException,
    Tests\PhpUnit\AbstractTestCase,
    Variable\ResourceUtils
};

class GetTypeMethodTest extends AbstractTestCase
{
    public function testFile(): void
    {
        $resource = fopen(__FILE__, 'r');
        if (is_resource($resource) === false) {
            static::fail('Unable to open ' . __FILE__ . ' for reading.');
        }

        $result = $this->callPhpObjectMethod(
            function () use ($resource): string {
                return ResourceUtils::getType($resource);
            }
        );

        fclose($resource);

        static::assertSame('stream', $result);
    }

    public function testInvalidType(): void
    {
        $this->assertExceptionIsThrowned(
            function (): string {
                return ResourceUtils::getType('foo');
            },
            ResourceExpectedException::class,
            'Variable "$resource" should be a valid resource but is of type string.'
        );
    }
}
