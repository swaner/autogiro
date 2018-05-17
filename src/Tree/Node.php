<?php
/**
 * This file is part of byrokrat\autogiro.
 *
 * byrokrat\autogiro is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * byrokrat\autogiro is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with byrokrat\autogiro. If not, see <http://www.gnu.org/licenses/>.
 *
 * Copyright 2016-18 Hannes Forsgård
 */

 // declare(strict_types = 1);

namespace byrokrat\autogiro\Tree;

use byrokrat\autogiro\Visitor\VisitorInterface;
use byrokrat\autogiro\Exception\LogicException;

/**
 * Defines a node in the parse tree
 */
class Node
{
    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var Node[]
     */
    private $children = [];

    /**
     * @var int
     */
    private $lineNr = 0;

    /**
     * @var string
     */
    private $value = '';

    public function __construct($lineNr = null, string $value = '')
    {
        $this->lineNr = $lineNr;
        $this->value = $value;
    }

    /**
     * Accept a visitor
     */
    public function accept(VisitorInterface $visitor)
    {
        $visitor->visitBefore($this);

        foreach ($this->getChildren() as $node) {
            $node->accept($visitor);
        }

        $visitor->visitAfter($this);
    }

    /**
     * Get line number this node definition started at
     */
    public function getLineNr()
    {
        return $this->lineNr;
    }

    /**
     * Get node type identifier
     */
    public function getType()
    {
        return basename(str_replace('\\', '/', get_class($this)));
    }

    /**
     * Get raw value wrapped by node
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set a custom attribute on node
     *
     * @param string $name  Name of attribute
     * @param mixed  $value Value of attribute
     */
    public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Get custom attribute
     *
     * @param  string $name Name of attribute
     * @return mixed  Value of attribute
     */
    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? null;
    }

    /**
     * Check if attribute has been set
     */
    public function hasAttribute(string $name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * Get all registered attributes
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set a child node
     */
    public function setChild(string $name, Node $child)
    {
        $this->children[$name] = $child;
    }

    /**
     * Get child node
     *
     * @throws LogicException If child does not exist
     */
    public function getChild(string $name)
    {
        if (!$this->hasChild($name)) {
            throw new LogicException("Trying to read unknown child $name");
        }

        return $this->children[$name];
    }

    /**
     * Check if child exists
     */
    public function hasChild(string $name)
    {
        return isset($this->children[$name]);
    }

    /**
     * Get registered child nodes
     *
     * @return Node[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}
