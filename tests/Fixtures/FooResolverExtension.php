<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests\Fixtures;

use Siganushka\ApiFactory\ResolverExtensionInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FooResolverExtension implements ResolverExtensionInterface
{
    private \DateTimeInterface $date;

    public function __construct(\DateTimeInterface $date = null)
    {
        $this->date = $date ?? new \DateTime();
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('date', $this->date);
        $resolver->setDefault('text', 'symfony');
    }

    public static function getExtendedClasses(): iterable
    {
        return [
            FooResolver::class,
        ];
    }
}
