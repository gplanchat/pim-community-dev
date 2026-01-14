<?php

namespace Akeneo\Platform\Bundle\NotificationBundle\Entity;

/**
 * Notification entity
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Notification implements NotificationInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $route;

    /** @var array */
    protected $routeParams = [];

    /** @var string */
    protected $message;

    /** @var array */
    protected $messageParams = [];

    /** @var string */
    protected $comment;

    /** @var \DateTime */
    protected $created;

    /** @var string */
    protected $type;

    /** @var array */
    protected $context = [];

    public function __construct()
    {
        $this->created = new \DateTime('now');
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
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setType($type)
    {
        $this->type = $type;

        return $this;
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
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setRouteParams(array $routeParams)
    {
        $this->routeParams = $routeParams;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getRouteParams()
    {
        return $this->routeParams;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setMessageParams(array $messageParams)
    {
        $this->messageParams = $messageParams;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMessageParams()
    {
        return $this->messageParams;
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
    public function setContext(array $context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getContext()
    {
        return $this->context;
    }
}
