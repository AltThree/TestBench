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

use Exception;

/**
 * This is the expects trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait ExpectsTrait
{
    /**
     * Specify a list of events that should be fired for the given operation.
     *
     * These events will be mocked, so that handlers will not actually be executed.
     *
     * @param string[]|string $events
     *
     * @throws \Exception
     *
     * @return $this
     */
    public function onlyExpectsEvents($events)
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

            if ($eventsFired = array_diff(array_map('get_class', $this->firedEvents), $events)) {
                throw new Exception(
                    'These unexpected events were fired: ['.implode(', ', $eventsFired).']'
                );
            }
        });

        return $this;
    }

    /**
     * Specify a list of jobs that should be dispatched for the given operation.
     *
     * These jobs will be mocked, so that handlers will not actually be executed.
     *
     * @param string[]|string $jobs
     *
     * @return $this
     */
    protected function onlyExpectsJobs($jobs)
    {
        $jobs = is_array($jobs) ? $jobs : func_get_args();

        $this->withoutJobs();

        $this->beforeApplicationDestroyed(function () use ($jobs) {
            $dispatched = $this->getDispatchedJobs($jobs);

            if ($jobsNotDispatched = array_diff($jobs, $dispatched)) {
                throw new Exception(
                    'These expected jobs were not dispatched: ['.implode(', ', $jobsNotDispatched).']'
                );
            }

            if ($jobsDispatched = array_diff(array_map('get_class', $this->dispatchedJobs), $dispatched)) {
                throw new Exception(
                    'These unexpected jobs were dispatched: ['.implode(', ', $jobsDispatched).']'
                );
            }
        });

        return $this;
    }
}
