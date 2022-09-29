<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiFactory\ResolverConfigurator;
use Siganushka\ApiFactory\ResolverExtensionInterface;
use Siganushka\ApiFactory\Tests\Fixtures\FooResolver;
use Siganushka\ApiFactory\Tests\Fixtures\FooResolverExtension;

class ResolverConfiguratorTest extends TestCase
{
    public function testConfigure(): void
    {
        $date = new \DateTime('1970-01-01 00:00:00');

        $extensions = [
            new FooResolverExtension($date),
        ];

        $resolver = new FooResolver();

        $configurator = new ResolverConfigurator($extensions);
        $configurator->configure($resolver);

        static::assertSame($date, $resolver->resolve()['date']);
    }

    public function testUnexpectedValueException(): void
    {
        $this->expectException(\UnexpectedValueException::class);
        $this->expectExceptionMessage(sprintf('Expected argument of type "%s", "%s" given', ResolverExtensionInterface::class, \stdClass::class));

        $extensions = [
            new \stdClass(),
        ];

        new ResolverConfigurator($extensions);
    }
}
