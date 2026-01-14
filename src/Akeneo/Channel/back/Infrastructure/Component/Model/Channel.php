<?php

namespace Akeneo\Channel\Infrastructure\Component\Model;

use Akeneo\Category\Infrastructure\Component\Model\CategoryInterface;
use Akeneo\Channel\Infrastructure\Component\Event\ChannelCategoryHasBeenUpdated;
use Akeneo\Tool\Component\Localization\Model\TranslationInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Channel entity
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Channel implements ChannelInterface
{
    /** @var int $id */
    protected $id;

    /** @var string $code */
    protected $code;

    /** @var CategoryInterface $category */
    protected $category;

    /** @var ArrayCollection $currencies */
    protected $currencies;

    /** @var ArrayCollection $locales */
    protected $locales;

    /**
     * Used locale to override Translation listener's locale
     * this is not a mapped field of entity metadata, just a simple property
     *
     * @var string
     */
    protected $locale;

    /** @var ChannelTranslation[] */
    protected $translations;

    /** @var array $conversionUnits */
    protected $conversionUnits = [];

    /** @var array|ChannelEvent[] */
    private $events = [];

    public function __construct()
    {
        $this->currencies = new ArrayCollection();
        $this->locales = new ArrayCollection();
        $this->translations = new ArrayCollection();
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
     * Set id
     *
     * @param int $id
     *
     * @return Channel
     */
    public function setId($id)
    {
        $this->id = $id;

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
    public function setLocale($locale)
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getTranslation(?string $locale = null)
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
        return ChannelTranslation::class;
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
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setCategory(CategoryInterface $category)
    {
        if ($this->category === null) {
            $this->category = $category;

            return $this;
        }

        if ($this->category->getCode() !== $category->getCode()) {
            $previousCategoryCode = $this->category->getCode();
            $this->category = $category;
            $this->addEvent(new ChannelCategoryHasBeenUpdated($this->code, $previousCategoryCode, $category->getCode()));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getCurrencies()
    {
        return $this->currencies;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setCurrencies(array $currencies)
    {
        foreach ($this->currencies as $currency) {
            if (!in_array($currency, $currencies)) {
                $this->removeCurrency($currency);
            }
        }

        foreach ($currencies as $currency) {
            $this->addCurrency($currency);
        }
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addCurrency(CurrencyInterface $currency)
    {
        if (!$this->hasCurrency($currency)) {
            $this->currencies[] = $currency;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function removeCurrency(CurrencyInterface $currency)
    {
        $this->currencies->removeElement($currency);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLocales()
    {
        return $this->locales;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLocaleCodes()
    {
        return $this->locales->map(
            function ($locale) {
                return $locale->getCode();
            }
        )->toArray();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setLocales(array $locales)
    {
        foreach ($this->locales as $locale) {
            if (!in_array($locale, $locales)) {
                $this->removeLocale($locale);
            }
        }

        foreach ($locales as $locale) {
            $this->addLocale($locale);
        }
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addLocale(LocaleInterface $locale)
    {
        if (!$this->hasLocale($locale)) {
            $this->locales[] = $locale;
            $locale->addChannel($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function removeLocale(LocaleInterface $locale)
    {
        if ($this->locales->removeElement($locale)) {
            $locale->removeChannel($this);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function hasLocale(LocaleInterface $locale)
    {
        return $this->locales->contains($locale);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function hasCurrency(CurrencyInterface $currency)
    {
        return $this->currencies->contains($currency);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setConversionUnits(array $conversionUnits)
    {
        $this->conversionUnits = $conversionUnits;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getConversionUnits()
    {
        return $this->conversionUnits;
    }

    /**
     * {@inheritdoc}
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
    public function getReference()
    {
        return $this->code;
    }

    /**
     * @return array|ChannelEvent[]
     */
    #[\Override]
    public function popEvents(): array
    {
        $events = $this->events;
        $this->events = [];

        return $events;
    }

    private function addEvent($event): void
    {
        $this->events[] = $event;
    }
}
