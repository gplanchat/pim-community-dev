<?php

namespace Akeneo\Pim\Structure\Component\Model;

use Akeneo\Channel\Infrastructure\Component\Model\ChannelInterface;

/**
 * The attribute requirement for a channel and a family
 *
 * @author    Gildas Quéméner <gildas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AttributeRequirement implements AttributeRequirementInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var Family
     */
    protected $family;

    /**
     * @var AttributeInterface
     */
    protected $attribute;

    /**
     * @var ChannelInterface
     */
    protected $channel;

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setFamily(FamilyInterface $family)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setAttribute(AttributeInterface $attribute)
    {
        $this->attribute = $attribute;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttribute()
    {
        return $this->attribute;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttributeCode()
    {
        return $this->attribute->getCode();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setChannel(ChannelInterface $channel)
    {
        $this->channel = $channel;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getChannel()
    {
        return $this->channel;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getChannelCode()
    {
        return $this->channel->getCode();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setRequired($required)
    {
        $this->required = (bool) $required;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isRequired()
    {
        return $this->required;
    }
}
