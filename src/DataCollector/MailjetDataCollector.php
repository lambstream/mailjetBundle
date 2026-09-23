<?php

namespace Mailjet\MailjetBundle\DataCollector;

use Mailjet\MailjetBundle\Client\MailjetClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

/**
 * Class MailjetDataCollector
 */
class MailjetDataCollector extends DataCollector
{
    /**
     * Mailjet client for common API Call
     * @var MailjetClient
     */
    protected $client;

    /**
     * @param MailjetClient $client
     */
    public function __construct(MailjetClient $client)
    {
        $this->client = $client;
    }

    /**
     * Collects data for the given Request and Response.
     *
     * @param Request    $request   A Request instance
     * @param Response   $response  A Response instance
     * @param \Exception $exception An Exception instance
     */
    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {

        $this->data = $this->client->getCalls();
    }

    /**
     * Returns the name of the collector.
     *
     * @return string The collector name
     */
    public function getName(): string
    {
        return 'mailjet';
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Return call number
     * @method getCallCount
     * @return int
     */
    public function getCallCount(): int
    {
        return count($this->data);
    }

	/**
	 * Resets this data collector to its initial state.
	 */
	public function reset(): void
	{
		$this->data = array();
	}
}
