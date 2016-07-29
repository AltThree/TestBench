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

use Illuminate\Foundation\Application;

/**
 * This is the validation trait.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
trait ValidationTrait
{
    /**
     * Check the rules on the given object are valid.
     *
     * @param object $object
     *
     * @return void
     */
    protected function checkRules($object)
    {
        $this->assertTrue(property_exists($object, 'rules'));

        $this->assertInternalType('array', $object->rules);

        foreach ($object->rules as $rule) {
            $this->checkRule($rule);
        }
    }

    /**
     * Check the given validation rule is valid.
     *
     * @param array|string $rule
     *
     * @return void
     */
    protected function checkRule($rule)
    {
        $this->assertTrue(is_array($rule) || is_string($rule));

        $parts = is_array($rule) ? $rule : explode('|', $rule);

        $this->assertTrue(isset($parts[0]));

        if ($this->enforceRequiredOrNullable()) {
            $this->assertTrue($parts[0] === 'required' || $parts[0] === 'nullable');
        }

        foreach ($parts as $part) {
            $this->assertInternalType('string', $part);
        }
    }

    /**
     * Should we require rules to be required or nullable?
     *
     * @return bool
     */
    protected function enforceRequiredOrNullable()
    {
        return version_compare(Application::VERSION, '5.3') === 1;
    }
}
