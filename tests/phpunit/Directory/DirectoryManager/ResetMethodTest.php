<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryManager;

use PhpObject\Core\{
    Directory\DirectoryManager,
    Tests\PhpUnit\AbstractTestCase
};

class ResetMethodTest extends AbstractTestCase
{
    public function testExistingDirectory(): void
    {
        $directory = $this->getTemporaryPath();
        mkdir($directory);
        touch("$directory/a.file");
        touch("$directory/b.file");
        touch("$directory/c.file");

        /** @var DirectoryManager $manager */
        $manager = $this->callPhpObjectMethod(
            function () use ($directory): DirectoryManager {
                return DirectoryManager::createFromPath($directory);
            }
        );
        static::assertIsOpenResource($manager->getResource());

        $this->assertItemsFound($manager);

        $result = $this->callPhpObjectMethod(
            function () use ($manager): DirectoryManager {
                return $manager->reset();
            }
        );
        static::assertInstanceOf(DirectoryManager::class, $result);

        $this->assertItemsFound($manager);

        $manager->close();
    }

    public function testExistingSymbolicLink(): void
    {
        $directory = $this->getTemporaryPath();
        mkdir($directory);
        touch("$directory/a.file");
        touch("$directory/b.file");
        touch("$directory/c.file");
        $symbolicLink = $this->getTemporaryPath();
        symlink($directory, $symbolicLink);

        /** @var DirectoryManager $manager */
        $manager = $this->callPhpObjectMethod(
            function () use ($symbolicLink): DirectoryManager {
                return DirectoryManager::createFromPath($symbolicLink);
            }
        );
        static::assertIsOpenResource($manager->getResource());

        $this->assertItemsFound($manager);

        $result = $this->callPhpObjectMethod(
            function () use ($manager): DirectoryManager {
                return $manager->reset();
            }
        );
        static::assertInstanceOf(DirectoryManager::class, $result);

        $this->assertItemsFound($manager);

        $manager->close();
    }

    private function assertItemsFound(DirectoryManager $directoryManager): self
    {
        // readdir() return order is OS dependant
        // Although PHPUnit is dockerised, I have different behavior between local and CircleCI
        $expectedItems = ['.', '..', 'a.file', 'b.file', 'c.file', null];
        $itemsFound = [];
        for ($i = 0; $i < count($expectedItems); $i++) {
            $itemsFound[] = $this->callPhpObjectMethod(
                function () use ($directoryManager): ?string {
                    return $directoryManager->getNext();
                }
            );
        }

        static::assertCount(count($expectedItems), $itemsFound);
        foreach ($itemsFound as $item) {
            $key = array_search($item, $expectedItems, true);
            static::assertIsInt($key, "Item \"$item\" is not expected.");
            unset($expectedItems[$key]);
        }
        static::assertCount(0, $expectedItems, count($expectedItems) . ' has not been found.');

        return $this;
    }
}
