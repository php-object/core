<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryManager;

use PhpObject\Core\{
    Directory\DirectoryManager,
    Exception\Variable\ResourceIsClosedException,
    Tests\PhpUnit\AbstractTestCase
};

class AssertIsOpenMethodTest extends AbstractTestCase
{
    public function testIsOpen(): void
    {
        /** @var DirectoryManager $manager */
        $manager = $this->callPhpObjectMethod(
            function (): DirectoryManager {
                return DirectoryManager::createFromPath(__DIR__);
            }
        );

        $manager->assertIsOpen();
        $this->addToAssertionCount(1);

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

        $manager->assertIsOpen();
        $this->addToAssertionCount(1);

        $manager->close();
        $this->assertExceptionIsThrowned(
            function () use ($manager): void {
                $manager->assertIsOpen();
            },
            ResourceIsClosedException::class,
            'Variable "$resource" should be a valid open resource but is a closed resource.'
        );
    }
}
