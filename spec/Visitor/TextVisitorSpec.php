<?php

declare(strict_types = 1);

namespace spec\byrokrat\autogiro\Visitor;

use byrokrat\autogiro\Visitor\TextVisitor;
use byrokrat\autogiro\Visitor\ErrorAwareVisitor;
use byrokrat\autogiro\Visitor\ErrorObject;
use byrokrat\autogiro\Tree\BgcNumberNode;
use byrokrat\autogiro\Tree\PayerNumberNode;
use byrokrat\autogiro\Tree\RepetitionsNode;
use byrokrat\autogiro\Tree\TextNode;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TextVisitorSpec extends ObjectBehavior
{
    function let(ErrorObject $errorObj)
    {
        $this->beConstructedWith($errorObj);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TextVisitor::CLASS);
    }

    function it_is_an_error_aware_visitor()
    {
        $this->shouldHaveType(ErrorAwareVisitor::CLASS);
    }

    function a_failing_regexp(TextNode $node)
    {
        $node->getValue()->willReturn('foo');
        $node->getValidationRegexp()->willReturn('/bar/');
        $node->getLineNr()->willReturn(1);

        return $node;
    }

    function it_captures_invalid_text_nodes(TextNode $textNode, $errorObj)
    {
        $this->beforeTextNode($this->a_failing_regexp($textNode));
        $errorObj->addError(Argument::type('string'), Argument::cetera())->shouldHaveBeenCalledTimes(1);
    }

    function it_ignores_text_nodes_without_regexp(TextNode $textNode, $errorObj)
    {
        $textNode->getValue()->willReturn('does-not-match-regexp');
        $textNode->getValidationRegexp()->willReturn('');

        $this->beforeTextNode($textNode);
        $errorObj->addError(Argument::cetera())->shouldNotHaveBeenCalled();
    }

    function it_ignores_text_nodes_with_valid_content(TextNode $textNode, $errorObj)
    {
        $textNode->getValue()->willReturn('abc');
        $textNode->getValidationRegexp()->willReturn('/abc/');

        $this->beforeTextNode($textNode);
        $errorObj->addError(Argument::cetera())->shouldNotHaveBeenCalled();
    }

    function it_captures_invalid_repetitions(RepetitionsNode $node, $errorObj)
    {
        $this->beforeRepetitionsNode($this->a_failing_regexp($node));
        $errorObj->addError(Argument::type('string'), Argument::cetera())->shouldHaveBeenCalledTimes(1);
    }

    function it_captures_invalid_bgc_customer_numbers(BgcNumberNode $node, $errorObj)
    {
        $this->beforeBgcNumberNode($this->a_failing_regexp($node));
        $errorObj->addError(Argument::type('string'), Argument::cetera())->shouldHaveBeenCalledTimes(1);
    }

    function it_captures_invalid_payer_numbers(PayerNumberNode $node, $errorObj)
    {
        $this->beforePayerNumberNode($this->a_failing_regexp($node));
        $errorObj->addError(Argument::type('string'), Argument::cetera())->shouldHaveBeenCalledTimes(1);
    }
}
