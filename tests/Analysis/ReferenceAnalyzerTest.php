<?php

/*
 * This file is part of Alt Three TestBench.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\Tests\TestBench\Analysis;

use AltThree\TestBench\Analysis\ReferenceAnalyzer;
use PHPUnit_Framework_TestCase as TestCase;

class ReferenceAnalyzerTest extends TestCase
{
    public function testCanGenerateRefs()
    {
        $refs = (new ReferenceAnalyzer())->analyze(__FILE__);

        $this->assertSame([
            'AltThree\TestBench\Analysis\ReferenceAnalyzer',
            'PHPUnit_Framework_TestCase',
        ], $refs);
    }

    public function testCanGenerateMoreRefs()
    {
        $refs = (new ReferenceAnalyzer())->analyze(__DIR__.'/../../src/Analysis/ReferenceAnalyzer.php');

        $this->assertSame([
            'PhpParser\NodeTraverser',
            'PhpParser\NodeVisitor\NameResolver',
            'PhpParser\ParserFactory',
            'AltThree\TestBench\Analysis\Parser',
            'AltThree\TestBench\Analysis\ImportVisitor',
            'AltThree\TestBench\Analysis\NameVisitor',
        ], $refs);
    }

    public function testCanGenerateUsingStub()
    {
        $refs = (new ReferenceAnalyzer())->analyze(__DIR__.'/stubs/func.php');

        $this->assertSame([], $refs);
    }
}
