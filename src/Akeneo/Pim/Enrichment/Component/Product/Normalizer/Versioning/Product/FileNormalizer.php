<?php

namespace Akeneo\Pim\Enrichment\Component\Product\Normalizer\Versioning\Product;

use Akeneo\Tool\Component\FileStorage\Model\FileInfoInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;

/**
 * @author    Julien Janvier <janvier@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class FileNormalizer extends AbstractValueDataNormalizer implements CacheableSupportsMethodInterface
{
    /** @var string[] */
    protected $supportedFormats = ['flat'];

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function normalize($file, $format = null, array $context = [])
    {
        return $this->doNormalize($file, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function doNormalize($file, $format = null, array $context = [])
    {
        return [
            $this->getFieldName($file, $context) => $file->getKey(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof FileInfoInterface && in_array($format, $this->supportedFormats);
    }

    #[\Override]
    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
