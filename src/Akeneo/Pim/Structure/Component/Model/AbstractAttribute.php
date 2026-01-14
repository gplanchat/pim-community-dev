<?php

namespace Akeneo\Pim\Structure\Component\Model;

use Akeneo\Channel\Infrastructure\Component\Model\LocaleInterface;
use Akeneo\Pim\Structure\Component\AttributeTypes;
use Akeneo\Tool\Component\Localization\Model\TranslationInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Abstract product attribute
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
abstract class AbstractAttribute implements AttributeInterface
{
    /** @var int|string */
    protected $id;

    /**
     * Attribute code
     *
     * @var string
     */
    protected $code;

    /**
     * Attribute label
     *
     * @var string
     */
    protected $label;

    /**
     * Entity type (FQCN)
     *
     * @var string
     */
    protected $entityType;

    /**
     * Attribute type
     *
     * @var string
     */
    protected $type;

    /**
     * Kind of field to store values
     *
     * @var string
     */
    protected $backendType;

    /** @var \DateTime */
    protected $created;

    /** @var \DateTime */
    protected $updated;

    /**
     * Is attribute is required
     *
     * @var bool
     */
    protected $required;

    /**
     * Is attribute value is required
     *
     * @var bool
     */
    protected $unique;

    /** @var bool */
    protected $localizable;

    /** @var bool */
    protected $scopable;

    /** @var array */
    protected $properties;

    /** @var ArrayCollection */
    protected $options;

    /** @var int */
    protected $sortOrder = 0;

    /** @var AttributeGroupInterface $group */
    protected $group;

    /** @var bool */
    protected $useableAsGridFilter;

    /** @var ArrayCollection */
    protected $availableLocales;

    /** @var ArrayCollection */
    protected $families;

    /** @var int */
    protected $maxCharacters;

    /** @var string */
    protected $validationRule;

    /** @var string */
    protected $validationRegexp;

    /** @var bool */
    protected $wysiwygEnabled;

    /** @var float */
    protected $numberMin;

    /** @var float */
    protected $numberMax;

    /** @var bool */
    protected $decimalsAllowed;

    /** @var bool */
    protected $negativeAllowed;

    /** @var \DateTime */
    protected $dateMin;

    /** @var \DateTime */
    protected $dateMax;

    /** @var string */
    protected $metricFamily;

    /** @var string */
    protected $defaultMetricUnit;

    /**
     * @var float expressed in MB so decimal is needed for values < 1 MB
     */
    protected $maxFileSize;

    /** @var array */
    protected $allowedExtensions;

    /** @var int */
    protected $minimumInputLength;

    /**
     * Used locale to override Translation listener's locale
     * this is not a mapped field of entity metadata, just a simple property
     *
     * @var string
     */
    protected $locale;

    /** @var ArrayCollection */
    protected $translations;

    /** @var string[] */
    protected array $guidelines = [];

    protected ?array $rawTableConfiguration = null;

