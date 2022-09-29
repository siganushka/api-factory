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
        $data = ['message' => 'hello siganushka'];
        $body = json_encode($data);

        return new CachedResponse($body);
    }
}
