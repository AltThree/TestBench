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

use PHPUnit\Framework\Assert;

/**
 * This is the internal type trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait InternalTypeTrait
{
    public static function assertIsArray($actual, $message = ''): void
    {
        if (is_callable([Assert::class, 'assertIsArray'])) {
            Assert::assertIsArray($actual, $message);
        } else {
            Assert::assertInternalType('array', $actual, $message);
        }
    }

    public static function assertIsString($actual, $message = ''): void
    {
        if (is_callable([Assert::class, 'assertIsString'])) {
            Assert::assertIsString($actual, $message);
        } else {
            Assert::assertInternalType('string', $actual, $message);
        }
    }
}
