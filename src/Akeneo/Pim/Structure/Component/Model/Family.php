<?php

namespace Akeneo\Pim\Structure\Component\Model;

use Akeneo\Pim\Structure\Component\AttributeTypes;
use Akeneo\Tool\Component\Localization\Model\TranslationInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Webmozart\Assert\Assert;

/**
 * Family entity
 *
 * @author    Filips Alpe <filips@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Family implements FamilyInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $code;

    /** @var Collection */
    protected $attributes;

    /**
     * Used locale to override Translation listener's locale
     * this is not a mapped field of entity metadata, just a simple property
     *
     * @var string
     */
    protected $locale;

    /** @var Collection */
    protected $translations;

    /** @var AttributeInterface */
    protected $attributeAsLabel;

    /** @var AttributeInterface */
    protected $attributeAsImage;

    /** @var Collection */
    protected $requirements;

    /** @var \DateTime */
    protected $created;

    /** @var \DateTime */
    protected $updated;

    /** @var Collection */
    protected $familyVariants;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->requirements = new ArrayCollection();
        $this->familyVariants = new ArrayCollection();
    }

    /**
     * Returns the label of the family
     *
     * @return string
     */
    #[\Override]
    public function __toString()
    {
        return $this->getLabel();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get created datetime
     *
     * @return \DateTime
     */
    #[\Override]
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set created datetime
     *
     * @param \DateTime $created
     *
     * @return Family
     */
    #[\Override]
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get updated datetime
     *
     * @return \DateTime
     */
    #[\Override]
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set updated datetime
     *
     * @param \DateTime $updated
     *
     * @return Family
     */
    #[\Override]
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getCode()
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addAttribute(AttributeInterface $attribute)
    {
        if (!$this->attributes->contains($attribute)) {
            $this->attributes->add($attribute);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    #[\Override]
    public function removeAttribute(AttributeInterface $attribute)
    {
        $this->attributes->removeElement($attribute);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttributeCodes()
    {
        $codes = [];
        foreach ($this->attributes as $attribute) {
            $codes[] = $attribute->getCode();
        }

        return $codes;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getGroupedAttributes()
    {
        $result = [];
        foreach ($this->attributes as $attribute) {
            $result[(string) $attribute->getGroup()][] = $attribute;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function hasAttribute(AttributeInterface $attribute)
    {
        return $this->hasAttributeCode($attribute->getCode());
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function hasAttributeCode($attributeCode)
    {
        return in_array($attributeCode, $this->getAttributeCodes());
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setAttributeAsLabel(AttributeInterface $attributeAsLabel)
    {
        $this->attributeAsLabel = $attributeAsLabel;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttributeAsLabel()
    {
        return $this->attributeAsLabel;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setAttributeAsImage(?AttributeInterface $attributeAsImage): FamilyInterface
    {
        $this->attributeAsImage = $attributeAsImage;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttributeAsImage(): ?AttributeInterface
    {
        return $this->attributeAsImage;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttributeAsLabelChoices()
    {
        return $this->attributes->filter(
            function ($attribute) {
                return in_array(
                    $attribute->getType(),
                    [AttributeTypes::TEXT, AttributeTypes::IDENTIFIER]
                );
            }
        )->toArray();
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
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getTranslation(?string $locale = null): ?FamilyTranslationInterface
    {
        $locale = $locale ?: $this->locale;
        if (null === $locale) {
            return null;
        }
        foreach ($this->getTranslations() as $translation) {
            if (\strtolower($translation->getLocale()) === \strtolower($locale)) {
                return $translation;
            }
        }

        $translationClass = $this->getTranslationFQCN();
        $translation = new $translationClass();
        $translation->setLocale($locale);
        $translation->setForeignKey($this);
        $this->addTranslation($translation);

        return $translation;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addTranslation(TranslationInterface $translation)
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->set($translation->getLocale(), $translation);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function removeTranslation(TranslationInterface $translation)
    {
        $this->translations->removeElement($translation);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getTranslationFQCN()
    {
        return FamilyTranslation::class;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLabel()
    {
        $translated = $this->getTranslation() ? $this->getTranslation()->getLabel() : null;

        return ($translated !== '' && $translated !== null) ? $translated : '['.$this->getCode().']';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setLabel($label)
    {
        $this->getTranslation()->setLabel($label);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addAttributeRequirement(AttributeRequirementInterface $requirement)
    {
        $requirementKey = $this->getAttributeRequirementKey($requirement);
        $requirements = $this->getAttributeRequirements();

        if (!isset($requirements[$requirementKey])) {
            $requirement->setFamily($this);
            $this->requirements->add($requirement);
        } else {
            $requirements[$requirementKey]->setRequired($requirement->isRequired());
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function removeAttributeRequirement(AttributeRequirementInterface $requirement)
    {
        $this->requirements->removeElement($requirement);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setAttributeRequirements(array $requirements)
    {
        foreach ($requirements as $requirement) {
            $requirement->setFamily($this);
        }
        $this->requirements = new ArrayCollection($requirements);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttributeRequirements()
    {
        $result = [];

        foreach ($this->requirements as $requirement) {
            $key = $this->getAttributeRequirementKey($requirement);
            $result[$key] = $requirement;
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttributeRequirementKey(AttributeRequirementInterface $requirement)
    {
        return sprintf(
            '%s_%s',
            $requirement->getAttributeCode(),
            $requirement->getChannelCode()
        );
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getReference()
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFamilyVariants(): Collection
    {
        return $this->familyVariants;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setFamilyVariants(Collection $familyVariants): void
    {
        $this->familyVariants = $familyVariants;
    }

    /**
     * @param AttributeInterface[] $attributes
     * @return void
     */
    #[\Override]
    public function updateAttributes(array $attributes = []): void
    {
        Assert::allIsInstanceOf($attributes, AttributeInterface::class);

        $formerAttributeCodes = $this->getAttributeCodes();
        $newAttributeCodes = \array_map(
            fn (AttributeInterface $attribute): string => $attribute->getCode(),
            $attributes
        );

        sort($formerAttributeCodes);
        sort($newAttributeCodes);

        if ($formerAttributeCodes !== $newAttributeCodes) {
            $attributeCodesToRemove = array_diff($formerAttributeCodes, $newAttributeCodes);
            $attributeCodesToAdd = array_diff($newAttributeCodes, $formerAttributeCodes);

            if (\count($attributeCodesToRemove) > 0) {
                $familyVariants = $this->getFamilyVariants();
                /** @var FamilyVariant $familyVariant */
                foreach ($familyVariants as $familyVariant) {
                    // for every family variants, we want to inform subscribers that the levels of product variants
                    // and product models have been updated
                    $familyVariant->addEvent(FamilyVariantInterface::ATTRIBUTES_WERE_UPDATED_ON_LEVEL);
                }
            }

            // removes attributes only from former attributes list
            foreach ($this->getAttributes() as $attribute) {
                if (\in_array($attribute->getCode(), $attributeCodesToRemove)) {
                    $this->removeAttribute($attribute);
                }
            }

            // adds attributes only from new attributes list
            foreach ($attributes as $attribute) {
                if (\in_array($attribute->getCode(), $attributeCodesToAdd)) {
                    $this->addAttribute($attribute);
                }
            }
        }
    }
}
