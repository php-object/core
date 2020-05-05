<?php

declare(strict_types=1);

require(__DIR__ . '/../../vendor/autoload.php');

$verbosityLevel = 0;
if ($argc === 2) {
    switch ($argv[1] ?? null) {
        case '-v':
            $verbosityLevel = 1;
            break;
        case '-vv':
            $verbosityLevel = 2;
            break;
        case '-vvv':
            $verbosityLevel = 3;
            break;
        default:
            echo 'Unknown parameter "' . ($argv[1] ?? null) . '".';
            exit(1);
            break;
    }
}

function outputTestFilePath(string $path, int $verbosityLevel): void
{
    if ($verbosityLevel >= 2) {
        echo $path;
    }
}

$countTested = 0;
$countMissing = 0;
$iterator = new RecursiveDirectoryIterator(__DIR__ . '/../../src');
/** @var SplFileInfo $path */
foreach (new RecursiveIteratorIterator($iterator) as $path) {
    if ($path->isFile() === true && $path->getExtension() === 'php') {
        require_once($path->getPathname());

        $pathFromSrc = substr(realpath($path->getPathname()), strlen(dirname(dirname(__DIR__)) . '/src/'));
        $fqcn =
            'PhpObject\\Core\\'
            . substr(str_replace('/', '\\', $pathFromSrc), 0, -4);
        $reflection = new ReflectionClass($fqcn);

        $pathFromTests = dirname(__DIR__) . '/phpunit/' . substr($pathFromSrc, 0, -4);
        foreach ((new ReflectionClass($fqcn))->getMethods() as $method) {
            $fileName = ucfirst($method->getName()) . 'MethodTest.php';
            if (substr($fileName, 0, 2) === '__') {
                $fileName = ucfirst(substr($fileName, 2));
            }

            $testFilePath = "$pathFromTests/$fileName";
            if (is_file($testFilePath) === false) {
                $countMissing++;
                echo "\033[0;31mMethod $fqcn::" . $method->getName() . "() is not tested. \033[0m";
                outputTestFilePath($testFilePath, $verbosityLevel);
                echo "\n";
            } else {
                $countTested++;
                if ($verbosityLevel >= 1) {
                    echo "\033[0;32mMethod $fqcn::" . $method->getName() . "() is tested. \033[0m";
                    outputTestFilePath($testFilePath, $verbosityLevel);
                    echo "\n";
                }
            }
        }
    }
}

echo "\n";
echo "Tested: $countTested/" . ($countTested + $countMissing) . ", missing: $countMissing.";
echo "\n";

if ($countMissing > 0) {
    exit(1);
}
