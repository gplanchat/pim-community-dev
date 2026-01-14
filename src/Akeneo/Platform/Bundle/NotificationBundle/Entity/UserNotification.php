<?php

namespace Akeneo\Platform\Bundle\NotificationBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * UserNotification entity
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class UserNotification implements UserNotificationInterface
{
    /** @var int */
    protected $id;

    /** @var bool */
    protected $viewed = false;

    /** @var NotificationInterface */
    protected $notification;

    /** @var UserInterface */
    protected $user;

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
    public function setNotification(NotificationInterface $notification)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setUser(UserInterface $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUser()
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setViewed($viewed)
    {
        $this->viewed = $viewed;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isViewed()
    {
        return $this->viewed;
    }
}
