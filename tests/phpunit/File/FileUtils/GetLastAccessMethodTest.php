<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\File\FileUtils;

use PhpObject\Core\{
    Exception\File\FileNotFoundException,
    File\FileUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class GetLastAccessMethodTest extends AbstractTestCase
{
    public function testFileExists(): void
    {
        /** @var \DateTimeImmutable $lastAccess */
        $lastAccess = $this->callPhpObjectMethod(
            function (): \DateTimeImmutable {
                return FileUtils::getLastAccess(__FILE__);
            }
        );

        static::assertSame(fileatime(__FILE__), $lastAccess->getTimestamp());
    }

    public function testFileNotFound(): void
    {
        $this->assertExceptionIsThrowned(
            function (): void {
                FileUtils::getLastAccess(__FILE__ . '/foo');
            },
            FileNotFoundException::class,
            'File "' . __FILE__ . '/foo" not found.'
        );
    }
}
