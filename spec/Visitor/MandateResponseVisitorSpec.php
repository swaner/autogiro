<?php

declare(strict_types = 1);

namespace spec\byrokrat\autogiro\Visitor;

use byrokrat\autogiro\Visitor\MandateResponseVisitor;
use byrokrat\autogiro\Visitor\ErrorAwareVisitor;
use byrokrat\autogiro\Visitor\ErrorObject;
use byrokrat\autogiro\Tree\LayoutNode;
use byrokrat\autogiro\Tree\Record\RecordNode;
use byrokrat\autogiro\Tree\Record\ResponseOpeningRecord;
use byrokrat\autogiro\Tree\Record\MandateResponseClosingRecord;
use byrokrat\autogiro\Tree\DateNode;
use byrokrat\autogiro\Tree\TextNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MandateResponseVisitorSpec extends ObjectBehavior
{
    function let(ErrorObject $errorObj)
    {
        $this->beConstructedWith($errorObj);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MandateResponseVisitor::CLASS);
    }

    function it_is_an_error_aware_visitor()
    {
        $this->shouldHaveType(ErrorAwareVisitor::CLASS);
    }

    function it_fails_on_missmatching_dates(
        ResponseOpeningRecord $opening,
        MandateResponseClosingRecord $closing,
        DateNode $dateA,
        DateNode $dateB,
        $errorObj
    ) {
        $dateA->getValue()->willReturn('2010');
        $opening->getChild('date')->willReturn($dateA);

        $this->beforeResponseOpeningRecord($opening);

        $dateB->getValue()->willReturn('2011');
        $closing->getChild('date')->willReturn($dateB);
        $closing->getLineNr()->willReturn(1);

        $this->beforeMandateResponseClosingRecord($closing);

        $errorObj->addError(Argument::type('string'), Argument::cetera())->shouldHaveBeenCalledTimes(1);
    }

    function it_fails_on_wrong_record_count(
        LayoutNode $layout,
        ResponseOpeningRecord $opening,
        RecordNode $record,
        MandateResponseClosingRecord $closing,
        TextNode $nrOfPosts,
        $errorObj
    ) {
        $nrOfPosts->getValue()->willReturn('2');
        $closing->getChild('nr_of_posts')->willReturn($nrOfPosts);

        $this->afterMandateResponseClosingRecord($closing);

        $layout->getChildren()->willReturn([$opening, $record, $closing]);
        $layout->getLineNr()->willReturn(1);

        $this->afterLayoutNode($layout);

        $errorObj->addError(Argument::type('string'), Argument::cetera())->shouldHaveBeenCalledTimes(1);
    }
}