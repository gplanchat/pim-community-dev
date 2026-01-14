<?php

namespace Akeneo\Pim\Structure\Bundle\Doctrine\ORM\Repository;

use Akeneo\Pim\Structure\Component\Model\AttributeGroupInterface;
use Akeneo\Pim\Structure\Component\Repository\AttributeGroupRepositoryInterface;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Repository
 *
 * @author    Gildas Quemener <gildas@akeneo.com>
 * @copyright 2013 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class AttributeGroupRepository extends EntityRepository implements AttributeGroupRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getIdToLabelOrderedBySortOrder()
    {
        $groups = $this->buildAllOrderedBySortOrder()->getQuery()->execute();
        $orderedGroups = [];
        foreach ($groups as $group) {
            $orderedGroups[$group->getId()] = $group->getLabel();
        }

        return $orderedGroups;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function findDefaultAttributeGroup()
    {
        return $this->findOneBy(['code' => AttributeGroupInterface::DEFAULT_CODE]);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMaxSortOrder()
    {
        return (int) $this->createQueryBuilder('ag')
            ->select('MAX(ag.sortOrder)')
            ->getQuery()
            ->execute([], AbstractQuery::HYDRATE_SINGLE_SCALAR);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function findOneByIdentifier($code)
    {
        return $this->findOneBy(['code' => $code]);
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
     * @return QueryBuilder
     */
    protected function buildAllOrderedBySortOrder()
    {
        return $this->createQueryBuilder('attribute_group')
            ->orderBy('attribute_group.sortOrder');
    }

    #[\Override]
    public function countAll(): int
    {
        return $this->count([]);
    }
}
