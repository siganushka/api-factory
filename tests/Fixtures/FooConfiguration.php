<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests\Fixtures;

use Siganushka\ApiFactory\AbstractConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FooConfiguration extends AbstractConfiguration
{
    public static function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->define('foo')
            ->required()
            ->allowedTypes('string')
        ;

        $resolver
            ->define('bar')
            ->default(123)
            ->allowedTypes('int')
        ;

        $resolver
            ->define('baz')
            ->allowedTypes('bool')
        ;
    }
}
