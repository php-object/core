<?php

declare(strict_types=1);

namespace PhpObject\Directory;

use PhpObject\Exception\Directory\DirectoryNotFoundException;

class DirectoryUtils
{
    /** @link https://www.php.net/manual/fr/function.chdir.php */
    public static function change(string $directory): void
    {
        try {
            $executed = chdir($directory);
        } catch (\Throwable $exception) {
            throw new DirectoryNotFoundException($directory, 0, $exception);
        }

        if ($executed === false) {
            throw new DirectoryNotFoundException($directory);
        }
    }
}
