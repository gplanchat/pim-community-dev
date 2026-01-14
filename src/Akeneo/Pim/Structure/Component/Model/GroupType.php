<?php

namespace Akeneo\Pim\Structure\Component\Model;

use Akeneo\Tool\Component\Localization\Model\TranslationInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Group type entity
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class GroupType implements GroupTypeInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $code;

    /** @var ArrayCollection */
    protected $groups;

    /**
     * Used locale to override Translation listener's locale
     * this is not a mapped field of entity metadata, just a simple property
     *
     * @var string
     */
    protected $locale;

    /** @var \Doctrine\Common\Collections\ArrayCollection */
    protected $translations;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
        $this->groups = new ArrayCollection();
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
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Returns the code
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
    public function getTranslation(?string $locale = null): ?GroupTypeTranslationInterface
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
        return GroupTypeTranslation::class;
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
    public function getReference()
    {
        return $this->code;
    }
}
