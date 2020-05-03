<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryManager;

use PhpObject\Core\{
    Directory\DirectoryManager,
    Tests\PhpUnit\AbstractTestCase
};

class IsOpenMethodTest extends AbstractTestCase
{
    public function testOpen(): void
    {
        /** @var DirectoryManager $manager */
        $manager = $this->callPhpObjectMethod(
            function (): DirectoryManager {
                return DirectoryManager::createFromPath(__DIR__);
            }
        );

        static::assertTrue($manager->isOpen());

        $manager->close();
    }

    public function testClosed(): void
    {
        /** @var DirectoryManager $manager */
        $manager = $this->callPhpObjectMethod(
            function (): DirectoryManager {
                return DirectoryManager::createFromPath(__DIR__);
            }
        );

        static::assertTrue($manager->isOpen());
        $manager->close();
        static::assertFalse($manager->isOpen());
    }
}
