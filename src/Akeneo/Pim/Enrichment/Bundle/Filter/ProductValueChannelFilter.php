<?php

namespace Akeneo\Pim\Enrichment\Bundle\Filter;

use Akeneo\Pim\Enrichment\Component\Product\Model\ValueInterface;
use Akeneo\Pim\Enrichment\Component\Product\Model\WriteValueCollection;

/**
 * Filter the values according to channel codes provided in options.
 *
 * @author    Julien Sanchez <julien@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ProductValueChannelFilter implements CollectionFilterInterface, ObjectFilterInterface
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function filterObject($value, $type, array $options = [])
    {
        if (!$value instanceof ValueInterface) {
            throw new \LogicException('This filter only handles objects of type "ValueInterface"');
        }

        $channelCodes = isset($options['channels']) ? $options['channels'] : [];

        return !empty($channelCodes) &&
            $value->isScopable() &&
            !in_array($value->getScopeCode(), $channelCodes);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function filterCollection($objects, $type, array $options = [])
    {
        foreach ($objects as $key => $object) {
            if ($this->filterObject($object, $type, $options)) {
                $objects->removeKey($key);
            }
        }

        return $objects;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function supportsObject($object, $type, array $options = [])
    {
        return $object instanceof ValueInterface;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function supportsCollection($collection, $type, array $options = [])
    {
        return $collection instanceof WriteValueCollection;
    }
}
