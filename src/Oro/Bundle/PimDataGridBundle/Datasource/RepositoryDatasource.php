<?php

namespace Oro\Bundle\PimDataGridBundle\Datasource;

use Doctrine\ORM\QueryBuilder;
use Oro\Bundle\DataGridBundle\Datagrid\DatagridInterface;
use Oro\Bundle\PimDataGridBundle\Datasource\ResultRecord\HydratorInterface;
use Oro\Bundle\PimDataGridBundle\Doctrine\ORM\Repository\DatagridRepositoryInterface;
use Oro\Bundle\PimDataGridBundle\Doctrine\ORM\Repository\MassActionRepositoryInterface;

/**
 * @author    Nicolas Dupont <nicolas@akeneo.com>
 * @copyright 2017 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class RepositoryDatasource implements DatasourceInterface, ParameterizableInterface
{
    const DEFAULT_QUERY_PARAMS_KEY = 'default_query_params';

    /** @var DatagridRepositoryInterface */
    protected $repository;

    /** @var QueryBuilder */
    protected $qb;

    /** @var HydratorInterface */
    protected $hydrator;

    /** @var array */
    protected $parameters = [];

    /**
     * @param DatagridRepositoryInterface $repository
     * @param HydratorInterface           $hydrator
     */
    public function __construct(DatagridRepositoryInterface $repository, HydratorInterface $hydrator)
    {
        $this->repository = $repository;
        $this->hydrator = $hydrator;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function process(DatagridInterface $grid, array $config)
    {
        $this->qb = $this->repository->createDatagridQueryBuilder();

        if (array_key_exists(static::DEFAULT_QUERY_PARAMS_KEY, $config)) {
            $this->parameters += $config[static::DEFAULT_QUERY_PARAMS_KEY];
        }

        $grid->setDatasource(clone $this);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setParameters($parameters)
    {
        $this->parameters += $parameters;
        if ($this->qb instanceof QueryBuilder) {
            $this->qb->setParameters($this->parameters);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getResults()
    {
        return $this->hydrator->hydrate($this->qb);
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getQueryBuilder()
    {
        return $this->qb;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function getMassActionRepository()
    {
        throw new \LogicException("No need to implement this method, design flaw in interface!");
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setMassActionRepository(MassActionRepositoryInterface $massActionRepository)
    {
        throw new \LogicException("No need to implement this method, design flaw in interface!");
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    public function setHydrator(HydratorInterface $hydrator)
    {
        throw new \LogicException("No need to implement this method, design flaw in interface!");
    }
}
