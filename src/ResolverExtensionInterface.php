<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory;

use Symfony\Component\OptionsResolver\OptionsResolver;

interface ResolverExtensionInterface
{
    public function configureOptions(OptionsResolver $resolver): void;

    public static function getExtendedClasses(): iterable;
}
