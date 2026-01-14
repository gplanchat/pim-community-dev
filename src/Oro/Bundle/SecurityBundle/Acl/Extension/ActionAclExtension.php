<?php

namespace Oro\Bundle\SecurityBundle\Acl\Extension;

use Oro\Bundle\SecurityBundle\Acl\AccessLevel;
use Oro\Bundle\SecurityBundle\Acl\Domain\ObjectIdentityFactory;
use Oro\Bundle\SecurityBundle\Annotation\Acl as AclAnnotation;
use Oro\Bundle\SecurityBundle\Metadata\ActionMetadataProvider;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;

class ActionAclExtension extends AbstractAclExtension
{
    /**
     * @var ActionMetadataProvider
     */
    protected $actionMetadataProvider;

    /**
     * Constructor
     */
    public function __construct(ActionMetadataProvider $actionMetadataProvider)
    {
        $this->actionMetadataProvider = $actionMetadataProvider;

        $this->map = [
            'EXECUTE' => [
                ActionMaskBuilder::MASK_EXECUTE,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function supports($type, $id)
    {
        if ($type === ObjectIdentityFactory::ROOT_IDENTITY_TYPE && $id === $this->getExtensionKey()) {
            return true;
        }

        return $id === $this->getExtensionKey()
            && $this->actionMetadataProvider->isKnownAction($type);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getExtensionKey()
    {
        return 'action';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function validateMask($mask, $object, $permission = null)
    {
        if ($mask === 0) {
            return;
        }
        if ($mask === ActionMaskBuilder::MASK_EXECUTE) {
            return;
        }

        throw $this->createInvalidAclMaskException($mask, $object);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getObjectIdentity($val)
    {
        $type = $id = null;
        if (is_string($val)) {
            $this->parseDescriptor($val, $type, $id);
        } elseif ($val instanceof AclAnnotation) {
            $type = $val->getId();
            $id = $val->getType();
        }

        return new ObjectIdentity($id, $type);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMaskBuilder($permission)
    {
        return new ActionMaskBuilder();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAllMaskBuilders()
    {
        return [new ActionMaskBuilder()];
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMaskPattern($mask)
    {
        return ActionMaskBuilder::getPatternFor($mask);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAccessLevel($mask, $permission = null)
    {
        return $mask === 0
            ? AccessLevel::NONE_LEVEL
            : AccessLevel::SYSTEM_LEVEL;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPermissions($mask = null, $setOnly = false)
    {
        $result = [];
        if ($mask === null || $setOnly || $mask !== 0) {
            $result[] = 'EXECUTE';
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAllowedPermissions(ObjectIdentity $oid)
    {
        return ['EXECUTE'];
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getDefaultPermission()
    {
        return 'EXECUTE';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getClasses()
    {
        return $this->actionMetadataProvider->getActions();
    }
}
