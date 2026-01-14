<?php

namespace Akeneo\Pim\Structure\Component\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class AttributeOption implements AttributeOptionInterface
{
    protected ?int $id = null;
    protected ?string $code = null;
    protected Collection $optionValues;
    protected ?int $sortOrder = null;

    /**
     * Overrided to change target entity name
     */
    protected ?AttributeInterface $attribute = null;

    /**
     * Not persisted, allows to define the value locale
     */
    protected ?string $locale = null;

    public function __construct()
    {
        $this->optionValues = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getId(): ?int
    {
        return $this->id;
    }

    #[\Override]
    public function setId($id): static
    {
        $this->id = $id;

        return $this;
    }

    #[\Override]
    public function getAttribute(): ?AttributeInterface
    {
        return $this->attribute;
    }

    #[\Override]
    public function setAttribute(?AttributeInterface $attribute = null): static
    {
        $this->attribute = $attribute;

        return $this;
    }

    #[\Override]
    public function getOptionValues(): Collection
    {
        return $this->optionValues;
    }

    #[\Override]
    public function getLocale(): ?string
    {
        return $this->locale;
    }

    #[\Override]
    public function setLocale($locale): static
    {
        $this->locale = $locale;

        return $this;
    }

    #[\Override]
    public function setSortOrder($sortOrder): static
    {
        if ($sortOrder !== null) {
            $this->sortOrder = $sortOrder;
        }

        return $this;
    }

    #[\Override]
    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    #[\Override]
    public function setCode($code): static
    {
        $this->code = (string) $code;

        return $this;
    }

    #[\Override]
    public function getCode(): ?string
    {
        return $this->code;
    }

    #[\Override]
    public function __toString(): string
    {
        $value = $this->getOptionValue();

        return ($value && $value->getValue()) ? $value->getValue() : '[' . $this->getCode() . ']';
    }

    #[\Override]
    public function getReference(): ?string
    {
        if (null === $this->code) {
            return null;
        }

        return ($this->attribute ? $this->attribute->getCode() : '') . '.' . $this->code;
    }

    #[\Override]
    public function getTranslation(): ?AttributeOptionValueInterface
    {
        $value = $this->getOptionValue();

        if (!$value) {
            $value = new AttributeOptionValue();
            $value->setLocale($this->locale);
            $this->addOptionValue($value);
        }

        return $value;
    }

    #[\Override]
    public function addOptionValue(AttributeOptionValueInterface $value): static
    {
        $this->optionValues[] = $value;
        $value->setOption($this);

        return $this;
    }

    #[\Override]
    public function removeOptionValue(AttributeOptionValueInterface $value): static
    {
        $this->optionValues->removeElement($value);

        return $this;
    }

    #[\Override]
    public function getOptionValue(): ?AttributeOptionValueInterface
    {
        $locale = $this->locale;
        $values = $this->optionValues->filter(
            function ($value) use ($locale): bool {
                return \strtolower($value->getLocale()) === \strtolower($locale);
            }
        );

        if ($values->isEmpty()) {
            return null;
        }

        return $values->first();
    }
}
