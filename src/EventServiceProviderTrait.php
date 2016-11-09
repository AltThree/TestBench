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

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use ReflectionClass;

/**
 * This is the event service provider trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author James Brooks <james@alt-three.com>
 */
trait EventServiceProviderTrait
{
    use ServiceProviderTrait;

    public function testIsAnEventServiceProvider()
    {
        $class = $this->getServiceProviderClass($this->app);

        $reflection = new ReflectionClass($class);

        $provider = new ReflectionClass(EventServiceProvider::class);

        $msg = "Expected class '$class' to be an event service provider.";

        $this->assertTrue($reflection->isSubclassOf($provider), $msg);
    }

    public function testListenerMapIsAnArray()
    {
        $map = $this->getListenerMap();

        $this->assertInternalType('array', $map);
        $this->assertGreaterThan(0, count($map));
    }

    /**
     * @depends testListenerMapIsAnArray
     */
    public function testListenerMapEventsExist()
    {
        $map = $this->getListenerMap();

        foreach (array_keys($map) as $event) {
             $this->assertTrue(class_exists($event) || interface_exists($event));
        }
    }

    /**
     * @depends testListenerMapIsAnArray
     */
    public function testListenerMapKeysAreSorted()
    {
        $map = $this->getListenerMap();

        $events = array_keys($map);

        sort($events);

        $this->assertSame($events, array_keys($map));
    }

    protected function getListenerMap()
    {
        $class = $this->getServiceProviderClass($this->app);

        $reflection = new ReflectionClass($class);

        $property = $reflection->getProperty('listen');

        $property->setAccessible(true);

        return $property->getValue(new $class($this->app));
    }
}
