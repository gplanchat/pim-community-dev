<?php

namespace Akeneo\Channel\Infrastructure\Component\Model;

use Akeneo\Tool\Component\Versioning\Model\VersionableInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Locale entity
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Locale implements LocaleInterface, VersionableInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $code;

    /**
     * @var bool
     */
    protected $activated = false;

    /**
     * @var ArrayCollection
     */
    protected $channels;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->channels = new ArrayCollection();
    }

    /**
     * To string
     *
     * @return string
     */
    #[\Override]
    public function __toString()
    {
        return $this->code;
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
    public function getLanguage()
    {
        return (null === $this->code) ? null : substr($this->code, 0, 2);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isActivated(): bool
    {
        return $this->activated;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function hasChannel(ChannelInterface $channel)
    {
        return $this->channels->contains($channel);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addChannel(ChannelInterface $channel)
    {
        if (!$this->channels->contains($channel)) {
            $this->channels->add($channel);
            $this->activated = true;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function removeChannel(ChannelInterface $channel)
    {
        $this->channels->removeElement($channel);
        if ($this->channels->count() === 0) {
            $this->activated = false;
        }

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
    public function getName()
    {
        return null !== $this->code ? \Locale::getDisplayName($this->code) : null;
    }
}
