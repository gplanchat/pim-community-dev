<?php

namespace Pim\Component\Connector\Step;

use Akeneo\Component\Batch\Model\StepExecution;
use Akeneo\Component\Batch\Step\AbstractStep;
use Pim\Component\Connector\Step\TaskletInterface;
use Akeneo\Component\Batch\Job\JobRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author    Adrien Pétremann <adrien.petremann@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class TaskletStep extends AbstractStep
{
    /** @var TaskletInterface */
    protected $tasklet;

    public function __construct(
        $name,
        EventDispatcherInterface $eventDispatcher,
        JobRepositoryInterface $jobRepository,
        TaskletInterface $tasklet
    ) {
        parent::__construct($name, $eventDispatcher, $jobRepository);
        $this->tasklet = $tasklet;
    }

    /**
     * {@inheritdoc}
     */
    protected function doExecute(StepExecution $stepExecution)
    {
        $this->tasklet->setStepExecution($stepExecution);
        $this->tasklet->execute();
    }

    /**
     * @return TaskletInterface
     */
    public function getTasklet()
    {
        return $this->tasklet;
    }

    /**
     * @param TaskletInterface $tasklet
     */
    public function setTasklet($tasklet)
    {
        $this->tasklet = $tasklet;
    }
}
