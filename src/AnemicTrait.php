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
use ReflectionParameter;
use ReflectionProperty;

/**
 * This is the anemic trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait AnemicTrait
{
    protected function objectHasQueue()
    {
        return false;
    }

    protected function objectHasRules()
    {
        return false;
    }

    protected function objectHasThrottle()
    {
        return false;
    }

    public function testClassIsFinal()
    {
        $rc = new ReflectionClass($this->getObjectAndParams()['object']);

        $this->assertTrue($rc->isFinal());
    }

    public function testPropertiesMatchTheConstructor()
    {
        $rc = new ReflectionClass($this->getObjectAndParams()['object']);

        $properties = array_map(function (ReflectionProperty $property) {
            return $property->getName();
        }, $rc->getProperties());

        $params = array_map(function (ReflectionParameter $param) {
            return $param->getName();
        }, $rc->getMethod('__construct')->getParameters());

        if ($this->objectHasQueue()) {
            $params[] = 'queue';
        }

        if ($this->objectHasRules()) {
            $params[] = 'rules';
        }

        if ($this->objectHasThrottle()) {
            $params[] = 'throttle';
        }

        $this->assertSame($properties, $params);
    }

    public function testPropertiesAreCorrectlyDefined()
    {
        $rc = new ReflectionClass($this->getObjectAndParams()['object']);

        foreach ($rc->getProperties() as $property) {
            $this->assertTrue($property->isPublic());
            $this->assertFalse($property->isStatic());
        }
    }

    public function testPropertyAccessBehavesCorrectly()
    {
        extract($this->getObjectAndParams());

        foreach ($params as $key => $value) {
            $this->assertSame($value, $object->{$key});
        }
    }
}
