<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests\Fixtures;

use Siganushka\ApiFactory\ResolverExtensionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UnmatchedResolverExtension implements ResolverExtensionInterface
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('unmatched', true);
    }

    public static function getExtendedClasses(): iterable
    {
        return [
            \stdClass::class,
        ];
    }
}
