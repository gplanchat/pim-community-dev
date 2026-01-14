<?php

declare(strict_types=1);

namespace Akeneo\Test\Acceptance\Locale;

use Akeneo\Channel\Infrastructure\Component\Model\ChannelInterface;
use Akeneo\Channel\Infrastructure\Component\Repository\LocaleRepositoryInterface;
use Akeneo\Test\Acceptance\Common\NotImplementedException;
use Akeneo\Tool\Component\StorageUtils\Saver\SaverInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class InMemoryLocaleRepository implements LocaleRepositoryInterface, SaverInterface
{
    /** @var Collection */
    private $locales;

    public function __construct()
    {
        $this->locales = new ArrayCollection();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getIdentifierProperties()
    {
        return ['code'];
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function findOneByIdentifier($code)
    {
        return $this->locales->get($code);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function save($locale, array $options = [])
    {
        $this->locales->set($locale->getCode(), $locale);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        $locales = [];
        foreach ($this->locales as $locale) {
            $keepThisLocale = true;
            foreach ($criteria as $key => $value) {
                $getter = sprintf('get%s', ucfirst($key));
                if ($locale->$getter() !== $value) {
                    $keepThisLocale = false;
                }
            }

            if ($keepThisLocale) {
                $locales[] = $locale;
            }
        }

        return $locales;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getActivatedLocales()
    {
        return $this->locales->getValues();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getActivatedLocaleCodes()
    {
        $localeCodes = [];
        foreach ($this->locales as $locale) {
            if ($locale->isActivated()) {
                $localeCodes[] = $locale->getCode();
            }
        }

        return $localeCodes;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getActivatedLocalesQB()
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getDeletedLocalesForChannel(ChannelInterface $channel)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function countAllActivated()
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function find($id)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function findAll()
    {
        return $this->locales->toArray();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function findOneBy(array $criteria)
    {
        $keepThisLocale = true;
        foreach ($this->locales as $locale) {
            foreach ($criteria as $key => $value) {
                $getter = sprintf('get%s', ucfirst($key));
                if ($locale->$getter() !== $value) {
                    $keepThisLocale = false;
                }
            }

            if ($keepThisLocale) {
                return $locale;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getClassName()
    {
        throw new NotImplementedException(__METHOD__);
    }
}
