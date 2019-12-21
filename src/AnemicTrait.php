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

use ReflectionClass;
use ReflectionException;
use ReflectionParameter;
use ReflectionProperty;

/**
 * This is the anemic trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait AnemicTrait
{
    use ExpectsTrait, ValidationTrait;

    abstract protected function getObjectAndParams();

    protected function setFrameworkExpectations()
    {
        //
    }

    protected function objectHasDelay()
    {
        return false;
    }

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
        $this->setFrameworkExpectations();

        $rc = new ReflectionClass($this->getObjectAndParams()['object']);

        $this->assertTrue($rc->isFinal());
    }

    public function testPropertiesMatchTheConstructor()
    {
        $this->setFrameworkExpectations();

        $rc = new ReflectionClass($this->getObjectAndParams()['object']);

        $properties = array_map(function (ReflectionProperty $property) {
            return $property->getName();
        }, $rc->getProperties());

        try {
            $params = array_map(function (ReflectionParameter $param) {
                return $param->getName();
            }, $rc->getMethod('__construct')->getParameters());
        } catch (ReflectionException $e) {
            $params = [];
        }

        if ($this->objectHasDelay()) {
            $params[] = 'delay';
        }

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
        $this->setFrameworkExpectations();

        $obj = $this->getObjectAndParams()['object'];
        $rc = new ReflectionClass($obj);

        $props = $rc->getProperties();

        $this->assertIsArray($props);

        foreach ($props as $property) {
            $this->assertTrue($property->isPublic());
            $this->assertFalse($property->isStatic());
        }

        if ($this->objectHasRules()) {
            $this->checkRules($obj);
        }
    }

    public function testPropertyAccessBehavesCorrectly()
    {
        $this->setFrameworkExpectations();

        extract($this->getObjectAndParams());

        $this->assertIsArray($params);

        foreach ($params as $key => $value) {
            $this->assertSame($value, $object->{$key});
        }
    }
}
