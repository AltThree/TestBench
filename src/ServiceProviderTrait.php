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

use GrahamCampbell\TestBenchCore\LaravelTrait;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait as ProviderTrait;
use ReflectionClass;

/**
 * This is the service provider trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait ServiceProviderTrait
{
    use LaravelTrait, ProviderTrait;

    protected function getServiceProviderClass($app)
    {
        $split = explode('\\', (new ReflectionClass($this))->getName());
        $class = substr(end($split), 0, -4);

        return "{$split[0]}\\{$split[2]}\\Providers\\{$class}}";
    }
}
