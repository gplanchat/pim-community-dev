<?php

namespace Akeneo\Pim\Enrichment\Component\Comment\Model;

use Akeneo\UserManagement\Component\Model\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\UuidInterface;

/**
 * Comment model
 *
 * @author    Olivier Soulet <olivier.soulet@akeneo.com>
 * @author    Julien Janvier <julien.janvier@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Comment implements CommentInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $resourceName;

    /** @var string */
    protected $resourceId;

    protected UuidInterface $resourceUuid;

    /** @var UserInterface */
    protected $author;

    /** @var string */
    protected $body;

    /** @var \DateTime */
    protected $createdAt;

    /** @var \DateTime */
    protected $repliedAt;

    /** @var CommentInterface */
    protected $parent;

    /** @var ArrayCollection[] */
    protected $children;

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
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setResourceName($resourceName)
    {
        $this->resourceName = $resourceName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getResourceName()
    {
        return $this->resourceName;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setAuthor(UserInterface $author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setParent(CommentInterface $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setRepliedAt(\DateTime $repliedAt)
    {
        $this->repliedAt = $repliedAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getRepliedAt()
    {
        return $this->repliedAt;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setChildren(ArrayCollection $children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setResourceUuid(UuidInterface $resourceUuid): void
    {
        $this->resourceUuid = $resourceUuid;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getResourceUuid(): ?UuidInterface
    {
        return $this->resourceUuid;
    }
}
