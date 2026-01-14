<?php

declare(strict_types=1);

namespace Akeneo\Category\Api\Command\UserIntents;

/**
 * @copyright 2022 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 */
class SetRichText implements ValueUserIntent
{
    public function __construct(
        private readonly string $attributeUuid,
        private readonly string $attributeCode,
        private readonly ?string $channelCode,
        private readonly ?string $localeCode,
        private readonly ?string $value,
    ) {
    }

    #[\Override]
    public function attributeUuid(): string
    {
        return $this->attributeUuid;
    }

    #[\Override]
    public function attributeCode(): string
    {
        return $this->attributeCode;
    }

    #[\Override]
    public function channelCode(): ?string
    {
        return $this->channelCode;
    }

    #[\Override]
    public function localeCode(): ?string
    {
        return $this->localeCode;
    }

    #[\Override]
    public function value(): ?string
    {
        return $this->value;
    }
}
