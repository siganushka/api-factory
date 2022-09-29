<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests\Exception;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiFactory\Exception\ParseResponseException;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ParseResponseExceptionTest extends TestCase
{
    public function testAll(): void
    {
        $mockResponse = new MockResponse('test');
        $client = new MockHttpClient($mockResponse);

        $response = $client->request('GET', '/');

        $previous = new \InvalidArgumentException('foo');
        $exception = new ParseResponseException($response, 'bar', 1024, $previous);

        static::assertInstanceOf(\Throwable::class, $exception);
        static::assertSame($response, $exception->getResponse());
        static::assertSame('test', $exception->getResponse()->getContent());
        static::assertSame($previous, $exception->getPrevious());
        static::assertSame('bar', $exception->getMessage());
        static::assertSame(1024, $exception->getCode());
    }
}
