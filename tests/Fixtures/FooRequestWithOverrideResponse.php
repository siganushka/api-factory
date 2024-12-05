<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests\Fixtures;

use Siganushka\ApiFactory\RequestOptions;
use Siganushka\ApiFactory\Response\StaticResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class FooRequestWithOverrideResponse extends FooRequest
{
    protected function sendRequest(RequestOptions $request): ResponseInterface
    {
        return StaticResponse::createFromArray(['message' => 'hello siganushka']);
    }
}
