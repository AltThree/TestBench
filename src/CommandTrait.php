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
trait DisableLoggingTrait
{
    use AnemicTrait;

    public function testHandlerCanBeResolved()
    {
        $command = $this->getObjectAndParams()['object'];
        $dispatcher = $this->app->make(Dispatcher::class);

        $this->assertInstanceOf($this->getHandlerClass(), $dispatcher->resolveHandler($command));
    }
}
