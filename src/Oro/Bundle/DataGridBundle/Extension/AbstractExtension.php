<?php

namespace Oro\Bundle\DataGridBundle\Extension;

use Oro\Bundle\DataGridBundle\Datagrid\Common\DatagridConfiguration;
use Oro\Bundle\DataGridBundle\Datagrid\Common\MetadataIterableObject;
use Oro\Bundle\DataGridBundle\Datagrid\Common\ResultsIterableObject;
use Oro\Bundle\DataGridBundle\Datagrid\RequestParameters;
use Oro\Bundle\DataGridBundle\Datasource\DatasourceInterface;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

abstract class AbstractExtension implements ExtensionVisitorInterface
{
    /** @var RequestParameters */
    protected $requestParams;

    public function __construct(?RequestParameters $requestParams = null)
    {
        $this->requestParams = $requestParams;
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function processConfigs(DatagridConfiguration $config)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function visitDatasource(DatagridConfiguration $config, DatasourceInterface $datasource)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function visitMetadata(DatagridConfiguration $config, MetadataIterableObject $data)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function visitResult(DatagridConfiguration $config, ResultsIterableObject $result)
    {
    }

    /**
     * {@inheritDoc}
     */
    #[\Override]
    public function getPriority()
    {
        // default priority if not overridden by child
        return 0;
    }

    /**
     * Validate configuration
     *
     * @param ConfigurationInterface      $configuration
     * @param                             $config
     *
     * @return array
     */
    protected function validateConfiguration(ConfigurationInterface $configuration, $config)
    {
        $processor = new Processor();
        return $processor->processConfiguration(
            $configuration,
            $config
        );
    }

    /**
     * Getter for request parameters object
     *
     * @return RequestParameters
     */
    protected function getRequestParams()
    {
        return $this->requestParams;
    }
}
