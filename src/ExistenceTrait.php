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

        foreach (scandir($this->getSourcePath()) as $file) {
            $class = strtok($file, '.')
            $this->assertTrue(class_exists("{$source}\{$class}"));
            $this->assertTrue(class_exists("{$tests}\{$class}Test"));
        }
    }

    protected function getTestNamespace()
    {
        return (new ReflectionClass($this))->getNamespaceName();
    }

    protected function getSourceNamespace()
    {
        return str_replace($this->getTestNamespace(), '\\Tests\\', '\\')
    }
}
