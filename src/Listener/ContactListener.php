<?php

namespace Mailjet\MailjetBundle\Listener;

use Mailjet\MailjetBundle\Event\ContactEvent;
use Mailjet\MailjetBundle\Manager\ContactsListManager;

class ContactListener
{
    public function __construct(protected ContactsListManager $contactManager)
    {
    }

    public function onSubscribe(ContactEvent $event): void
    {
        $this->contactManager->subscribe(
            $event->getListId(),
            $event->getContact()
        );
    }

    public function onUnsubscribe(ContactEvent $event): void
    {
        $this->contactManager->unsubscribe(
            $event->getListId(),
            $event->getContact()
        );
    }

    public function onUpdate(ContactEvent $event): void
    {
        $this->contactManager->update(
            $event->getListId(),
            $event->getContact()
        );
    }

    public function onDelete(ContactEvent $event): void
    {
        $this->contactManager->delete(
            $event->getListId(),
            $event->getContact()
        );
    }

    // @TODO How to change user email? (workaround: remove old, add new...)
    public function onChangeEmail(ContactEvent $event): void
    {
        $this->contactManager->changeEmail(
            $event->getListId(),
            $event->getContact(),
            $event->getOldEmail()
        );
    }
}
