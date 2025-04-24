<?php

namespace Mailjet\MailjetBundle\Controller;

use JsonException;
use Mailjet\MailjetBundle\Event\CallbackEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Best practice
 * We advise you to follow some basic guidelines for implementation and usage.
 *
 * Process the payload received asynchronously : as much as possible, the webhook script should rely on an asynchronous consumer process that will use the data saved by your webhook. You should keep out of your webhook logic all cross
 * matches of the delivered events with other ressources of our API or your internal database. This step will allow your webhook to answer in a timely manner to our calls and avoid it to timeout and being retried by our server. Check
 * regularly your server logs for any errors : all non 200 errors would be retried and could cause an increasing volume of calls to your system. Leverage the transactional message tagging to simplify reconciliation between the events and
 * your own system.
 */
class EventController extends AbstractController
{
    public function __construct(
        protected EventDispatcherInterface $dispatcher,
        private string $eventEndpointToken,
    ) {
    }

    /**
     * Endpoint for the mailjet events (webhooks)
     * https://dev.mailjet.com/guides/#events
     * https://live-event-dashboard-demo.mailjet.com/
     */
    #[\Symfony\Component\Routing\Annotation\Route('/mailjet-event/endpoint/{token}', name: 'mailjet_event_endpoint', methods: ['POST'])]
    public function indexAction(Request $request, $token)
    {
        // Token validation
        if ($this->getToken() !== $token) {
            throw new BadRequestHttpException('Token mismatch');
        }

        $data = $this->extractData($request);

        if (!$data) {
            throw new BadRequestHttpException('Malformatted or missing data');
        }

        if (isset($data['event'])) {
            $data = [$data];
        }
        /*
            Please note that the event types in the collection can be mixed.
            We group together all the events of the last second for the same webhook url.
        */
        // NOTE: use a better dispatcher such as rabbitMQ if you have a huge amount of events (sent, open, click can be a lot...)
        $dispatcher = $this->getDispatcher();

        foreach ($data as $key => $callbackData) {
            match ($callbackData['event']) {
                'sent' => $dispatcher->dispatch(new CallbackEvent($callbackData), CallbackEvent::EVENT_SENT),
                'open' => $dispatcher->dispatch(new CallbackEvent($callbackData), CallbackEvent::EVENT_OPEN),
                'click' => $dispatcher->dispatch(new CallbackEvent($callbackData), CallbackEvent::EVENT_CLICK),
                'bounce' => $dispatcher->dispatch(new CallbackEvent($callbackData), CallbackEvent::EVENT_BOUNCE),
                'spam' => $dispatcher->dispatch(new CallbackEvent($callbackData), CallbackEvent::EVENT_SPAM),
                'blocked' => $dispatcher->dispatch(new CallbackEvent($callbackData), CallbackEvent::EVENT_BLOCKED),
                'unsub' => $dispatcher->dispatch(new CallbackEvent($callbackData), CallbackEvent::EVENT_UNSUB),
                default => throw new BadRequestHttpException('Type mismatch'),
            };
        }

        return new JsonResponse(['success' => true]);
    }

    /**
     * Override this to use another event dispatcher
     *
     * @return EventDispatcherInterface
     */
    public function getDispatcher(): EventDispatcherInterface
    {
        // NOTE: use a better dispatcher such as rabbitMQ if you have a huge amount of events
        return $this->dispatcher;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    private function extractData(
        Request $request,
    ): array {
        try {
            return json_decode($request->getContent(), true, flags: JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return [];
        }
    }

    /**
     * @return string
     */
    private function getToken(): string
    {
        return $this->eventEndpointToken;
    }
}
