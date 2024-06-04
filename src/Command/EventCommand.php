<?php

namespace Mailjet\MailjetBundle\Command;

use Mailjet\MailjetBundle\Manager\EventCallbackUrlManager;
use Mailjet\MailjetBundle\Model\EventCallbackUrl;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\RouterInterface;

class EventCommand extends Command
{
    private EventCallbackUrlManager $callbackUrlManager;

    private RouterInterface $router;

    private string $eventEndpointRoute;

    private string $endpointToken;


    public function __construct(
        EventCallbackUrlManager $callbackUrlManager,
        RouterInterface $router,
        string $eventEndpointRoute,
        string $endpointToken,
    ) {
        $this->callbackUrlManager = $callbackUrlManager;
        $this->router = $router;
        $this->eventEndpointRoute = $eventEndpointRoute;
        $this->endpointToken = $endpointToken;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('mailjet:event-endpoint')
            ->setDescription('Prints URL endpoint that should be configured at mailjet.com website')
            ->addArgument('baseurl', InputArgument::REQUIRED, 'Baseurl with domain to be used in URL, i.e. https://example.com')
            ->addOption(
                'event-type',
                null,
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'List of eventType: ["sent", "open", "click", "bounce", "blocked", "spam", "unsub"], all by default',
                null
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domain = $input->getArgument('baseurl');
        $uri = $this->router->generate($this->eventEndpointRoute, [
            'token' => $this->endpointToken,
        ]);
        $url = sprintf('%s/%s', rtrim($domain, '/'), ltrim($uri, '/'));

        if ($input->getOption('event-type')) {
            $eventTypes = $input->getOption('event-type');
        } else {
            $eventTypes = ["sent", "open", "click", "bounce", "blocked", "spam", "unsub"];
        }

        foreach ($eventTypes as $eventType) {
            $eventCallBackUrl = new EventCallbackUrl($url, $eventType, true);

            try {
                $this->callbackUrlManager->get($eventType);
                $output->writeln('update ' . $eventType);
                $this->callbackUrlManager->update($eventType, $eventCallBackUrl);
            } catch (\Exception $e) {
                $output->writeln('create ' . $eventType);
                $this->callbackUrlManager->create($eventCallBackUrl);
            }
        }

        $output->writeln(sprintf('<info>%s callback url has been added to your Mailjet account!</info>', $url));
    }
}
