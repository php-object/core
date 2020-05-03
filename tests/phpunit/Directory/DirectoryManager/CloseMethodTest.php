<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryManager;

use PhpObject\Core\{
    Directory\DirectoryManager,
    Exception\Variable\ResourceIsClosedException,
    Tests\PhpUnit\AbstractTestCase
};

class CloseMethodTest extends AbstractTestCase
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
        $manager->close();
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

        $this->assertExceptionIsThrowned(
            function () use ($manager): void {
                $manager->close();
            },
            ResourceIsClosedException::class,
            'Variable "$resource" should be a valid open resource but is a closed resource.'
        );
    }
}
