<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiFactory\Exception\ParseResponseException;
use Siganushka\ApiFactory\Tests\Fixtures\FooRequest;
use Siganushka\ApiFactory\Tests\Fixtures\FooRequestWithOverrideResponse;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class RequestTest extends TestCase
{
    protected FooRequest $request;

    protected function setUp(): void
    {
        $data = ['message' => 'hello world'];
        $body = json_encode($data, \JSON_THROW_ON_ERROR);

        $response = new MockResponse($body);
        $client = new MockHttpClient($response);

        $this->request = new FooRequest($client);
    }

    public function testResolve(): void
    {
        static::assertEquals([
            'a' => 'hello',
            'b' => 'world',
        ], $this->request->resolve(['a' => 'hello']));

        static::assertEquals([
            'a' => 'hi',
            'b' => 'siganushka',
            'c' => 123,
        ], $this->request->resolve(['a' => 'hi', 'b' => 'siganushka', 'c' => 123]));
    }

    public function testBuild(): void
    {
        $requestOptions = $this->request->build(['a' => 'hello']);

        static::assertSame('GET', $requestOptions->getMethod());
        static::assertSame('http://localhost/foo', $requestOptions->getUrl());
        static::assertEquals([
            'query' => [
                'options_a' => 'hello',
                'options_b' => 'world',
            ],
        ], $requestOptions->toArray());

        $requestOptions = $this->request->build(['a' => 'hi', 'b' => 'siganushka', 'c' => 123]);
        static::assertEquals([
            'query' => [
                'options_a' => 'hi',
                'options_b' => 'siganushka',
                'options_c' => 123,
            ],
        ], $requestOptions->toArray());
    }

    public function testSend(): void
    {
        static::assertSame(['message' => 'hello world'], $this->request->send(['a' => 'hello']));
    }

    public function testSendWithOverrideResponse(): void
    {
        $request = new FooRequestWithOverrideResponse();
        static::assertEquals(['message' => 'hello siganushka'], $request->send(['a' => 'hello']));
    }

    public function testSendWithParseResponseException(): void
    {
        $this->expectException(ParseResponseException::class);
        $this->expectExceptionCode(65535);
        $this->expectExceptionMessage('invalid argument error');

        $data = ['err_code' => 65535, 'err_msg' => 'invalid argument error.'];
        $body = json_encode($data, \JSON_THROW_ON_ERROR);

        $response = new MockResponse($body);
        $client = new MockHttpClient($response);

        (new FooRequest($client))->send(['a' => 'hello']);
    }

    public function testMissingOptionsException(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "a" is missing');

        $this->request->build();
    }

    public function testInvalidOptionsException(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "c" with value "aaa" is expected to be of type "int", but is of type "string"');

        $this->request->build(['a' => 'hello', 'c' => 'aaa']);
    }

    public function testUndefinedOptionsException(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessage('The option "d" does not exist. Defined options are: "a", "b", "c"');

        $this->request->build(['d' => 'xyz']);
    }
}
