<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\DateTime\TimestampUtils;

use PhpObject\Core\{
    DateTime\TimestampUtils,
    Exception\DateTime\InvalidTimestampException,
    Tests\PhpUnit\AbstractTestCase
};

final class AssertValidMethodTest extends AbstractTestCase
{
    public function test0(): void
    {
        $this->callPhpObjectMethod(
            function (): void {
                TimestampUtils::assertValid(0);
            }
        );

        static::addToAssertionCount(1);
    }

    public function test1(): void
    {
        $this->callPhpObjectMethod(
            function (): void {
                TimestampUtils::assertValid(1);
            }
        );

        static::addToAssertionCount(1);
    }

    public function testNegative1(): void
    {
        $this->assertExceptionIsThrowned(
            function (): void {
                TimestampUtils::assertValid(-1);
            },
            InvalidTimestampException::class,
            'Invalid timestamp "-1".'
        );
    }
}
