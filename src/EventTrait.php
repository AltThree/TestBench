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

    protected function getEventListeners($provider)
    {
        $property = (new ReflectionClass($provider))->getProperty('listen');
        $property->setAccessible(true);

        return $property->getValue($provider);
    }

    protected function getEventServiceProvider()
    {
        $split = explode('\\', (new ReflectionClass($this))->getName());

        return "{$split[0]}\\{$split[2]}\\Providers\\EventServiceProvider";
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
        $class = get_class($this->getObjectAndParams()['object']);
        $mappings = $this->getEventListeners(new $provider($this->app));

        $this->assertTrue(isset($mappings[$class]), "Expected '{$class}' to exists as a key in the event mappings.");

        $handlers = count($mappings[$class]);

        if ($this->objectHasHandlers()) {
            $this->assertGreaterThan(0, $handlers, "Expected '{$class}' to have at least 1 handler.");
        } else {
            $this->assertSame(0, $handlers, "Expected '{$class}' to have at exactly 0 handlers.");
        }

        foreach ($mappings[$class] as $handler) {
            $this->assertInstanceOf($handler, $this->app->make($handler));
            $params = (new ReflectionClass($handler))->getMethod('handle')->getParameters();
            $this->assertCount(1, $params, "Expected '{$handler}::handle' to require exactly 1 argument.");
            $this->assertFalse($params[0]->getType()->allowsNull(), "Expected '{$handler}::handle' to require non-null arguments.");

            $type = PHP_VERSION_ID >= 70100 ? $params[0]->getType()->getName() : (string) $params[0]->getType();
            $this->assertTrue($class === $type || (new ReflectionClass($class))->isSubclassOf($type), "Expected '{$class}' to equal or subtype '{$type}' in '{$handler}::handle'.");
        }
    }
}
