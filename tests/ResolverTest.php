<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiFactory\ResolverInterface;
use Siganushka\ApiFactory\Tests\Fixtures\FooResolver;
use Siganushka\ApiFactory\Tests\Fixtures\FooResolverExtension;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

class ResolverTest extends TestCase
{
    protected ?ResolverInterface $resolver = null;

    protected function setUp(): void
    {
        $this->resolver = new FooResolver();
    }

    protected function tearDown(): void
    {
        $this->resolver = null;
    }

    public function testResolve(): void
    {
        $date = new \DateTimeImmutable();
        static::assertEquals([
            'date' => $date,
            'text' => 'world',
            'message' => 'Hello world',
        ], $this->resolver->resolve(['date' => $date]));

        $newDate = $date->modify('+7 days');
        static::assertEquals([
            'date' => $newDate,
            'text' => 'siganushka',
            'message' => 'Hello siganushka',
        ], $this->resolver->resolve(['text' => 'siganushka', 'date' => $newDate]));
    }

    public function testExtend(): void
    {
        $date = new \DateTime('1970-01-01 00:00:00');
        $this->resolver->extend(new FooResolverExtension($date));

        static::assertEquals([
            'date' => $date,
            'text' => 'symfony',
            'message' => 'Hello symfony',
        ], $this->resolver->resolve());
    }

    public function testDateMissingOptionsException(): void
    {
        $this->expectException(MissingOptionsException::class);
        $this->expectExceptionMessage('The required option "date" is missing');

        $this->resolver->resolve();
    }

    public function testDateInvalidOptionsException(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "date" with value "foo" is expected to be of type "DateTimeInterface", but is of type "string"');

        $this->resolver->resolve(['date' => 'foo']);
    }

    public function testTextInvalidOptionsException(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "text" with value 123 is expected to be of type "string", but is of type "int"');

        $this->resolver->resolve(['date' => new \DateTime(), 'text' => 123]);
    }

    public function testMessageInvalidOptionsException(): void
    {
        $this->expectException(InvalidOptionsException::class);
        $this->expectExceptionMessage('The option "message" with value 123 is expected to be of type "string", but is of type "int"');

        $this->resolver->resolve(['date' => new \DateTime(), 'message' => 123]);
    }
}
