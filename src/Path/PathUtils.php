<?php

declare(strict_types=1);

namespace PhpObject\Core\Path;

use PhpObject\Core\ErrorHandler\PhpObjectErrorHandlerManager;

class PathUtils
{
    /** @link https://www.php.net/manual/en/function.basename.php */
    public static function getBaseName(string $path, string $suffix = null): string
    {
        PhpObjectErrorHandlerManager::enable();
        if (is_string($suffix) === true) {
            $return = basename($path, $suffix);
        } else {
            $return = basename($path);
        }
        PhpObjectErrorHandlerManager::disable();
        PhpObjectErrorHandlerManager::assertNoError();

        return $return;
    }
}
