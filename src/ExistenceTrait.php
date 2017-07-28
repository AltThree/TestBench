<?php

declare(strict_types=1);

/*
 * This file is part of Alt Three TestBench.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\TestBench;

use CallbackFilterIterator;
use GrahamCampbell\Analyzer\ClassInspector;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

/**
 * This is the existence trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait ExistenceTrait
{
    abstract protected function getSourcePath();

    /**
     * @dataProvider provideFilesToCheck
     */
    public function testExistence($class, $test)
    {
        $inspector = ClassInspector::inspect($class);

        $this->assertTrue($inspector->exists(), "Expected {$class} to exist.");

        if ($inspector->isClass()) {
            $this->assertTrue(class_exists($test), "Expected there to be tests for {$class}.");
        }
    }

    public function provideFilesToCheck()
    {
        $source = $this->getSourceNamespace();
        $tests = $this->getTestNamespace();
        $path = $this->getSourcePath();
        $len = strlen($path);

        $files = new CallbackFilterIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)), function ($file) {
            return $file->getFilename()[0] !== '.' && !$file->isDir();
        });

        return array_map(function ($file) use ($len, $source, $tests) {
            $name = str_replace('/', '\\', strtok(substr($file->getPathname(), $len), '.'));

            return ["{$source}{$name}", "{$tests}{$name}Test"];
        }, iterator_to_array($files));
    }

    protected function getSourceNamespace()
    {
        return str_replace('\\Tests\\', '\\', $this->getTestNamespace());
    }

    protected function getTestNamespace()
    {
        return (new ReflectionClass($this))->getNamespaceName();
    }
}
