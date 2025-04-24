<?php

namespace Mailjet\MailjetBundle\Event;

use Symfony\Contracts\EventDispatcher\Event;

/**
 * Documentation : https://dev.mailjet.com/email-api/v3/eventcallbackurl/
 */
class CallbackEvent extends Event
{
    public const EVENT_SENT = 'mailjet.event.sent';
    public const EVENT_OPEN = 'mailjet.event.open';
    public const EVENT_CLICK = 'mailjet.event.click';
    public const EVENT_BOUNCE = 'mailjet.event.bounce';
    public const EVENT_SPAM = 'mailjet.event.spam';
    public const EVENT_BLOCKED = 'mailjet.event.blocked';
    public const EVENT_UNSUB = 'mailjet.event.unsub';
    public const EVENT_TYPOFIX = 'mailjet.event.typofix';
    public const EVENT_PARSEAPI = 'mailjet.event.parseapi';
    public const EVENT_NEWSENDER = 'mailjet.event.newsender';
    public const EVENT_NEWSENDERAUTOVALID = 'mailjet.event.newsenderautovalid';

    /**
     * @param array $data
     */
    public function __construct(
        /**
         * array of data payload from Mailjet Event
         *
         * @var array
         */
        protected array $data,
    ) {
    }

    /**
     * Get data payload from Mailjet Event
     * @method getData
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }
}
