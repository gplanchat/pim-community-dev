<?php

declare(strict_types=1);

/**
 * @copyright 2022 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Akeneo\Pim\Automation\DataQualityInsights\Domain\ValueObject;

use Webmozart\Assert\Assert;

final class ProductModelIdCollection implements ProductEntityIdCollection
{
    /**
     * @var array<ProductModelId>
     */
    private array $productModelIds;

    private function __construct(array $productModelIds)
    {
        $this->productModelIds = array_values(array_unique($productModelIds));
    }

    #[\Override]
    public static function fromStrings(array $productEntityIds): self
    {
        return new self(array_map(fn ($productModelId) => ProductModelId::fromString((string) $productModelId), $productEntityIds));
    }

    /**
     * @param ProductModelId[] $productModelIds
     */
    public static function fromProductModelIds(array $productModelIds): self
    {
        Assert::allIsInstanceOf($productModelIds, ProductModelId::class);

        return new self($productModelIds);
    }

    /**
     * @return array<ProductEntityIdInterface>
     */
    #[\Override]
    public function toArray(): array
    {
        return $this->productModelIds;
    }

    #[\Override]
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->productModelIds);
    }

    #[\Override]
    public function count(): int
    {
        return count($this->productModelIds);
    }

    #[\Override]
    public function isEmpty(): bool
    {
        return empty($this->productModelIds);
    }

    #[\Override]
    public function toArrayString(): array
    {
        return array_map(fn (ProductModelId $productModelId) => (string)$productModelId, $this->productModelIds);
    }
}
