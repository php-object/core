<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryManager;

use PhpObject\Core\{
    Directory\DirectoryManager,
    Tests\PhpUnit\AbstractTestCase
};

class DestructMethodTest extends AbstractTestCase
{
    public function testOpenResource(): void
    {
        /** @var DirectoryManager $manager */
        $manager = $this->callPhpObjectMethod(
            function (): DirectoryManager {
                return DirectoryManager::createFromPath(__DIR__);
            }
        );

        $resource = $manager->getResource();
        static::assertIsOpenResource($resource);
        unset($manager);
        static::assertIsClosedResource($resource);
    }

    public function testClosedResource(): void
    {
        /** @var DirectoryManager $manager */
        $manager = $this->callPhpObjectMethod(
            function (): DirectoryManager {
                return DirectoryManager::createFromPath(__DIR__);
            }
        );

        $resource = $manager->getResource();
        static::assertIsOpenResource($resource);
        $manager->close();
        static::assertIsClosedResource($resource);
        unset($manager);
        static::assertIsClosedResource($resource);
    }
}
