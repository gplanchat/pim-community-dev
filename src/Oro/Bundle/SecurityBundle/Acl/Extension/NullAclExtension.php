<?php

namespace Oro\Bundle\SecurityBundle\Acl\Extension;

use Oro\Bundle\SecurityBundle\Acl\AccessLevel;
use Oro\Bundle\SecurityBundle\Acl\Exception\InvalidAclMaskException;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Exception\InvalidDomainObjectException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * This class implements "Null object" design pattern for AclExtensionInterface
 */
final class NullAclExtension implements AclExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function supports($type, $id)
    {
        throw new \LogicException('Not supported by NullAclExtension.');
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getExtensionKey()
    {
        throw new \LogicException('Not supported by NullAclExtension.');
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function validateMask($mask, $object, $permission = null)
    {
        throw new InvalidAclMaskException('Not supported by NullAclExtension.');
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getObjectIdentity($val)
    {
        throw new InvalidDomainObjectException('Not supported by NullAclExtension.');
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMaskBuilder($permission)
    {
        throw new \LogicException('Not supported by NullAclExtension.');
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAllMaskBuilders()
    {
        throw new \LogicException('Not supported by NullAclExtension.');
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMaskPattern($mask)
    {
        return 'NullAclExtension: ' . $mask;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMasks($permission)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function hasMasks($permission)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function adaptRootMask($rootMask, $object)
    {
        return $rootMask;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getServiceBits($mask)
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function removeServiceBits($mask)
    {
        return $mask;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAccessLevel($mask, $permission = null)
    {
        return AccessLevel::UNKNOWN;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPermissions($mask = null, $setOnly = false)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAllowedPermissions(ObjectIdentity $oid)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getDefaultPermission()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getClasses()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function decideIsGranting($triggeredMask, $object, TokenInterface $securityToken)
    {
        return true;
    }
}
