<?php

namespace Oro\Bundle\DataGridBundle\Datagrid;

use Oro\Bundle\DataGridBundle\Datagrid\Common\MetadataIterableObject;
use Oro\Bundle\DataGridBundle\Datagrid\Common\ResultsIterableObject;
use Oro\Bundle\DataGridBundle\Datasource\DatasourceInterface;
use Oro\Bundle\DataGridBundle\Extension\Acceptor;

class Datagrid implements DatagridInterface
{
    /** @var DatasourceInterface */
    protected $datasource;

    /** @var string */
    protected $name;

    /** @var Acceptor */
    protected $acceptor;

    public function __construct($name, Acceptor $acceptor)
    {
        $this->name = $name;
        $this->setAcceptor($acceptor);
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getData()
    {
        /** @var array $rows */
        $rows = $this->getAcceptedDatasource()->getResults();

        $result = ResultsIterableObject::create(['data' => $rows]);
        $this->acceptor->acceptResult($result);

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getMetadata()
    {
        $data = MetadataIterableObject::createNamed($this->getName(), []);
        $this->acceptor->acceptMetadata($data);

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function setDatasource(DatasourceInterface $source)
    {
        $this->datasource = $source;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getDatasource()
    {
        return $this->datasource;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getAcceptedDatasource()
    {
        $this->acceptor->acceptDatasource($this->getDatasource());

        return $this->getDatasource();
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getAcceptor()
    {
        return $this->acceptor;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function setAcceptor(Acceptor $acceptor)
    {
        $this->acceptor = $acceptor;

        return $this;
    }
}
