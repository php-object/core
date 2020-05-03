<?php

declare(strict_types=1);

namespace PhpObject\Core\Tests\PhpUnit\Directory\DirectoryManager;

use PhpObject\Core\{
    Directory\DirectoryManager,
    Exception\Variable\ResourceExpectedException,
    Tests\PhpUnit\AbstractTestCase,
    Version\PhpVersionUtils
};

class ConstructMethodTest extends AbstractTestCase
{
    public function testOpenResource(): void
    {
        $resource = opendir(__DIR__);
        if (is_resource($resource) === false) {
            static::fail('Cannot open directory ' . __DIR__ . ' for reading.');
        }

        /** @var DirectoryManager $manager */
        $manager = $this->callPhpObjectMethod(
            function () use ($resource): DirectoryManager {
                return new DirectoryManager($resource);
            }
        );

        static::assertIsOpenResource($manager->getResource());

        $manager->close();
    }

    public function testClosedResource(): void
    {
        $resource = opendir(__DIR__);
        if (is_resource($resource) === false) {
            static::fail('Cannot open directory ' . __DIR__ . ' for reading.');
        }
        closedir($resource);

        $this->assertExceptionIsThrowned(
            function () use ($resource): DirectoryManager {
                return new DirectoryManager($resource);
            },
            ResourceExpectedException::class,
            PhpVersionUtils::is71()
                ? 'Variable "$resource" should be a valid resource but is of type unknown type.'
                : 'Variable "$resource" should be a valid resource but is of type resource (closed).'
        );
    }
}
