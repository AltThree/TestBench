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

use Illuminate\Contracts\Bus\Dispatcher;

/**
 * This is the command trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait CommandTrait
{
    use AnemicTrait;

    /**
     * @before
     */
    public function setEventExpectations()
    {
        $this->onlyExpectsEvents([]);
    }

    public function testHandlerCanBeResolved()
    {
        $command = $this->getObjectAndParams()['object'];
        $dispatcher = $this->app->make(Dispatcher::class);

        if (method_exists($dispatcher, 'resolveHandler')) {
            $handler = $dispatcher->resolveHandler($command);
        } else {
            $handler = $dispatcher->getCommandHandler($command) ?: $command;
        }

        $this->assertInstanceOf($this->getHandlerClass(), $handler);
    }
}
