<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Variable\VariableUtils;

use PhpObject\Core\{
    Tests\PhpUnit\AbstractTestCase,
    Variable\VariableUtils,
    Version\PhpVersionUtils
};

class GetTypeMethodTest extends AbstractTestCase
{
    public function testTrue(): void
    {
        $this->assertGetType(true, VariableUtils::TYPE_BOOLEAN);
    }

    public function testFalse(): void
    {
        $this->assertGetType(false, VariableUtils::TYPE_BOOLEAN);
    }

    public function testInt(): void
    {
        $this->assertGetType(42, VariableUtils::TYPE_INTEGER);
    }

    public function testDouble(): void
    {
        $this->assertGetType(42.0, VariableUtils::TYPE_DOUBLE);
    }

    public function testString(): void
    {
        $this->assertGetType('foo', VariableUtils::TYPE_STRING);
    }

    public function testArray(): void
    {
        $this->assertGetType([], VariableUtils::TYPE_ARRAY);
    }

    public function testObject(): void
    {
        $this->assertGetType(new \stdClass(), VariableUtils::TYPE_OBJECT);
    }

    public function testResource(): void
    {
        $resource = fopen(__FILE__, 'r');
        if (is_resource($resource) === false) {
            static::fail('Unable to open ' . __FILE__ . ' for reading.');
        }

        $this->assertGetType($resource, VariableUtils::TYPE_RESOURCE);
        fclose($resource);
    }

    public function testClosedResource(): void
    {
        $resource = fopen(__FILE__, 'r');
        if (is_resource($resource) === false) {
            static::fail('Unable to open ' . __FILE__ . ' for reading.');
        }
        fclose($resource);

        if (PhpVersionUtils::is71() === true) {
            $this->assertGetType($resource, VariableUtils::TYPE_UNKNOWN_TYPE);
        } else {
            $this->assertGetType($resource, VariableUtils::TYPE_CLOSED_RESOURCE);
        }
    }

    public function testNull(): void
    {
        $this->assertGetType(null, VariableUtils::TYPE_NULL);
    }

    /** @param mixed $value */
    private function assertGetType($value, string $type): self
    {
        $result = $this->callPhpObjectMethod(
            function () use ($value): string {
                return VariableUtils::getType($value);
            }
        );
        static::assertSame($type, $result);

        return $this;
    }
}
