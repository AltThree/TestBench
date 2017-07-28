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

use AltThree\TestBench\Analysis\ClassInspector;
use AltThree\TestBench\AnemicTrait;
use PhpParser\NodeTraverserInterface;
use PHPUnit\Framework\TestCase;

class ClassInspectorTest extends TestCase
{
    public function testCanInspectClasses()
    {
        $inspector = ClassInspector::inspect(static::class);

        $this->assertInstanceOf(ClassInspector::class, $inspector);

        $this->assertTrue($inspector->isClass());
        $this->assertFalse($inspector->isInterface());
        $this->assertFalse($inspector->isTrait());
        $this->assertTrue($inspector->exists());

        $this->assertSame([
            'AltThree\TestBench\Analysis\ClassInspector',
            'AltThree\TestBench\AnemicTrait',
            'PhpParser\NodeTraverserInterface',
            'PHPUnit_Framework_TestCase',
        ], $inspector->references());
    }

    public function testCanInspectInterfaces()
    {
        $inspector = ClassInspector::inspect(NodeTraverserInterface::class);

        $this->assertInstanceOf(ClassInspector::class, $inspector);

        $this->assertFalse($inspector->isClass());
        $this->assertTrue($inspector->isInterface());
        $this->assertFalse($inspector->isTrait());
        $this->assertTrue($inspector->exists());

        $this->assertSame(['PhpParser\NodeVisitor'], $inspector->references());
    }

    public function testCanInspectTraits()
    {
        $inspector = ClassInspector::inspect(AnemicTrait::class);

        $this->assertInstanceOf(ClassInspector::class, $inspector);

        $this->assertFalse($inspector->isClass());
        $this->assertFalse($inspector->isInterface());
        $this->assertTrue($inspector->isTrait());
        $this->assertTrue($inspector->exists());

        $this->assertSame([
            'ReflectionClass',
            'ReflectionException',
            'ReflectionParameter',
            'ReflectionProperty',
            'AltThree\TestBench\ExpectsTrait',
            'AltThree\TestBench\ValidationTrait',
        ], $inspector->references());
    }

    public function testCanInspectNothing()
    {
        $inspector = ClassInspector::inspect('foobarbaz');

        $this->assertInstanceOf(ClassInspector::class, $inspector);

        $this->assertFalse($inspector->isClass());
        $this->assertFalse($inspector->isInterface());
        $this->assertFalse($inspector->isTrait());
        $this->assertFalse($inspector->exists());

        $this->assertSame([], $inspector->references());
    }
}
