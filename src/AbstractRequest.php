<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract class AbstractRequest implements RequestInterface
{
    use ResolverTrait;

    protected HttpClientInterface $httpClient;

    public function __construct(?HttpClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? HttpClient::create();
    }

    public function build(array $options = []): RequestOptions
    {
        $request = new RequestOptions();
        $this->configureRequest($request, $this->resolve($options));

        return $request;
    }

    /**
     * @return mixed
     */
    public function send(array $options = [])
    {
        $request = $this->build($options);
        $response = $this->sendRequest($request);

        return $this->parseResponse($response);
    }

    protected function sendRequest(RequestOptions $request): ResponseInterface
    {
        $method = $request->getMethod();
        $url = $request->getUrl();

        if (null === $method) {
            throw new \RuntimeException('The request "method" option must be set.');
        }

        if (null === $url) {
            throw new \RuntimeException('The request "url" option must be set.');
        }

        return $this->httpClient->request($method, $url, $request->toArray());
    }

    abstract protected function configureRequest(RequestOptions $request, array $options): void;

    /**
     * @return mixed
     */
    abstract protected function parseResponse(ResponseInterface $response);
}
