<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\File\FileUtils;

use PhpObject\Core\{
    Exception\File\FileNotFoundException,
    File\FileUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class GetInodeUpdatedAtMethodTest extends AbstractTestCase
{
    public function testFileExists(): void
    {
        /** @var \DateTimeImmutable $lastAccess */
        $lastAccess = $this->callPhpObjectMethod(
            function (): \DateTimeImmutable {
                return FileUtils::getInodeUpdatedAt(__FILE__);
            }
        );

        static::assertSame(fileatime(__FILE__), $lastAccess->getTimestamp());
    }

    public function testFileNotFound(): void
    {
        $this->assertExceptionIsThrowned(
            function (): void {
                FileUtils::getInodeUpdatedAt(__FILE__ . '/foo');
            },
            FileNotFoundException::class,
            'File "' . __FILE__ . '/foo" not found.'
        );
    }
}
