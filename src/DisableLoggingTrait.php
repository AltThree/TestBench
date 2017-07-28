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

/**
 * This is the disable logging trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait DisableLoggingTrait
{
    /**
     * @before
     */
    public function disableLogging()
    {
        $this->app->config->set('logger.loggers', []);
    }
}
