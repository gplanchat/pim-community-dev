<?php

namespace Oro\Bundle\SecurityBundle\Cache;

use Oro\Bundle\SecurityBundle\Metadata\ActionMetadataProvider;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmerInterface;

class ActionMetadataCacheWarmer implements CacheWarmerInterface
{
    /**
     * @var ActionMetadataProvider
     */
    private $provider;

    /**
     * Constructor
     *
     * @param ActionMetadataProvider $provider
     */
    public function __construct(ActionMetadataProvider $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {inheritdoc}
     */
    #[\Override]
    public function warmUp($cacheDir): array
    {
        $this->provider->warmUpCache();

        return [];
    }

    /**
     * {inheritdoc}
     */
    #[\Override]
    public function isOptional(): bool
    {
        return true;
    }
}
