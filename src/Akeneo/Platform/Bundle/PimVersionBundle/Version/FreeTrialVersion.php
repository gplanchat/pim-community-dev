<?php

namespace Akeneo\Platform\Bundle\PimVersionBundle\Version;

/**
 * @copyright 2022 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
final class FreeTrialVersion implements PimVersion
{
    /** @staticvar string */
    const string VERSION_CODENAME = 'Free Trial Edition';

    /** @staticvar string */
    const string EDITION_NAME = 'Free Trial Edition';

    /** @staticvar string **/
    private const string EDITION_CODE = 'pim_trial_instance';

    #[\Override]
    public function versionCodename(): string
    {
        return self::VERSION_CODENAME;
    }

    #[\Override]
    public function editionName(): string
    {
        return self::EDITION_NAME;
    }

    #[\Override]
    public function isSaas(): bool
    {
        return false;
    }

    #[\Override]
    public function isEditionCode(string $editionCode): bool
    {
        return $editionCode === self::EDITION_CODE;
    }
}
