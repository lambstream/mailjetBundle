<?php

namespace spec\Mailjet\MailjetBundle\Command;

use Mailjet\MailjetBundle\Manager\EventCallbackUrlManager;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Routing\RouterInterface;

class EventCommandSpec extends ObjectBehavior
{
    function it_is_initializable(
        EventCallbackUrlManager $callbackUrlManager,
        RouterInterface $router,
    ) {
        $this->beConstructedWith($callbackUrlManager, $router, 'eventEntpointRoute', 'TOKEN');
        $this->shouldHaveType('Mailjet\MailjetBundle\Command\EventCommand');
    }
}
