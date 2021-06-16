<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\DateTime\DateTimeImmutableUtils;

use PhpObject\Core\{
    DateTime\DateTimeImmutableUtils,
    Exception\DateTime\InvalidTimestampException,
    Tests\PhpUnit\AbstractTestCase
};

final class CreateFromTimestampMethodTest extends AbstractTestCase
{
    public function test0(): void
    {
        /** @var \DateTimeImmutable $result */
        $result = $this->callPhpObjectMethod(
            function (): \DateTimeImmutable {
                return DateTimeImmutableUtils::createFromTimestamp(0);
            }
        );

        static::assertSame(0, $result->getTimestamp());
    }

    public function test1(): void
    {
        /** @var \DateTimeImmutable $result */
        $result = $this->callPhpObjectMethod(
            function (): \DateTimeImmutable {
                return DateTimeImmutableUtils::createFromTimestamp(1);
            }
        );

        static::assertSame(1, $result->getTimestamp());
    }

    public function testNegative1(): void
    {
        $this->assertExceptionIsThrowned(
            function (): void {
                DateTimeImmutableUtils::createFromTimestamp(-1);
            },
            InvalidTimestampException::class,
            'Invalid timestamp "-1".'
        );
    }
}
