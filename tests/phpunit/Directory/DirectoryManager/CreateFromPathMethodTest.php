<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryManager;

use PhpObject\Core\{
    Directory\DirectoryManager,
    Exception\Directory\DirectoryNotFoundException,
    Tests\PhpUnit\AbstractTestCase
};

class CreateFromPathMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        /** @var DirectoryManager $manager */
        $manager = $this->callPhpObjectMethod(
            function (): DirectoryManager {
                return DirectoryManager::createFromPath(__DIR__);
            }
        );
        static::assertIsOpenResource($manager->getResource());
    }

    public function testDirectoryNotFound(): void
    {
        $this->assertExceptionIsThrowned(
            function (): void {
                DirectoryManager::createFromPath('/foo');
            },
            DirectoryNotFoundException::class,
            'Directory "/foo" not found.'
        );
    }

    public function testExistingSymbolicLink(): void
    {
        $symbolicLink = $this->getTemporaryPath();
        symlink(__DIR__, $symbolicLink);

        /** @var DirectoryManager $manager */
        $manager = $this->callPhpObjectMethod(
            function () use ($symbolicLink): DirectoryManager {
                return DirectoryManager::createFromPath($symbolicLink);
            }
        );
        static::assertIsOpenResource($manager->getResource());
    }

    public function testSymbolicLinkNotFound(): void
    {
        $symbolicLink = $this->getTemporaryPath();
        symlink(__DIR__ . '/foo', $symbolicLink);

        $this->assertExceptionIsThrowned(
            function () use ($symbolicLink): void {
                DirectoryManager::createFromPath($symbolicLink);
            },
            DirectoryNotFoundException::class,
            "Directory \"$symbolicLink\" not found."
        );
    }
}
