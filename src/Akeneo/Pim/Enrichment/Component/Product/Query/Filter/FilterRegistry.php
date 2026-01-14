<?php

namespace Akeneo\Pim\Enrichment\Component\Product\Query\Filter;

use Akeneo\Pim\Structure\Component\Model\AttributeInterface;
use Akeneo\Pim\Structure\Component\Repository\AttributeRepositoryInterface;

/**
 * Aims to register and retrieve filters usable on product query builder
 *
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2014 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class FilterRegistry implements FilterRegistryInterface
{
    /** @var AttributeFilterInterface[] prioritized attribute filters */
    protected $attributeFilters = [];

    /** @var FieldFilterInterface[] prioritized field filters */
    protected $fieldFilters = [];

    /** @var AttributeRepositoryInterface */
    protected $attributeRepository;

    /**
     * @param AttributeRepositoryInterface $attributeRepository
     */
    public function __construct(AttributeRepositoryInterface $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function register(FilterInterface $filter)
    {
        if ($filter instanceof FieldFilterInterface) {
            $this->fieldFilters[] = $filter;
        }
        if ($filter instanceof AttributeFilterInterface) {
            $this->attributeFilters[] = $filter;
        }
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFilter($code, $operator)
    {
        $attribute = $this->attributeRepository->findOneBy(['code' => FieldFilterHelper::getCode($code)]);

        if (null !== $attribute) {
            return $this->getAttributeFilter($attribute, $operator);
        }

        return $this->getFieldFilter($code, $operator);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFieldFilter($field, $operator)
    {
        foreach ($this->fieldFilters as $filter) {
            if ($filter->supportsField($field) && $filter->supportsOperator($operator)) {
                return $filter;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttributeFilter(AttributeInterface $attribute, $operator)
    {
        foreach ($this->attributeFilters as $filter) {
            if ($filter->supportsAttribute($attribute) && $filter->supportsOperator($operator)) {
                return $filter;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFieldFilters()
    {
        return $this->fieldFilters;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttributeFilters()
    {
        return $this->attributeFilters;
    }
}
