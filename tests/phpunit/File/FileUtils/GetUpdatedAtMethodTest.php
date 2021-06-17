<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\File\FileUtils;

use PhpObject\Core\{
    Exception\File\FileNotFoundException,
    File\FileUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class GetUpdatedAtMethodTest extends AbstractTestCase
{
    public function testFileExists(): void
    {
        /** @var \DateTimeImmutable $updatedAt */
        $updatedAt = $this->callPhpObjectMethod(
            function (): \DateTimeImmutable {
                return FileUtils::getUpdatedAt(__FILE__);
            }
        );

        static::assertSame(filemtime(__FILE__), $updatedAt->getTimestamp());
    }

    public function testFileNotFound(): void
    {
        $this->assertExceptionIsThrowned(
            function (): void {
                FileUtils::getUpdatedAt(__FILE__ . '/foo');
            },
            FileNotFoundException::class,
            'File "' . __FILE__ . '/foo" not found.'
        );
    }
}
