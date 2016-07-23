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

use AltThree\TestBench\Analysis\ClassInspector;
use AltThree\TestBench\Analysis\ReferenceAnalyzer;
use AppendIterator;
use CallbackFilterIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;

/**
 * This is the analysis trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait AnalysisTrait
{
    /**
     * @dataProvider provideFilesToCheck
     */
    public function testReferences($file)
    {
        foreach ((new ReferenceAnalyzer())->analyze($file) as $class) {
            $this->assertTrue(ClassInspector::inspect($class)->exists(), "Expected {$class} to exist.");
        }
    }

    public function provideFilesToCheck()
    {
        $iterator = new AppendIterator();

        foreach ($this->getPaths() as $path) {
            $iterator->append(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)));
        }

        $files = new CallbackFilterIterator($iterator, function ($file) {
            return $file->getFilename()[0] !== '.' && !$file->isDir();
        });

        return iterator_to_array($files));
    }
}
