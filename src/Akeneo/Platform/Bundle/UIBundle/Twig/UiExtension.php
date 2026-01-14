<?php

namespace Akeneo\Platform\Bundle\UIBundle\Twig;

use Akeneo\Platform\Bundle\UIBundle\Twig\Parser\PlaceholderTokenParser;
use Twig\Extension\AbstractExtension;

class UiExtension extends AbstractExtension
{
    protected $placeholders;

    protected $wrapClassName;

    public function __construct($placeholders, $wrapClassName)
    {
        $this->placeholders = $placeholders;
        $this->wrapClassName = $wrapClassName;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getTokenParsers()
    {
        return [
            new PlaceholderTokenParser($this->placeholders, $this->wrapClassName)
        ];
    }
}
