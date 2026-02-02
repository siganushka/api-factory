<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests\Fixtures;

use Siganushka\ApiFactory\ResolverInterface;
use Siganushka\ApiFactory\ResolverTrait;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FooResolver implements ResolverInterface
{
    use ResolverTrait;

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->define('date')
            ->required()
            ->allowedTypes(\DateTimeInterface::class)
        ;

        $resolver
            ->define('text')
            ->default('world')
            ->allowedTypes('string')
        ;

        $resolver
            ->define('message')
            /* @phpstan-ignore argument.type */
            ->default(static fn (Options $options) => \sprintf('Hello %s', $options['text']))
            ->allowedTypes('string')
        ;
    }
}
