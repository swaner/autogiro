<?php

declare(strict_types = 1);

namespace spec\byrokrat\autogiro\Tree\Response;

use byrokrat\autogiro\Tree\Response\MandateResponse;
use byrokrat\autogiro\Tree\RecordNode;
use PhpSpec\ObjectBehavior;

class MandateResponseSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(0, []);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MandateResponse::CLASS);
    }

    function it_contains_a_type()
    {
        $this->getType()->shouldEqual('MandateResponse');
    }

    function it_is_a_record()
    {
        $this->shouldHaveType(RecordNode::CLASS);
    }
}
