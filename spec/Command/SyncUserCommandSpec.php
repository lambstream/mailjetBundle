<?php

namespace spec\Mailjet\MailjetBundle\Command;

use Mailjet\MailjetBundle\Synchronizer\ContactsListSynchronizer;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Container;

class SyncUserCommandSpec extends ObjectBehavior
{
    function it_is_initializable(
        ContactsListSynchronizer $synchronizer,
        Container $serviceContainer,
    ) {
        $this->beConstructedWith([], $synchronizer, $serviceContainer);

        $this->shouldHaveType('Mailjet\MailjetBundle\Command\SyncUserCommand');
    }
}
