<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryManager;

use PhpObject\Core\{
    Directory\DirectoryManager,
    Tests\PhpUnit\AbstractTestCase
};

class GetResourceMethodTest extends AbstractTestCase
{
    public function testIsOpen(): void
    {
        /** @var DirectoryManager $manager */
        $manager = $this->callPhpObjectMethod(
            function (): DirectoryManager {
                return DirectoryManager::createFromPath(__DIR__);
            }
        );

        static::assertIsOpenResource($manager->getResource());

        $manager->close();
    }

    public function testIsClosed(): void
    {
        /** @var DirectoryManager $manager */
        $manager = $this->callPhpObjectMethod(
            function (): DirectoryManager {
                return DirectoryManager::createFromPath(__DIR__);
            }
        );

        static::assertIsOpenResource($manager->getResource());
        $manager->close();
        static::assertIsClosedResource($manager->getResource());
    }
}
