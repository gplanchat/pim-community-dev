<?php

declare(strict_types=1);

namespace Akeneo\UserManagement\Component;

final class Profiles
{
    /** I manage product catalogs. */
    const string PRODUCT_MANAGER = 'product_manager';

    /** I enrich product data. */
    const string REDACTOR = 'redactor';

    /** I integrate Akeneo PIM into our IT ecosystem.  */
    const string PIM_INTEGRATOR = 'pim_integrator';

    /** I administrate Akeneo PIM. */
    const string PIM_ADMINISTRATOR = 'pim_administrator';

    /** I manage assets in the PIM. */
    const string ASSET_MANAGER = 'asset_manager';

    /** I translate product, asset, and/or reference entity data. */
    const string TRANSLATOR = 'translator';

    /** I develop solutions connected with Akeneo PIM. */
    const string THIRD_PARTY_DEVELOPER = 'third_party_developer';
}
