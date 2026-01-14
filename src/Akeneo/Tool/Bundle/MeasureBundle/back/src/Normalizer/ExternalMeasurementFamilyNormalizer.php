<?php

namespace Akeneo\Tool\Bundle\MeasureBundle\Normalizer;

use Akeneo\Tool\Bundle\MeasureBundle\Model\MeasurementFamily;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @copyright 2022 Akeneo SAS (https://www.akeneo.com)
 */
final class ExternalMeasurementFamilyNormalizer implements NormalizerInterface
{
    #[\Override]
    public function normalize($object, ?string $format = null, array $context = [])
    {
        /** @var MeasurementFamily $object */
        $normalizedMeasurementFamily = $object->normalizeWithIndexedUnits();

        if ([] === $normalizedMeasurementFamily['labels']) {
            $normalizedMeasurementFamily['labels'] = new \ArrayObject();
        }

        $normalizedMeasurementFamily['units'] = array_map(function (array $normalizedUnit) {
            if ([] === $normalizedUnit['labels']) {
                $normalizedUnit['labels'] = new \ArrayObject();
            }

            return $normalizedUnit;
        }, $normalizedMeasurementFamily['units']);

        return $normalizedMeasurementFamily;
    }

    #[\Override]
    public function supportsNormalization($data, ?string $format = null): bool
    {
        return $data instanceof MeasurementFamily;
    }
}
