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

use Illuminate\Queue\SerializesModels;
use ReflectionClass;

/**
 * This is the job trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait JobTrait
{
    use CommandTrait;

    /**
     * @before
     */
    public function setEventExpectations()
    {
        $this->expectsOnlyEvents([]);
    }

    /**
     * Specify a list of events that should be fired for the given operation.
     *
     * These events will be mocked, so that handlers will not actually be executed.
     *
     * @param  array|string  $events
     * @return $this
     *
     * @throws \Exception
     */
    public function expectsOnlyEvents($events)
    {
        $events = is_array($events) ? $events : func_get_args();

        $this->withoutEvents();

        $this->beforeApplicationDestroyed(function () use ($events) {
            $fired = $this->getFiredEvents($events);

            if ($eventsNotFired = array_diff($events, $fired)) {
                throw new Exception(
                    'These expected events were not fired: ['.implode(', ', $eventsNotFired).']'
                );
            }

            if ($eventsFired = array_diff($fired, $events)) {
                throw new Exception(
                    'These unexpected events were fired: ['.implode(', ', $eventsFired).']'
                );
            }
        });

        return $this;
    }

    public function testJobSerializesModels()
    {
        $data = $this->getObjectAndParams();

        $rc = new ReflectionClass($data['object']);

        if ($data['params']) {
            $this->assertTrue(in_array(SerializesModels::class, $rc->getTraitNames(), true));
        } else {
            $this->assertFalse(in_array(SerializesModels::class, $rc->getTraitNames(), true));
        }
    }
}