    protected bool $mainIdentifier;

    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->required = false;
        $this->unique = false;
        $this->localizable = false;
        $this->scopable = false;
        $this->useableAsGridFilter = false;
        $this->availableLocales = new ArrayCollection();
        $this->families = new ArrayCollection();
        $this->translations = new ArrayCollection();
        $this->validationRule = null;
        $this->properties = [];
        $this->mainIdentifier = false;
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
    public function setCode($code)
    {
        $this->code = $code;

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
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * {@inheritdoc}
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
    public function setBackendType($type)
    {
        $this->backendType = $type;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getBackendType()
    {
        return $this->backendType;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttributeType()
    {
        return $this->getType();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setRequired($required)
    {
        $this->required = $required;

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

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setUnique($unique)
    {
        $this->unique = $unique;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isUnique()
    {
        return $this->unique;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setLocalizable($localizable)
    {
        $this->localizable = $localizable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isLocalizable()
    {
        return $this->localizable;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setScopable($scopable)
    {
        $this->scopable = $scopable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isScopable()
    {
        return $this->scopable;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addOption(AttributeOptionInterface $option)
    {
        $this->options[] = $option;
        $option->setAttribute($this);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function removeOption(AttributeOptionInterface $option)
    {
        $this->options->removeElement($option);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getProperties()
    {
        return $this->properties;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setProperties(array $properties)
    {
        $this->properties = $properties;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getProperty($property)
    {
        return isset($this->properties[$property]) ? $this->properties[$property] : null;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setProperty($property, $value)
    {
        $this->properties[$property] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getGroupSequence()
    {
        $groups = ['Attribute', $this->getType()];

        if ($this->isUnique()) {
            $groups[] = 'unique';
        }
        if ($this->isScopable()) {
            $groups[] = 'scopable';
        }
        if ($this->isLocalizable()) {
            $groups[] = 'localizable';
        }
        if ($rule = $this->getValidationRule()) {
            $groups[] = $rule;
        }

        return $groups;
    }

    /**
     * To string
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
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setGroup(?AttributeGroupInterface $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isUseableAsGridFilter()
    {
        return $this->useableAsGridFilter;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setUseableAsGridFilter($useableAsGridFilter)
    {
        $this->useableAsGridFilter = $useableAsGridFilter;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addAvailableLocale(LocaleInterface $availableLocale)
    {
        $this->availableLocales[] = $availableLocale;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function removeAvailableLocale(LocaleInterface $availableLocale)
    {
        $this->availableLocales->removeElement($availableLocale);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAvailableLocales()
    {
        return $this->availableLocales;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAvailableLocaleCodes(): array
    {
        $codes = [];
        foreach ($this->getAvailableLocales() as $locale) {
            $codes[] = $locale->getCode();
        }

        return $codes;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function hasLocaleSpecific(LocaleInterface $locale)
    {
        return in_array($locale->getCode(), $this->getAvailableLocaleCodes());
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addFamily(FamilyInterface $family)
    {
        $this->families[] = $family;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function removeFamily(FamilyInterface $family)
    {
        $this->families->removeElement($family);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFamilies()
    {
        return $this->families->isEmpty() ? null : $this->families;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setFamilies(ArrayCollection $families)
    {
        $this->families = $families;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMaxCharacters()
    {
        return $this->maxCharacters;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setMaxCharacters($maxCharacters)
    {
        $this->maxCharacters = $maxCharacters;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getValidationRule()
    {
        return $this->validationRule;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setValidationRule($validationRule)
    {
        $this->validationRule = $validationRule;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getValidationRegexp()
    {
        return $this->validationRegexp;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setValidationRegexp($validationRegexp)
    {
        $this->validationRegexp = $validationRegexp;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isWysiwygEnabled()
    {
        return $this->wysiwygEnabled;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setWysiwygEnabled($wysiwygEnabled)
    {
        $this->wysiwygEnabled = $wysiwygEnabled;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getNumberMin()
    {
        return $this->numberMin;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setNumberMin($numberMin)
    {
        $this->numberMin = $numberMin;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getNumberMax()
    {
        return $this->numberMax;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setNumberMax($numberMax)
    {
        $this->numberMax = $numberMax;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isDecimalsAllowed()
    {
        return $this->decimalsAllowed;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setDecimalsAllowed($decimalsAllowed)
    {
        $this->decimalsAllowed = $decimalsAllowed;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isNegativeAllowed()
    {
        return $this->negativeAllowed;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setNegativeAllowed($negativeAllowed)
    {
        $this->negativeAllowed = $negativeAllowed;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getDateMin()
    {
        return $this->dateMin;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setDateMin($dateMin)
    {
        $this->dateMin = $dateMin;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getDateMax()
    {
        return $this->dateMax;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setDateMax($dateMax)
    {
        $this->dateMax = $dateMax;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMetricFamily()
    {
        return $this->metricFamily;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setMetricFamily($metricFamily)
    {
        $this->metricFamily = $metricFamily;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getDefaultMetricUnit()
    {
        return $this->defaultMetricUnit;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setDefaultMetricUnit($defaultMetricUnit)
    {
        $this->defaultMetricUnit = $defaultMetricUnit;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMaxFileSize()
    {
        return $this->maxFileSize;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setMaxFileSize($maxFileSize)
    {
        $this->maxFileSize = $maxFileSize;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions ? array_map('trim', explode(',', $this->allowedExtensions)) : [];
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setAllowedExtensions($allowedExtensions)
    {
        $allowedExtensions = explode(',', strtolower($allowedExtensions));
        $allowedExtensions = array_unique(array_map('trim', $allowedExtensions));
        $this->allowedExtensions = implode(',', $allowedExtensions);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMinimumInputLength()
    {
        return $this->minimumInputLength;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setMinimumInputLength($minimumInputLength)
    {
        $this->minimumInputLength = $minimumInputLength;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setParameters($parameters)
    {
        foreach ($parameters as $code => $value) {
            $method = 'set'.ucfirst($code);
            if (!method_exists($this, $method)) {
                throw new \Exception(sprintf('The parameter "%s" does not exist.', $code));
            }
            $this->$method($value);
        }

        return $this;
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
    public function getTranslation(?string $locale = null): ?AttributeTranslationInterface
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
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addTranslation(TranslationInterface $translation)
    {
        if (!$this->translations->contains($translation)) {
            $this->translations->add($translation);
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
        return AttributeTranslation::class;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLabel()
    {
        $translated = ($this->getTranslation()) ? $this->getTranslation()->getLabel() : null;

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
    public function getReference()
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setAttributeType($type)
    {
        return $this->setType($type);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setType($type)
    {
        $this->type = $type;
        if (AttributeTypes::IDENTIFIER === $this->type) {
            $this->required = true;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isLocaleSpecific()
    {
        if ($this->availableLocales->isEmpty()) {
            return false;
        } else {
            return !empty($this->availableLocales);
        }
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getReferenceDataName()
    {
        if (!$this->isBackendTypeReferenceData()) {
            return null;
        }

        return $this->getProperty('reference_data_name');
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setReferenceDataName($name)
    {
        $this->setProperty('reference_data_name', $name);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isBackendTypeReferenceData()
    {
        return in_array($this->getBackendType(), [
            AttributeTypes::BACKEND_TYPE_REF_DATA_OPTION,
            AttributeTypes::BACKEND_TYPE_REF_DATA_OPTIONS
        ]);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getGuidelines(): array
    {
        return $this->guidelines;
    }

    #[\Override]
    public function addGuidelines(string $locale, string $localeGuidelines): void
    {
        $this->guidelines[$locale] = $localeGuidelines;
    }


    #[\Override]
    public function removeGuidelines(string $locale): void
    {
        unset($this->guidelines[$locale]);
    }

    public function getGuidelinesLocaleCodes(): array
    {
        return array_keys($this->guidelines);
    }

    #[\Override]
    public function getRawTableConfiguration(): ?array
    {
        return $this->rawTableConfiguration;
    }

    #[\Override]
    public function setRawTableConfiguration(?array $rawTableConfiguration): void
    {
        $this->rawTableConfiguration = $rawTableConfiguration;
    }

    #[\Override]
    public function isMainIdentifier(): bool
    {
        return $this->mainIdentifier;
    }
}
