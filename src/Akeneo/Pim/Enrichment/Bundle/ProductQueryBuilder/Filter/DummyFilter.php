<?php

namespace Akeneo\Pim\Enrichment\Bundle\ProductQueryBuilder\Filter;

use Akeneo\Pim\Enrichment\Component\Product\Query\Filter\AttributeFilterInterface;
use Akeneo\Pim\Enrichment\Component\Product\Query\Filter\FieldFilterInterface;
use Akeneo\Pim\Structure\Component\Model\AttributeInterface;

/**
 * Dummy filter for PQB.
 *
 * This filter "bypasses" filters for supported product attributes/fields.
 * Originally created for the operator "ALL".
 *
 * For example the filter completeness with operator "<=" and value "100" doesn't cover
 * case where a product has no family.
 * The operator "ALL" covers those products.
 *
 * @author    Adrien Pétremann <adrien.petremann@akeneo.com>
 * @copyright 2016 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class DummyFilter implements AttributeFilterInterface, FieldFilterInterface
{
    /** @var array */
    protected $supportedAttributeTypes;

    /** @var array */
    protected $supportedFields;

    /** @var array */
    protected $supportedOperators;

    /** @var mixed */
    protected $queryBuilder;

    /**
     * @param array $supportedAttributeTypes
     * @param array $supportedFields
     * @param array $supportedOperators
     */
    public function __construct(array $supportedAttributeTypes, array $supportedFields, array $supportedOperators)
    {
        $this->supportedAttributeTypes = $supportedAttributeTypes;
        $this->supportedFields = $supportedFields;
        $this->supportedOperators = $supportedOperators;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function supportsAttribute(AttributeInterface $attribute)
    {
        return in_array($attribute->getType(), $this->supportedAttributeTypes);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function supportsField($field)
    {
        return in_array($field, $this->supportedFields);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function supportsOperator($operator)
    {
        return in_array($operator, $this->supportedOperators);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addAttributeFilter(
        AttributeInterface $attribute,
        $operator,
        $value,
        $locale = null,
        $scope = null,
        $options = []
    ) {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addFieldFilter($field, $operator, $value, $locale = null, $scope = null, $options = [])
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAttributeTypes()
    {
        return $this->supportedAttributeTypes;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFields()
    {
        return $this->supportedFields;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getOperators()
    {
        return $this->supportedOperators;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setQueryBuilder($queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }
}
