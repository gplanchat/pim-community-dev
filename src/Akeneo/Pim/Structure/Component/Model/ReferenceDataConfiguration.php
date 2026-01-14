<?php

namespace Akeneo\Pim\Structure\Component\Model;

/**
 * Reference data configuration
 *
 * @author    Julien Janvier <jjanvier@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class ReferenceDataConfiguration implements ReferenceDataConfigurationInterface
{
    /** @var string */
    protected $name;

    /** @var string */
    protected $class;

    /** @var string */
    protected $type;

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getClass()
    {
        return $this->class;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setType($type)
    {
        $this->type = $type;
    }
}
