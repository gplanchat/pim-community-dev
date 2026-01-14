<?php

declare(strict_types=1);

namespace Akeneo\Test\Acceptance\Currency;

use Akeneo\Channel\Infrastructure\Component\Model\Currency;
use Akeneo\Channel\Infrastructure\Component\Model\CurrencyInterface;
use Akeneo\Channel\Infrastructure\Component\Repository\CurrencyRepositoryInterface;
use Akeneo\Test\Acceptance\Common\NotImplementedException;
use Akeneo\Tool\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Akeneo\Tool\Component\StorageUtils\Saver\SaverInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ObjectRepository;

final class InMemoryCurrencyRepository implements
    SaverInterface,
    IdentifiableObjectRepositoryInterface,
    ObjectRepository,
    CurrencyRepositoryInterface
{
    /** @var Collection */
    private $currencies;

    public function __construct()
    {
        $this->currencies = new ArrayCollection();
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
        return $this->currencies->get($code);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function save($currency, array $options = [])
    {
        $this->currencies->set($currency->getCode(), $currency);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        $currencies = [];
        foreach ($this->currencies as $currency) {
            $keepThisCurrency = true;
            foreach ($criteria as $key => $value) {
                $getter = sprintf('get%s', ucfirst($key));
                if ($currency->$getter() !== $value) {
                    $keepThisCurrency = false;
                }
            }

            if ($keepThisCurrency) {
                $currencies[] = $currency;
            }
        }

        return $currencies;
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
        return array_values($this->currencies->toArray());
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function findOneBy(array $criteria)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getClassName()
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getActivatedCurrencies()
    {
        return array_filter($this->currencies->toArray(), function (CurrencyInterface $currency): bool {
            return $currency->isActivated();
        });
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getActivatedCurrencyCodes()
    {
        $activatedCurrencyCodes = [];

        /** @var Currency $currency */
        foreach ($this->currencies as $currency) {
            if ($currency->isActivated()) {
                $activatedCurrencyCodes[] = $currency->getCode();
            }
        }

        return $activatedCurrencyCodes;
    }
}
