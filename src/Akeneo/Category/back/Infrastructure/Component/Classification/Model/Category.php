<?php

namespace Akeneo\Category\Infrastructure\Component\Classification\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Implementation of CategoryInterface.
 *
 * @author    Willy Mesnage <willy.mesnage@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class Category implements CategoryInterface
{
    /** @var int */
    protected $id;

    /** @var string */
    protected $code;

    /** @var int */
    protected $left;

    /** @var int */
    protected $level;

    /** @var int */
    protected $right;

    /** @var int */
    protected $root;

    /** @var CategoryInterface */
    protected $parent;

    /** @var Collection */
    protected $children;

    public function __construct()
    {
        $this->children = new ArrayCollection();
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
    public function setCode($code)
    {
        $this->code = $code;

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
    public function setLeft($left)
    {
        $this->left = $left;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setRight($right)
    {
        $this->right = $right;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getRight()
    {
        return $this->right;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setRoot($root)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setParent(?CategoryInterface $parent = null)
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
    public function addChild(CategoryInterface $child)
    {
        $child->setParent($this);
        $this->children[] = $child;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function removeChild(CategoryInterface $children)
    {
        $this->children->removeElement($children);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function hasChildren()
    {
        return count($this->getChildren()) > 0;
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
    public function isRoot()
    {
        return null === $this->getParent();
    }
}
