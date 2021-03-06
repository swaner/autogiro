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

declare(strict_types = 1);

namespace byrokrat\autogiro\Xml;

use byrokrat\autogiro\Tree\Node;
use byrokrat\autogiro\Visitor\VisitorInterface;

/**
 * Transform node tree into xml
 */
class XmlWritingVisitor implements VisitorInterface
{
    /**
     * @var \XMLWriter
     */
    private $xmlWriter;

    /**
     * @var Stringifier
     */
    private $stringifier;

    public function __construct(\XMLWriter $xmlWriter, Stringifier $stringifier)
    {
        $this->xmlWriter = $xmlWriter;
        $this->stringifier = $stringifier;
    }

    public function visitBefore(Node $node): void
    {
        $this->xmlWriter->startElement($node->getType());

        foreach ($node->getAttributes() as $name => $value) {
            $this->xmlWriter->writeAttribute($name, $this->stringifier->stringify($value));
        }

        if ($node->getValue()) {
            $this->xmlWriter->text($node->getValue());
        }
    }

    public function visitAfter(Node $node): void
    {
        $this->xmlWriter->endElement();
    }
}
