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
        $this->expectsEvents([]);
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
