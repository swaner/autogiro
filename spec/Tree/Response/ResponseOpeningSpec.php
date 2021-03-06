<?php

declare(strict_types = 1);

namespace spec\byrokrat\autogiro\Tree\Response;

use byrokrat\autogiro\Tree\Response\ResponseOpening;
use byrokrat\autogiro\Tree\RecordNode;
use PhpSpec\ObjectBehavior;

class ResponseOpeningSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(0, []);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ResponseOpening::CLASS);
    }

    function it_contains_a_type()
    {
        $this->getType()->shouldEqual('ResponseOpening');
    }

    function it_is_a_record()
    {
        $this->shouldHaveType(RecordNode::CLASS);
    }
}
