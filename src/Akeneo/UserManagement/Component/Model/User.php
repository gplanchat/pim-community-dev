<?php

namespace Akeneo\UserManagement\Component\Model;

use Akeneo\Category\Infrastructure\Component\Classification\Model\CategoryInterface;
use Akeneo\Channel\Infrastructure\Component\Model\ChannelInterface;
use Akeneo\Channel\Infrastructure\Component\Model\LocaleInterface;
use Akeneo\Tool\Component\FileStorage\Model\FileInfoInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface as SymfonyUserInterface;

/**
 * @author    Nicolas Dupont <nicalas@akeneo.com>
 * @copyright 2012 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class User implements UserInterface, EquatableInterface
{
    const ROLE_DEFAULT = 'ROLE_USER';
    const GROUP_DEFAULT = 'All';
    const ROLE_ANONYMOUS = 'IS_AUTHENTICATED_ANONYMOUSLY';
    const DEFAULT_TIMEZONE = 'UTC';
    const TYPE_USER = 'user';
    const TYPE_API = 'api';
    const TYPE_JOB = 'job';

    /** @var int|string */
    protected $id;

    /** @var string */
    protected $username;

    /** @var string */
    protected $email;

    /** @var string */
    protected $namePrefix;

    /** @var string */
    protected $firstName;

    /** @var string */
    protected $middleName;

    /** @var string */
    protected $lastName;

    /** @var string */
    protected $nameSuffix;

    /**
     * Image filename
     *
     * @var string
     */
    protected $image;

    /** @var FileInfoInterface */
    protected $avatar;

    /**
     * Image filename
     *
     * @var UploadedFile
     */
    protected $imageFile;

    /** @var boolean */
    protected $enabled = true;

    /**
     * The salt to use for hashing
     *
     * @var string
     */
    protected $salt;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     */
    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * Random string sent to the user email address in order to verify it
     *
     * @var string
     */
    protected $confirmationToken;

    /** @var \DateTime */
    protected $passwordRequestedAt;

    /** @var \DateTime */
    protected $lastLogin;

    /** @var int */
    protected $loginCount = 0;

    /** @var Role[] */
    protected $roles;

    /** @var GroupInterface[] */
    protected $groups;

    /** @var string */
    protected $api;

    /** @var \DateTime $createdAt */
    protected $createdAt;

    /** @var \DateTime $updatedAt */
    protected $updatedAt;

    /** @var LocaleInterface */
    protected $catalogLocale;

    /** @var LocaleInterface */
    protected $uiLocale;

    /** @var ChannelInterface */
    protected $catalogScope;

    /** @var CategoryInterface */
    protected $defaultTree;

    /** @var ArrayCollection */
    protected $defaultGridViews;

    /** @var bool */
    protected $emailNotifications = false;

    /** @var array */
    protected $productGridFilters = [];

    /** @var string */
    protected $phone;

    /** @var string */
    protected $timezone;

    /** @var array $property bag for properties extension */
    private $properties = [];

    private int $consecutiveAuthenticationFailureCounter = 0;

    private ?\DateTime $authenticationFailureResetDate = null;

    protected $type = self::TYPE_USER;

    private ?string $profile = null;

    public function __construct()
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->roles = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->defaultGridViews = new ArrayCollection();
        $this->timezone = self::DEFAULT_TIMEZONE;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function serialize()
    {
        return serialize(
            [
                $this->password,
                $this->salt,
                $this->username,
                $this->enabled,
                $this->confirmationToken,
                $this->id,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function unserialize($serialized)
    {
        list(
            $this->password,
            $this->salt,
            $this->username,
            $this->enabled,
            $this->confirmationToken,
            $this->id
        ) = unserialize($serialized);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getClass()
    {
        return UserInterface::class;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUsername()
    {
        return $this->getUserIdentifier();
    }

    /**
     * {@inheritDoc}
     */
    public function getUserIdentifier()
    {
        return $this->username;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getNamePrefix()
    {
        return $this->namePrefix;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getNameSuffix()
    {
        return $this->nameSuffix;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getFullName()
    {
        return \trim(\implode(' ', array_filter([
            $this->namePrefix,
            $this->firstName,
            $this->middleName,
            $this->lastName,
            $this->nameSuffix
        ])));
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getImage()
    {
        return $this->image;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAvatar(): ?FileInfoInterface
    {
        return $this->avatar;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setAvatar(?FileInfoInterface $avatar = null): void
    {
        $this->avatar = $avatar;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPasswordRequestedAt()
    {
        return $this->passwordRequestedAt;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLastLogin()
    {
        return $this->lastLogin;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getLoginCount()
    {
        return $this->loginCount;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isAccountNonExpired(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isAccountNonLocked(): bool
    {
        return $this->isEnabled();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isPasswordRequestNonExpired($ttl)
    {
        return $this->getPasswordRequestedAt() instanceof \DateTime &&
            $this->getPasswordRequestedAt()->getTimestamp() + $ttl > time();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setFirstName($firstName = null)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setLastName($lastName = null)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setNamePrefix($namePrefix)
    {
        $this->namePrefix = $namePrefix;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setNameSuffix($nameSuffix)
    {
        $this->nameSuffix = $nameSuffix;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setImage($image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setImageFile(UploadedFile $imageFile)
    {
        $this->imageFile = $imageFile;
        // this will trienvogger PreUpdate callback even if only image has been changed
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function unsetImageFile()
    {
        $this->imageFile = null;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setEnabled($enabled)
    {
        $this->enabled = (bool) $enabled;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setConfirmationToken($token)
    {
        $this->confirmationToken = $token;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setPasswordRequestedAt(?\DateTime $time = null)
    {
        $this->passwordRequestedAt = $time;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setLastLogin(\DateTime $time)
    {
        $this->lastLogin = $time;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setLoginCount($count)
    {
        $this->loginCount = $count;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getRoles(): array
    {
        return $this->roles->map(fn (RoleInterface $role): string => $role->getRole())->getValues();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getRolesCollection()
    {
        return $this->roles;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getRole($roleName)
    {
        /** @var Role $item */
        foreach ($this->roles as $item) {
            if ($roleName == $item->getRole()) {
                return $item;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function hasRole($role)
    {
        if ($role instanceof Role) {
            $roleName = $role->getRole();
        } elseif (is_string($role)) {
            $roleName = $role;
        } else {
            throw new \InvalidArgumentException(
                sprintf('$role must be an instance of %s or a string', Role::class)
            );
        }

        return (bool) $this->getRole($roleName);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addRole(Role $role)
    {
        if (!$this->hasRole($role)) {
            $this->roles->add($role);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function removeRole($role)
    {
        if ($role instanceof Role) {
            $roleObject = $role;
        } elseif (is_string($role)) {
            $roleObject = $this->getRole($role);
        } else {
            throw new \InvalidArgumentException(
                sprintf('$role must be an instance of %s or a string', Role::class)
            );
        }
        if ($roleObject) {
            $this->roles->removeElement($roleObject);
        }
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setRoles($roles)
    {
        if (!$roles instanceof Collection && !is_array($roles)) {
            throw new \InvalidArgumentException(
                '$roles must be an instance of Doctrine\Common\Collections\Collection or an array'
            );
        }

        $this->roles->clear();

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setRolesCollection(Collection $collection)
    {
        if (!$collection instanceof Collection) {
            throw new \InvalidArgumentException(
                '$collection must be an instance of Doctrine\Common\Collections\Collection'
            );
        }
        $this->roles = $collection;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getGroupNames()
    {
        $names = [];

        /** @var Group $group */
        foreach ($this->getGroups() as $group) {
            $names[] = $group->getName();
        }

        return $names;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function hasGroup($name)
    {
        return in_array($name, $this->getGroupNames());
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addGroup(GroupInterface $group)
    {
        if (!$this->getGroups()->contains($group)) {
            $this->getGroups()->add($group);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function removeGroup(GroupInterface $group)
    {
        if ($this->getGroups()->contains($group)) {
            $this->getGroups()->removeElement($group);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setGroups(array $groups)
    {
        $this->groups->clear();

        foreach ($groups as $group) {
            $this->addGroup($group);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getGroupsIds()
    {
        $ids = [];
        foreach ($this->groups as $group) {
            $ids[] = $group->getId();
        }

        return $ids;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getImagePath()
    {
        if ($this->image) {
            return $this->getUploadDir(true) . '/' . $this->image;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function generateToken()
    {
        return base_convert(bin2hex(hash('sha256', uniqid(mt_rand(), true), true)), 16, 36);
    }

    /**
     * @return string
     */
    #[\Override]
    public function __toString()
    {
        return (string) $this->getUserIdentifier();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function beforeSave()
    {
        $this->createdAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
        $this->loginCount = 0;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('UTC'));
    }

    /**
     * {@inheritdoc}
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUploadDir($forWeb = false)
    {
        $ds = DIRECTORY_SEPARATOR;

        if ($forWeb) {
            $ds = '/';
        }

        $suffix = $this->getCreatedAt() ? $this->getCreatedAt()->format('Y-m') : date('Y-m');

        return ($forWeb ? $ds : '').'uploads'.$ds.'users'.$ds.$suffix;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getCatalogLocale()
    {
        return $this->catalogLocale;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setCatalogLocale(LocaleInterface $catalogLocale)
    {
        $this->catalogLocale = $catalogLocale;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getUiLocale()
    {
        return $this->uiLocale;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setUiLocale(LocaleInterface $uiLocale)
    {
        $this->uiLocale = $uiLocale;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getCatalogScope()
    {
        return $this->catalogScope;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setCatalogScope(ChannelInterface $catalogScope)
    {
        $this->catalogScope = $catalogScope;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getDefaultTree()
    {
        return $this->defaultTree;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setDefaultTree(CategoryInterface $defaultTree)
    {
        $this->defaultTree = $defaultTree;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function isEmailNotifications()
    {
        return $this->emailNotifications;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setEmailNotifications($emailNotifications)
    {
        $this->emailNotifications = $emailNotifications;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getProductGridFilters()
    {
        return $this->productGridFilters;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setProductGridFilters(array $productGridFilters = [])
    {
        $this->productGridFilters = $productGridFilters;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getDefaultGridView($alias)
    {
        foreach ($this->defaultGridViews as $datagridView) {
            if ($datagridView->getDatagridAlias() === $alias) {
                return $datagridView;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getDefaultGridViews()
    {
        $views = [];
        foreach ($this->defaultGridViews as $datagridView) {
            $views[$datagridView->getDatagridAlias()] = $datagridView;
        }

        return $views;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setDefaultGridView($alias, $defaultGridView)
    {
        if (null !== $gridView = $this->getDefaultGridView($alias)) {
            $this->defaultGridViews->removeElement($gridView);
        }

        if (null !== $defaultGridView) {
            $this->defaultGridViews->set($alias, $defaultGridView);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setPhone(?string $phone): UserInterface
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getTimezone(): string
    {
        return $this->timezone;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setTimezone(string $timezone): UserInterface
    {
        $this->timezone = $timezone;

        return $this;
    }

    #[\Override]
    public function isUiUser(): bool
    {
        return self::TYPE_USER === $this->type;
    }

    #[\Override]
    public function defineAsUiUser(): void
    {
        $this->type = self::TYPE_USER;
    }

    #[\Override]
    public function isApiUser(): bool
    {
        return self::TYPE_API === $this->type;
    }

    #[\Override]
    public function defineAsApiUser(): void
    {
        $this->type = self::TYPE_API;
    }

    #[\Override]
    public function isJobUser(): bool
    {
        return self::TYPE_JOB === $this->type;
    }

    #[\Override]
    public function defineAsJobUser(): void
    {
        $this->type = self::TYPE_JOB;
    }

    #[\Override]
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function addProperty(string $propertyName, $propertyValue): void
    {
        $propertyName = $this->getInflector()->tableize($propertyName);

        $this->properties[$propertyName] = $propertyValue;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getProperty(string $propertyName)
    {
        $propertyName = $this->getInflector()->tableize($propertyName);

        return $this->properties[$propertyName] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getConsecutiveAuthenticationFailureCounter(): int
    {
        return $this->consecutiveAuthenticationFailureCounter;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setConsecutiveAuthenticationFailureCounter(int $consecutiveAuthenticationFailureCounter): void
    {
        $this->consecutiveAuthenticationFailureCounter = $consecutiveAuthenticationFailureCounter;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getAuthenticationFailureResetDate(): ?\DateTime
    {
        return $this->authenticationFailureResetDate;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setAuthenticationFailureResetDate(?\DateTime $authenticationFailureResetDate): void
    {
        $this->authenticationFailureResetDate = $authenticationFailureResetDate;
    }

    #[\Override]
    public function duplicate(): UserInterface
    {
        $duplicated = new static();
        $duplicated->setEnabled($this->enabled ?? false);
        if ($this->timezone) {
            $duplicated->setTimezone($this->timezone);
        }

        $duplicated->setPhone($this->phone);
        $duplicated->setEmailNotifications($this->emailNotifications);
        if ($this->uiLocale) {
            $duplicated->setUiLocale($this->uiLocale);
        }

        if ($this->catalogLocale) {
            $duplicated->setCatalogLocale($this->catalogLocale);
        }

        if ($this->catalogScope) {
            $duplicated->setCatalogScope($this->catalogScope);
        }

        if ($this->defaultTree) {
            $duplicated->setDefaultTree($this->defaultTree);
        }

        $duplicated->setRoles($this->roles);
        $duplicated->setGroups($this->groups->toArray());
        $duplicated->setProductGridFilters($this->productGridFilters);
        foreach ($this->defaultGridViews as $datagridView) {
            if ($datagridView->isPublic()) {
                $duplicated->setDefaultGridView($datagridView->getDatagridAlias(), $datagridView);
            }
        }

        if ($this->isApiUser()) {
            $duplicated->defineAsApiUser();
        }

        foreach ($this->properties as $key => $value) {
            $duplicated->addProperty($key, $value);
        }

        return $duplicated;
    }

    private function getInflector(): Inflector
    {
        return new Inflector(new NoopWordInflector(), new NoopWordInflector());
    }

    #[\Override]
    public function getProfile(): ?string
    {
        return $this->profile;
    }

    #[\Override]
    public function setProfile(?string $profile): void
    {
        $this->profile = '' === $profile ? null : $profile;
    }

    /**
     * Please note this function is inspired by User::isEqualTo
     * But using Akeneo custom implementations roles are into token not User, and there are a few structural/implementation differences between Akeneo and Symfonu User ...
     * isAccountNonExpired isAccountNotLocked
     * @see \Symfony\Component\Security\Core\User\User::isEqualTo()
     * {@inheritdoc}
     */
    #[\Override]
    public function isEqualTo(SymfonyUserInterface $user): bool
    {
        if (!$user instanceof self) {
            return false;
        }

        if ($this->getPassword() !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->getUserIdentifier() !== $user->getUserIdentifier()) {
            return false;
        }

        if (self::class === static::class) {
            if ($this->isAccountNonLocked() !== $user->isAccountNonLocked()) {
                return false;
            }
        }

        if ($this->isEnabled() !== $user->isEnabled()) {
            return false;
        }

        return true;
    }
}
