<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests;

use PHPUnit\Framework\TestCase;
use Siganushka\ApiFactory\ResolverExtensionInterface;
use Siganushka\ApiFactory\Tests\Fixtures\FooResolver;
use Siganushka\ApiFactory\Tests\Fixtures\FooResolverExtension;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResolverExtensionTest extends TestCase
{
    protected ResolverExtensionInterface $extension;

    protected function setUp(): void
    {
        $this->extension = new FooResolverExtension();
    }

    public function testConfigureOptions(): void
    {
        $resolver = new OptionsResolver();
        $this->extension->configureOptions($resolver);

        $resolved = $resolver->resolve();
        static::assertInstanceOf(\DateTimeInterface::class, $resolved['date']);
        static::assertSame('symfony', $resolved['text']);
    }

    public function testGetExtendedClasses(): void
    {
        static::assertEquals([
            FooResolver::class,
        ], FooResolverExtension::getExtendedClasses());
    }
}
