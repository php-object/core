<?php

declare(strict_types=1);

namespace PhpObject\Core\Link;

use PhpObject\Core\ErrorHandler\PhpObjectErrorHandlerManager;

class SymbolicLinkUtils
{
    /** @link https://www.php.net/manual/en/function.is-link.php */
    public static function isSymbolicLink(string $path): bool
    {
        PhpObjectErrorHandlerManager::enable();
        $return = is_link($path);
        PhpObjectErrorHandlerManager::disable();
        PhpObjectErrorHandlerManager::assertNoError();

        return $return;
    }
}
