<?php

declare(strict_types=1);

namespace Akeneo\Test\Acceptance\Channel;

use Akeneo\Channel\Infrastructure\Component\Model\ChannelInterface;
use Akeneo\Channel\Infrastructure\Component\Model\CurrencyInterface;
use Akeneo\Channel\Infrastructure\Component\Repository\ChannelRepositoryInterface;
use Akeneo\Test\Acceptance\Common\NotImplementedException;
use Akeneo\Tool\Component\StorageUtils\Saver\SaverInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

final class InMemoryChannelRepository implements ChannelRepositoryInterface, SaverInterface
{
    /** @var Collection */
    private $channels;

    public function __construct(array $channels = [])
    {
        $this->channels = new ArrayCollection($channels);
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
        return $this->channels->get($code);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function save($channel, array $options = [])
    {
        $this->channels->set($channel->getCode(), $channel);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        $channels = [];
        foreach ($this->channels as $channel) {
            $keepThisChannel = true;
            foreach ($criteria as $key => $value) {
                $getter = sprintf('get%s', ucfirst($key));
                if ($channel->$getter() !== $value) {
                    $keepThisChannel = false;
                }
            }

            if ($keepThisChannel) {
                $channels[] = $channel;
            }
        }

        return $channels;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function countAll()
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getChannelCodes()
    {
        return $this->channels->map(function (ChannelInterface $channel): string {
            return $channel->getCode();
        })->getValues();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFullChannels()
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getChannelCountUsingCurrency(CurrencyInterface $currency)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLabelsIndexedByCode($localeCode)
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
        return array_values($this->channels->toArray());
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function findOneBy(array $criteria)
    {
        $keepThisChannel = true;
        foreach ($this->channels as $channel) {
            foreach ($criteria as $key => $value) {
                $getter = sprintf('get%s', ucfirst($key));
                if ($channel->$getter() !== $value) {
                    $keepThisChannel = false;
                }
            }

            if ($keepThisChannel) {
                return $channel;
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
