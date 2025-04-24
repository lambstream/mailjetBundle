<?php

namespace Mailjet\MailjetBundle\Exception;

use Mailjet\Response;

/**
 * Handle Mailjet API errors
 */
class MailjetException extends \Exception
{
    /**
     * @var string
     */
    private string $errorInfo = '';

    /**
     * @var string
     */
    private string $errorMessage = '';

    /**
     * @var string
     */
    private string $errorIdentifier = '';

    /**
     * https://dev.mailjet.com/guides/#about-the-mailjet-restful-api
     * @param Response   $response
     * @param \Throwable $previous
     */
    public function __construct(
        private int $statusCode = 0,
        protected $message = null,
        protected ?Response $response = null,
        protected ?\Throwable $previous = null
    ) {
        // if you pass a Mailjet\Response
        if ($response) {
            $this->statusCode = $response->getStatus();
            $message = sprintf('%s: %s', $message, $response->getReasonPhrase());
            $this->setErrorFromResponse($response);
        }

        parent::__construct($message, $this->statusCode, $previous);
    }

    /**
     * Configure MailjetException from Mailjet\Response
     * @method setErrorFromResponse
     *
     * @param Response $response
     */
    private function setErrorFromResponse(Response $response)
    {
        $this->statusCode = $response->getStatus();

        $body = $response->getBody();
        if (isset($body['ErrorInfo'])) {
            $this->errorInfo = $body['ErrorInfo'];
        }
        if (isset($body['ErrorMessage'])) {
            $this->errorMessage = $body['ErrorMessage'];
        }
        if (isset($body['ErrorIdentifier'])) {
            $this->errorIdentifier = $body['ErrorIdentifier'];
        }
    }

    /**
     * @return string
     */
    public function getErrorInfo()
    {
        return $this->errorInfo;
    }

    /**
     * @return string
     */
    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    /**
     * @return string
     */
    public function getErrorIdentifier(): string
    {
        return $this->errorIdentifier;
    }

}
