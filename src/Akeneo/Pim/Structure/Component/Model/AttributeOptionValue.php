<?php

namespace Akeneo\Pim\Structure\Component\Model;

/**
 * Attribute option values
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class AttributeOptionValue implements AttributeOptionValueInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var AttributeOptionInterface
     */
    protected $option;

    /**
     * LocaleInterface scope
     *
     * @var string
     */
    protected $locale;

    /**
     * @var string
     */
    protected $value;

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setOption(AttributeOptionInterface $option)
    {
        $this->option = $option;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getOption()
    {
        return $this->option;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getValue()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLabel()
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setLabel($label)
    {
        $this->value = (string) $label;

        return $this;
    }
}
