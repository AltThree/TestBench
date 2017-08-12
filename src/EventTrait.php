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

/**
 * This is the event trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait EventTrait
{
    use AnemicTrait;

    abstract protected function getEventInterfaces();

    protected function setFrameworkExpectations()
    {
        $this->onlyExpectsJobs([]);
    }

    protected function objectHasHandlers()
    {
        return true;
    }

    protected function getEventServiceProvider()
    {
        $split = explode('\\', (new ReflectionClass($this))->getName());

        return "{$split[0]}\\{$split[2]}\\Foundation\\Providers\\EventServiceProvider";
    }

    public function testEventImplementsTheCorrectInterfaces()
    {
        $this->setFrameworkExpectations();

        $event = $this->getObjectAndParams()['object'];

        foreach ($this->getEventInterfaces() as $interface) {
            $this->assertInstanceOf($interface, $event);
        }
    }

    public function testEventHasRegisteredHandlers()
    {
        $this->setFrameworkExpectations();

        $provider = $this->getEventServiceProvider();

        $property = (new ReflectionClass($provider))->getProperty('listen');
        $property->setAccessible(true);

        $class = get_class($this->getObjectAndParams()['object']);
        $mappings = $property->getValue(new $provider($this->app));

        $this->assertTrue(isset($mappings[$class]));

        $handlers = count($mappings[$class]);

        if ($this->objectHasHandlers()) {
            $this->assertGreaterThan(0, $handlers);
        } else {
            $this->assertSame(0, $handlers);
        }

        foreach ($mappings[$class] as $handler) {
            $this->assertInstanceOf($handler, $this->app->make($handler));
            $params = (new ReflectionClass($handler))->getMethod('handle')->getParameters();
            $this->assertCount(1, $params);
            $this->assertFalse($params[0]->getType()->allowsNull());
            $type = (string) $params[0]->getType();
            $this->assertTrue($class === $type || (new ReflectionClass($class))->isSubclassOf($type));
        }
    }
}
