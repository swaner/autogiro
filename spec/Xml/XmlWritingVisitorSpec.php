<?php

declare(strict_types = 1);

namespace spec\byrokrat\autogiro\Xml;

use byrokrat\autogiro\Xml\XmlWritingVisitor;
use byrokrat\autogiro\Xml\Stringifier;
use byrokrat\autogiro\Tree\Node;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class XmlWritingVisitorSpec extends ObjectBehavior
{
    function let(\XMLWriter $writer, Stringifier $stringifier)
    {
        $this->beConstructedWith($writer, $stringifier);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(XmlWritingVisitor::CLASS);
    }

    function it_writes_elements_before(Node $node, $writer, $stringifier)
    {
        $node->getType()->willReturn('type');
        $writer->startElement('type')->shouldBeCalled();

        $node->getAttributes()->willReturn(['name' => 'value']);
        $stringifier->stringify('value')->willReturn('stringified_value');
        $writer->writeAttribute('name', 'stringified_value')->shouldBeCalled();

        $node->getValue()->willReturn('value');
        $writer->text('value')->shouldBeCalled();

        $this->visitBefore($node);
    }

    function it_ignores_void_values(Node $node, $writer)
    {
        $node->getType()->willReturn('type');
        $writer->startElement('type')->shouldBeCalled();

        $node->getAttributes()->willReturn([]);

        $node->getValue()->willReturn('');
        $writer->text('value')->shouldNotBeCalled();

        $this->visitBefore($node);
    }

    function it_closes_elements_after(Node $node, $writer)
    {
        $writer->endElement()->shouldBeCalled();
        $this->visitAfter($node);
    }
}
