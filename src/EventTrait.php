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
 * This is the event trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait EventTrait
{
    use AnemicTrait;

    protected function getEventInterfaces()
    {
        $split = explode('/', get_class($this));

        return ["{$split[0]}/{$split[1]}/Events\EventInterface"];
    }

    protected function getEventServiceProvider()
    {
        $split = explode('/', get_class($this));

        return "{$split[0]}/{$split[1]}/Providers/EventServiceProvider";
    }

    public function testEventImplementsTheCorrectInterfaces()
    {
        $event = $this->getObjectAndParams()['object'];

        foreach ($this->getEventInterfaces() as $interface) {
            $this->assertInstanceOf($interface, $event);
        }
    }

    public function testEventHasRegisteredHandlers()
    {
        $provider = $this->getEventServiceProvider();

        $property = (new ReflectionClass($provider))->getProperty('listen');
        $property->setAccessible(true);

        $class = get_class($this->getObjectAndParams()['object']);
        $mappings = $property->getValue(new $provider($this->app));

        $this->assertTrue(isset($mappings[$class]));
        $this->assertGreaterThan(0, count($mappings[$class]));

        foreach ($mappings[$class] as $handler) {
            $this->assertInstanceOf($handler, $this->app->make($handler));
        }
    }
}
