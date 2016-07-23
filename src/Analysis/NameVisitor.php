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

use PhpParser\Node;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\NodeVisitorAbstract;

/**
 * This is the name visitor class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class NameVisitor extends NodeVisitorAbstract
{
    /**
     * The recorded names.
     *
     * @var string[]
     */
    protected $names = [];

    /**
     * Enter the node and record the name.
     *
     * @param \PhpParser\Node $node
     *
     * @return \PhpParser\Node
     */
    public function enterNode(Node $node)
    {
        if ($node instanceof FullyQualified) {
            $this->names[] = $node->toString();
        }

        return $node;
    }

    /**
     * Get the recorded names.
     *
     * @return string[]
     */
    public function getNames()
    {
        return $this->names;
    }
}
