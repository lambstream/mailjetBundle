<?php

namespace spec\Mailjet\MailjetBundle\Command;

use Mailjet\MailjetBundle\Manager\ContactMetadataManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SyncContactMetadataCommandSpec extends ObjectBehavior
{
    function it_is_initializable(ContactMetadataManager $contactMetadataManager)
    {
        $this->beConstructedWith([], $contactMetadataManager);

        $this->shouldHaveType('Mailjet\MailjetBundle\Command\SyncContactMetadataCommand');
    }
}
