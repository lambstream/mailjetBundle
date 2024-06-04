<?php

namespace Mailjet\MailjetBundle\Command;

use Mailjet\MailjetBundle\Manager\ContactMetadataManager;
use Mailjet\MailjetBundle\Model\ContactMetadata;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class SyncUserCommand
 * Sync users in a mailjet contact list
 *
 */
class SyncContactMetadataCommand extends Command
{
    private array $contactMetadata = [];

    private ContactMetadataManager $contactMetadataManager;

    public function __construct(
        array $contactMetadata,
        ContactMetadataManager $contactMetadataManager,
    ) {
        $this->contactMetadata = $contactMetadata;
        $this->contactMetadataManager = $contactMetadataManager;

        parent::__construct();
    }

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('mailjet:contactmetadata-sync')
            ->setDescription('Synchronize ContactMetadata in config with Mailjet');
    }

    /**
     * {@inheritDoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('<info>%s</info>', $this->getDescription()));
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // @TODO create a ContactMetadataSynchronizer
        // @TODO update existing ContactMetadata (in order to not throw error...)
        foreach ($this->contactMetadata as $contactMetadata) {

            $metadataObj = new ContactMetadata($contactMetadata['name'], $contactMetadata['datatype']);

            try {
                $response = $this->contactMetadataManager->create($metadataObj);
                $output->writeln(sprintf('<info>%s:%s added!</info>', $contactMetadata['name'], $contactMetadata['datatype']));
            } catch (\Exception $e) {
                $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
            }

        }
    }
}
