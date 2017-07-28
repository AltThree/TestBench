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

namespace AltThree\Tests\TestBench\Analysis;

use AltThree\TestBench\Analysis\ReferenceAnalyzer;
use PHPUnit\Framework\TestCase;

class ReferenceAnalyzerTest extends TestCase
{
    public function testCanGenerateRefs()
    {
        $refs = (new ReferenceAnalyzer())->analyze(__FILE__);

        $this->assertSame([
            'AltThree\TestBench\Analysis\ReferenceAnalyzer',
            'PHPUnit\Framework\TestCase',
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

    public function testCanGenerateUsingFuncStub()
    {
        $refs = (new ReferenceAnalyzer())->analyze(__DIR__.'/stubs/func.php');

        $this->assertSame([], $refs);
    }

    public function testCanGenerateUsingBoolStub()
    {
        $refs = (new ReferenceAnalyzer())->analyze(__DIR__.'/stubs/bool.php');

        $this->assertSame([], $refs);
    }
}
