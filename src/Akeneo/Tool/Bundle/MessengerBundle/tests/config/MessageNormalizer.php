<?php

declare(strict_types=1);

namespace Akeneo\Tool\Bundle\MessengerBundle\tests\config;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @copyright 2023 Akeneo SAS (https://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
final class MessageNormalizer implements NormalizerInterface, DenormalizerInterface
{
    #[\Override]
    public function denormalize($data, string $type, ?string $format = null, array $context = [])
    {
        return $type === Message1::class ? Message1::denormalize($data) : Message2::denormalize($data);
    }

    #[\Override]
    public function supportsDenormalization($data, string $type, ?string $format = null)
    {
        return $type === Message1::class || $type === Message2::class;
    }

    #[\Override]
    public function normalize($object, ?string $format = null, array $context = [])
    {
        return $object->normalize();
    }

    #[\Override]
    public function supportsNormalization($data, ?string $format = null)
    {
        $messageClass = \get_class($data);

        return $messageClass === Message1::class || $messageClass === Message2::class;
    }
}
