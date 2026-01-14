<?php

namespace Akeneo\Pim\Automation\DataQualityInsights\Application;

use Akeneo\Pim\Automation\DataQualityInsights\Domain\ValueObject\ProductUuid;
use Akeneo\Pim\Automation\DataQualityInsights\Domain\ValueObject\ProductUuidCollection;

class ProductUuidFactory implements ProductEntityIdFactoryInterface
{
    #[\Override]
    public function create(string $uuid): ProductUuid
    {
        return ProductUuid::fromString($uuid);
    }

    #[\Override]
    public function createCollection(array $uuids): ProductUuidCollection
    {
        return ProductUuidCollection::fromStrings($uuids);
    }
}
