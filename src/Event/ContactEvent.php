<?php

namespace Mailjet\MailjetBundle\Event;

use Mailjet\MailjetBundle\Model\Contact;
use Symfony\Contracts\EventDispatcher\Event;

class ContactEvent extends Event
{
    public const EVENT_SUBSCRIBE = 'mailjet.event.subscribe';
    public const EVENT_UNSUBSCRIBE = 'mailjet.event.unsubscribe';
    public const EVENT_UPDATE = 'mailjet.event.update';
    public const EVENT_DELETE = 'mailjet.event.delete';
    public const EVENT_CHANGE_EMAIL = 'mailjet.event.change_email'; # not implemented yet

    public function __construct(protected string $listId, protected Contact $contact, protected ?string $oldEmail = null)
    {
    }

    public function getListId(): string
    {
        return $this->listId;
    }

    public function getContact(): Contact
    {
        return $this->contact;
    }

    public function getOldEmail(): ?string
    {
        return $this->oldEmail;
    }
}
