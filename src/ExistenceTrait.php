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
    public function testExistence()
    {
        $source = $this->getSourceNamespace();
        $tests = $this->getTestNamespace();
        $path = $this->getSourcePath();
        $len = strlen($path);

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        foreach ($files as $file) {
            if ($file->isDir()) { 
                continue;
            }

            $name = str_replace('/', '\\', strtok(substr($file->getPathname(), $len), '.'));
            $this->assertTrue(class_exists("{$source}{$name}"));
            $this->assertTrue(class_exists("{$tests}{$name}Test"));
        }
    }

    protected function getTestNamespace()
    {
        return (new ReflectionClass($this))->getNamespaceName();
    }

    protected function getSourceNamespace()
    {
        return str_replace($this->getTestNamespace(), '\\Tests\\', '\\');
    }
}
