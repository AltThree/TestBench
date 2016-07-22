<?php

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
    /**
     * @dataProvider provideFilesToCheck
     */
    public function testExistence($class, $test)
    {
        if ($interface = interface_exists($class)) {
            $this->assertTrue($interface, "Expected the interface {$class} to exist.");
        } elseif ($trait = trait_exists($class)) {
            $this->assertTrue($trait, "Expected the trait {$class} to exist.");
        } else {
            $this->assertTrue(class_exists($class), "Expected the class {$class} to exist.");
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

    protected function getTestNamespace()
    {
        return (new ReflectionClass($this))->getNamespaceName();
    }

    protected function getSourceNamespace()
    {
        return str_replace('\\Tests\\', '\\', $this->getTestNamespace());
    }
}
