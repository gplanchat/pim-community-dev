<?php

namespace spec\Pim\Bundle\CatalogBundle\Validator\ConstraintGuesser;

use PhpSpec\ObjectBehavior;
use Pim\Bundle\CatalogBundle\Model\AttributeInterface;

class UrlGuesserSpec extends ObjectBehavior
{
    function let(AttributeInterface $text, AttributeInterface $identifier, AttributeInterface $textarea)
    {
        $text->getAttributeType()->willReturn('pim_catalog_text');
        $identifier->getAttributeType()->willReturn('pim_catalog_identifier');
        $textarea->getAttributeType()->willReturn('pim_catalog_textarea');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Pim\Bundle\CatalogBundle\Validator\ConstraintGuesser\UrlGuesser');
    }

    function it_is_an_attribute_constraint_guesser()
    {
        $this->shouldImplement('Pim\Bundle\CatalogBundle\Validator\ConstraintGuesserInterface');
    }

    function it_supports_text_attributes($text)
    {
        $this->supportAttribute($text)->shouldReturn(true);
    }

    function it_doesnt_support_other_attributes($identifier, $textarea, AttributeInterface $image)
    {
        $this->supportAttribute($identifier)->shouldReturn(false);
        $this->supportAttribute($textarea)->shouldReturn(false);

        $image->getAttributeType()->willReturn('pim_catalog_image');
        $this->supportAttribute($image)->shouldReturn(false);
    }

    function it_can_guess_url($text)
    {
        $text->getValidationRule()->willReturn('url');
        $textConstraints = $this->guessConstraints($text);

        $textConstraints->shouldHaveCount(1);

        $firstConstraint = $textConstraints[0];
        $firstConstraint->shouldBeAnInstanceOf('Symfony\Component\Validator\Constraints\Url');
    }

    function it_do_not_guess_not_url($text)
    {
        $text->getValidationRule()->willReturn('not_url');
        $textConstraints = $this->guessConstraints($text);

        $textConstraints->shouldReturn([]);
    }
}
