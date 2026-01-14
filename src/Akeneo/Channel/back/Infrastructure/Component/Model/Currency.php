<?php

namespace Akeneo\Channel\Infrastructure\Component\Model;

/**
 * Currency entity
 *
 * @author    Romain Monceau <romain@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Currency implements CurrencyInterface
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
    protected $activated;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->activated = true;
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
     * Set id
     *
     * @param int $id
     *
     * @return Currency
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
    public function isActivated()
    {
        return $this->activated;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function toggleActivation()
    {
        $this->activated = !$this->activated;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setActivated($activated)
    {
        $this->activated = $activated;

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
