<?php

namespace Akeneo\Pim\Enrichment\Component\Product\Normalizer\InternalApi;

use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Collection normalizer
 *
 * @author    Julien Sanchez <julien@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CollectionNormalizer implements NormalizerInterface, SerializerAwareInterface, CacheableSupportsMethodInterface
{
    /** @var Serializer $serializer */
    protected $serializer;

    /** @var string[] */
    protected $supportedFormat = ['internal_api'];

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function normalize($elements, $format = null, array $context = [])
    {
        $normalizedElements = [];

        foreach ($elements as $key => $element) {
            $normalizedElements[$key] = $this->serializer->normalize($element, $format, $context);
        }

        return $normalizedElements;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function supportsNormalization($data, $format = null): bool
    {
        return ($data instanceof \Traversable || is_array($data)) && in_array($format, $this->supportedFormat);
    }

    #[\Override]
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setSerializer(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }
}
