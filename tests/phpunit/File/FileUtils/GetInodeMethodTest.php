<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\File\FileUtils;

use PhpObject\Core\{
    Exception\File\FileNotFoundException,
    File\FileUtils,
    Tests\PhpUnit\AbstractTestCase
};

final class GetInodeMethodTest extends AbstractTestCase
{
    public function testFileExists(): void
    {
        $inode = $this->callPhpObjectMethod(
            function (): int {
                return FileUtils::getInode(__FILE__);
            }
        );

        static::assertSame(fileinode(__FILE__), $inode);
    }

    public function testFileNotFound(): void
    {
        $this->assertExceptionIsThrowned(
            function (): void {
                FileUtils::getInode(__FILE__ . '/foo');
            },
            FileNotFoundException::class,
            'File "' . __FILE__ . '/foo" not found.'
        );
    }
}
