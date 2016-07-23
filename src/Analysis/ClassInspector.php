<?php

/*
 * This file is part of Alt Three TestBench.
 *
 * (c) Alt Three Services Limited
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AltThree\TestBench\Analysis;

use InvalidArgumentException;
use ReflectionClass;

/**
 * This is the class inspector class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ClassInspector
{
    /**
     * The class name.
     *
     * @var string
     */
    protected $class;

    /**
     * Inspect the given class.
     *
     * @param string $class
     *
     * @throws \InvalidArgumentException
     *
     * @return static
     */
    public static function inspect($class)
    {
        if (!$class || !is_string($class)) {
            throw new InvalidArgumentException('The class name must be a non-empty string.');
        }

        return new static($class);
    }

    /**
     * Create a new class inspector instance.
     *
     * @param string $class
     *
     * @return void
     */
    protected function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * Is the class a valid class?
     *
     * @return bool
     */
    public function isClass()
    {
        return class_exists($this->class);
    }

    /**
     * Is the class a valid interface?
     *
     * @return bool
     */
    public function isInterface()
    {
        return interface_exists($this->class);
    }

    /**
     * Is the class a valid trait?
     *
     * @return bool
     */
    public function isTrait()
    {
        return trait_exists($this->class);
    }

    /**
     * Does the class exist?
     *
     * @return bool
     */
    public function exists()
    {
        return $this->isClass() || $this->isInterface() || $this->isTrait();
    }

    /**
     * Get the fullyqualified imports and typehints.
     *
     * @return string[]
     */
    public function references()
    {
        if (!$this->exists()) {
            return [];
        }

        $file = (new ReflectionClass($this->class))->getFileName();

        return (new ReferenceAnalyzer())->analyze($file);
    }
}
