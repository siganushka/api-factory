<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests\Response;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiFactory\Response\StaticResponse;
use Symfony\Component\HttpClient\Exception\JsonException;

class StaticResponseTest extends TestCase
{
    public function testAll(): void
    {
        $data = ['message' => 'hello world'];
        $body = json_encode($data, \JSON_THROW_ON_ERROR);

        $response = new StaticResponse($body);
        static::assertSame(200, $response->getStatusCode());
        static::assertSame([], $response->getHeaders());
        static::assertSame($body, $response->getContent());
        static::assertSame($data, $response->toArray());
        static::assertSame([], $response->getInfo());

        $response = new StaticResponse($body, ['Content-Type' => 'application/json'], 400);
        static::assertSame(400, $response->getStatusCode());
        static::assertSame(['Content-Type' => 'application/json'], $response->getHeaders());
        static::assertSame($body, $response->getContent());
        static::assertSame($data, $response->toArray());
        static::assertSame([], $response->getInfo());
    }

    public function testCreateFromArray(): void
    {
        $response = StaticResponse::createFromArray(['message' => '你好'], ['X-Foo' => 'bar']);

        static::assertSame('{"message":"你好"}', $response->getContent());
        static::assertSame(['message' => '你好'], $response->toArray());
        static::assertSame(['X-Foo' => 'bar'], $response->getHeaders());
    }

    public function testCancelAndGetInfo(): void
    {
        $response = new StaticResponse('{}');

        $response->cancel();

        static::assertSame([], $response->getInfo());
        static::assertSame([], $response->getInfo('http_code'));
    }

    public function testInvalidBodyException(): void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('Syntax error');

        $response = new StaticResponse('[{');
        $response->toArray();
    }

    public function testNullBodyException(): void
    {
        $this->expectException(JsonException::class);
        $this->expectExceptionMessage('JSON content was expected to decode to an array, "null" returned');

        $body = json_encode(null, \JSON_THROW_ON_ERROR);

        $response = new StaticResponse($body);
        $response->toArray();
    }
}
