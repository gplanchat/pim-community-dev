<?php

declare(strict_types=1);

namespace Akeneo\Test\Acceptance\Category;

use Akeneo\Category\Infrastructure\Component\Classification\Model\CategoryInterface;
use Akeneo\Category\Infrastructure\Component\Classification\Repository\CategoryRepositoryInterface;
use Akeneo\Test\Acceptance\Common\NotImplementedException;
use Akeneo\Tool\Component\StorageUtils\Repository\IdentifiableObjectRepositoryInterface;
use Akeneo\Tool\Component\StorageUtils\Saver\SaverInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ObjectRepository;

final class InMemoryCategoryRepository implements
    IdentifiableObjectRepositoryInterface,
    SaverInterface,
    ObjectRepository,
    CategoryRepositoryInterface
{
    /** @var Collection */
    private $categories;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
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
        return $this->categories->get($code);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function save($category, array $options = [])
    {
        $this->categories->set($category->getCode(), $category);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
    {
        $categories = [];
        foreach ($this->categories as $category) {
            $keepThisCategory = true;
            foreach ($criteria as $key => $value) {
                $getter = sprintf('get%s', ucfirst($key));
                if ($category->$getter() !== $value) {
                    $keepThisCategory = false;
                }
            }

            if ($keepThisCategory) {
                $categories[] = $category;
            }
        }

        return $categories;
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
        return $this->categories->toArray();
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
    public function getCategoriesByIds(array $categoryIds = [])
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getCategoriesByCodes(array $categoryCodes = [])
    {
        $categories = [];
        foreach ($this->categories as $category) {
            if (in_array($category->getCode(), $categoryCodes)) {
                $categories[] = $category;
            }
        }

        return $categories;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getTreeFromParents(array $parentsIds)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAllChildrenIds(CategoryInterface $parent, $includeNode = false)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAllChildrenCodes(CategoryInterface $parent, $includeNode = false)
    {
        $categoryCodes = $includeNode ? [$parent->getCode()] : [];
        /** @var CategoryInterface $category */
        foreach ($this->categories as $category) {
            $tmpParent = $category->getParent();
            if (null === $tmpParent) {
                continue;
            }

            if ($parent->getCode() === $tmpParent->getCode()) {
                $categoryCodes[] = $category->getCode();
                $categoryCodes = array_merge($categoryCodes, $this->getAllChildrenCodes($category, false));
            }
        }

        return $categoryCodes;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getCategoryIdsByCodes(array $codes)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getChildrenByParentId($parentId)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getChildrenGrantedByParentId(CategoryInterface $parent, array $grantedCategoryIds = [])
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getChildrenTreeByParentId($parentId, $selectNodeId = false, array $grantedCategoryIds = [])
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function buildTreeNode(array $nodes)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPath($node)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getTrees()
    {
        $trees = [];
        foreach ($this->categories as $category) {
            if (null === $category->getParent()) {
                $trees[] = $category;
            }
        }

        return $trees;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getGrantedTrees(array $grantedCategoryIds = [])
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isAncestor(CategoryInterface $parentNode, CategoryInterface $childNode)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getOrderedAndSortedByTreeCategories()
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFilledTree(CategoryInterface $root, Collection $categories)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getRootNodes($sortByField = null, $direction = 'asc')
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getNodesHierarchy($node = null, $direct = false, array $options = [], $includeNode = false)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getChildren($node = null, $direct = false, $sortByField = null, $direction = 'ASC', $includeNode = false)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function childCount($node = null, $direct = false)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function childrenHierarchy($node = null, $direct = false, array $options = [], $includeNode = false)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function buildTree(array $nodes, array $options = [])
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function buildTreeArray(array $nodes)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setChildrenIndex($childrenIndex)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getChildrenIndex()
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function persistAsNextSiblingOf(CategoryInterface $node, CategoryInterface $sibling)
    {
        throw new NotImplementedException(__METHOD__);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function persistAsFirstChildOf(CategoryInterface $node, CategoryInterface $parent)
    {
        throw new NotImplementedException(__METHOD__);
    }
}
