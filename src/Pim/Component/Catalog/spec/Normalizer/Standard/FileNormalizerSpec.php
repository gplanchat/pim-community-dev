<?php

namespace spec\Pim\Component\Catalog\Normalizer\Standard;

use Akeneo\Component\FileStorage\Model\FileInfoInterface;
use Pim\Component\Catalog\Model\AttributeOptionInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FileNormalizerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Pim\Component\Catalog\Normalizer\Standard\FileNormalizer');
    }

    function it_is_a_normalizer()
    {
        $this->shouldImplement('Symfony\Component\Serializer\Normalizer\NormalizerInterface');
    }

    function it_supports_standard_format_and_file_info_objects(FileInfoInterface $fileInfo)
    {
        $this->supportsNormalization($fileInfo, 'standard')->shouldReturn(true);
    }

    function it_does_not_supports_other_formats_or_objects(
        FileInfoInterface $fileInfo
    ) {
        $this->supportsNormalization($fileInfo, 'other_format')->shouldReturn(false);
        $this->supportsNormalization(new \stdClass(), 'standard')->shouldReturn(false);
        $this->supportsNormalization(new \stdClass(), 'other_format')->shouldReturn(false);
    }

    function it_normalizes_file_info(FileInfoInterface $fileInfo)
    {
        $fileInfo->getKey()->willReturn('f/2/e/6/f2e6674e076ad6fafa12012e8fd026acdc70f814_fileA.txt');
        $this->normalize($fileInfo, 'standard')
            ->shouldReturn('f/2/e/6/f2e6674e076ad6fafa12012e8fd026acdc70f814_fileA.txt');
    }
}
