<?php

declare(strict_types=1);

namespace Akeneo\Pim\Enrichment\Component\Product\Value;

use Akeneo\Pim\Enrichment\Component\Product\Model\ValueInterface;
use Webmozart\Assert\Assert;

/**
 * @copyright 2023 Akeneo SAS (https://www.akeneo.com)
 * @license   https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
final readonly class IdentifierValue implements IdentifierValueInterface
{
    private function __construct(
        private string $attributeCode,
        private bool $isMainIdentifier,
        private ?string $data
    ) {
    }

    public static function value(string $attributeCode, bool $isMainIdentifier, $data): self
    {
        Assert::nullOrStringNotEmpty($data);
        return new self($attributeCode, $isMainIdentifier, $data);
    }

    #[\Override]
    public function getData(): ?string
    {
        return $this->data;
    }

    #[\Override]
    public function getAttributeCode(): string
    {
        return $this->attributeCode;
    }

    #[\Override]
    public function isMainIdentifier(): bool
    {
        return $this->isMainIdentifier;
    }

    #[\Override]
    public function getLocaleCode(): ?string
    {
        return null;
    }

    #[\Override]
    public function isLocalizable(): bool
    {
        return false;
    }

    #[\Override]
    public function hasData(): bool
    {
        return null !== $this->data;
    }

    #[\Override]
    public function getScopeCode(): ?string
    {
        return null;
    }

    #[\Override]
    public function isScopable(): bool
    {
        return false;
    }

    #[\Override]
    public function isEqual(ValueInterface $value): bool
    {
        return $value instanceof IdentifierValue && $this->data === $value->getData();
    }

    #[\Override]
    public function __toString(): string
    {
        return $this->data;
    }
}
