<?php

declare(strict_types = 1);

namespace spec\byrokrat\autogiro\Tree\Response;

use byrokrat\autogiro\Tree\Response\RefundPaymentResponseSection;
use byrokrat\autogiro\Tree\SectionNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class RefundPaymentResponseSectionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RefundPaymentResponseSection::CLASS);
    }

    function it_contains_a_type()
    {
        $this->getType()->shouldEqual('RefundPaymentResponseSection');
    }

    function it_is_a_section()
    {
        $this->shouldHaveType(SectionNode::CLASS);
    }
}
