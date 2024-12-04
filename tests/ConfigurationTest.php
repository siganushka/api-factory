<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiFactory\Tests\Fixtures\FooConfiguration;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;

class ConfigurationTest extends TestCase
{
    public function testAll(): void
    {
        $options = [
            'foo' => 'hello',
            'baz' => false,
        ];

        $configuration = new FooConfiguration($options);
        static::assertInstanceOf(\Countable::class, $configuration);
        static::assertInstanceOf(\IteratorAggregate::class, $configuration);
        static::assertInstanceOf(\ArrayAccess::class, $configuration);
        static::assertSame(3, $configuration->count());

        static::assertTrue($configuration->offsetExists('foo'));
        static::assertTrue($configuration->offsetExists('bar'));
        static::assertTrue($configuration->offsetExists('baz'));

        static::assertSame('hello', $configuration->offsetGet('foo'));
        static::assertSame(123, $configuration->offsetGet('bar'));
        static::assertFalse($configuration->offsetGet('baz'));

        static::assertEquals([
            'foo' => 'hello',
            'bar' => 123,
            'baz' => false,
        ], $configuration->toArray());
    }

    public function testInvalidOptionsException(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "bar" with value "aaa" is expected to be of type "int", but is of type "string"');

        new FooConfiguration(['foo' => 'hello', 'bar' => 'aaa']);
    }

    public function testUndefinedOptionsException(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessage('The option "etc" does not exist. Defined options are: "bar", "baz", "foo"');

        new FooConfiguration(['foo' => 'hello', 'etc' => 'test']);
    }

    public function testOffsetGetUndefinedOptionsException(): void
    {
        $this->expectException(UndefinedOptionsException::class);
        $this->expectExceptionMessage('The option "etc" does not exist');

        $options = [
            'foo' => 'hello',
            'baz' => false,
        ];

        $configuration = new FooConfiguration($options);
        $configuration->offsetGet('etc');
    }

    public function testOffsetSetBadMethodCallException(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage(\sprintf('The method %s::offsetSet doesn\'t supported', FooConfiguration::class));

        $options = [
            'foo' => 'hello',
            'baz' => false,
        ];

        $configuration = new FooConfiguration($options);
        $configuration->offsetSet('etc', 'test');
    }

    public function testOffsetUnsetBadMethodCallException(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage(\sprintf('The method %s::offsetUnset doesn\'t supported', FooConfiguration::class));

        $options = [
            'foo' => 'hello',
            'baz' => false,
        ];

        $configuration = new FooConfiguration($options);
        $configuration->offsetUnset('baz');
    }
}
