<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiFactory\RequestOptions;
use Symfony\Component\HttpClient\HttpOptions;

class RequestOptionsTest extends TestCase
{
    public function testAll(): void
    {
        $requestOptions = new RequestOptions();
        static::assertInstanceOf(HttpOptions::class, $requestOptions);
        static::assertNull($requestOptions->getMethod());
        static::assertNull($requestOptions->getUrl());
        static::assertEquals([], $requestOptions->toArray());

        $requestOptions->setMethod('GET');
        static::assertSame('GET', $requestOptions->getMethod());

        $requestOptions->setUrl('http://localhost');
        static::assertSame('http://localhost', $requestOptions->getUrl());

        $requestOptions->setQuery(['foo' => 'bar']);
        static::assertEquals(['query' => ['foo' => 'bar']], $requestOptions->toArray());
    }
}
