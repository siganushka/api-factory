<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests\Fixtures;

use Siganushka\ApiFactory\RequestOptions;
use Siganushka\ApiFactory\Response\CachedResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FooRequestWithOverrideResponse extends FooRequest
{
    protected function sendRequest(RequestOptions $request): ResponseInterface
    {
        $body = json_encode(['message' => 'hello siganushka']);
        if (false === $body) {
            throw new \RuntimeException('Unable to JSON encode.');
        }

        return new CachedResponse($body);
    }
}
