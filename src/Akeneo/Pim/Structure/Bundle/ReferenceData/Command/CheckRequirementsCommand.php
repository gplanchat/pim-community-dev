<?php

namespace Akeneo\Pim\Structure\Bundle\ReferenceData\Command;

use Akeneo\Pim\Structure\Component\Model\ReferenceDataConfigurationInterface;
use Akeneo\Pim\Structure\Component\ReferenceData\ConfigurationRegistryInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Akeneo\Pim\Structure\Bundle\Doctrine\ORM\ReferenceDataUniqueCodeChecker;
use Akeneo\Pim\Structure\Bundle\ReferenceData\RequirementChecker\CheckerInterface;
use Akeneo\Pim\Structure\Bundle\ReferenceData\RequirementChecker\ReferenceDataInterfaceChecker;
use Akeneo\Pim\Structure\Bundle\ReferenceData\RequirementChecker\ReferenceDataNameChecker;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Checks if a reference data is correctly configured.
 *
 * @author    Julien Janvier <jjanvier@akeneo.com>
 * @copyright 2015 Akeneo SAS (http://www.akeneo.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class CheckRequirementsCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('pim:reference-data:check')
            ->setDescription('Check the requirements of the reference data configuration');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach ($this->getRegistry()->all() as $configuration) {
            $output->writeln('');
            $output->writeln(
                sprintf('<comment>Checking configuration of "%s"...</comment>', $configuration->getName())
            );

            foreach ($this->getCheckers() as $checker) {
                $this->checkConfiguration($checker, $configuration, $output);
            }
        }
    }

    /**
     * @return CheckerInterface[]
     */
    protected function getCheckers()
    {
        $checkers = [];
        $checkers[] = new ReferenceDataNameChecker();
        $checkers[] = new ReferenceDataInterfaceChecker($this->getReferenceDataInterface());
        $checkers[] = new ReferenceDataUniqueCodeChecker($this->getDoctrineEntityManager());

        return $checkers;
    }

    /**
     * @param CheckerInterface                    $checker
     * @param ReferenceDataConfigurationInterface $configuration
     * @param OutputInterface                     $output
     */
    protected function checkConfiguration(
        CheckerInterface $checker,
        ReferenceDataConfigurationInterface $configuration,
        OutputInterface $output
    ) {
        if ($checker->check($configuration)) {
            $output->write('<info>[OK]</info>    ');
            $output->writeln($checker->getDescription());
        } else {
            $output->write('<error>[KO]</error>    ');
            $output->writeln($checker->getDescription());
            $output->writeln(sprintf('<error>%s</error>', $checker->getFailure()));

            if ($checker->isBlockingOnFailure()) {
                exit(-1);
            }
        }
    }

    /**
     * @return ConfigurationRegistryInterface
     */
    protected function getRegistry()
    {
        return $this->getContainer()->get('pim_reference_data.registry');
    }

    /**
     * @return ObjectManager
     */
    protected function getDoctrineProductManager()
    {
        return $this->getContainer()->get('pim_catalog.object_manager.product');
    }

    /**
     * @return string
     */
    protected function getReferenceDataInterface()
    {
        return $this->getContainer()->getParameter('pim_reference_data.model.reference_data.interface');
    }

    /**
     * @return EntityManagerInterface
     */
    protected function getDoctrineEntityManager()
    {
        return $this->getContainer()->get('doctrine.orm.default_entity_manager');
    }
}
