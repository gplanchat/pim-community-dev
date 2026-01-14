<?php

namespace Oro\Bundle\SecurityBundle\Acl\Extension;

use Oro\Bundle\SecurityBundle\Acl\Permission\MaskBuilder;

/**
 * The permission mask builder for 'Action' ACL extension
 */
final class ActionMaskBuilder extends MaskBuilder
{
    const int MASK_EXECUTE = 1;         // 1 << 0

    // Some useful groups of bitmasks
    const int GROUP_NONE = 0;
    const int GROUP_ALL = 1;         // 1 << 0

    const string CODE_EXECUTE = 'E';

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }
}
