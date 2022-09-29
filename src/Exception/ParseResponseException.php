<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Exception;

use Symfony\Contracts\HttpClient\ResponseInterface;

class ParseResponseException extends \RuntimeException
{
    protected ResponseInterface $response;

    public function __construct(ResponseInterface $response, string $message = '', int $code = 0, \Throwable $previous = null)
    {
        $this->response = $response;

        parent::__construct($message, $code, $previous);
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
