<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory;

use Symfony\Component\OptionsResolver\OptionsResolver;

trait ResolverTrait
{
    /**
     * @var array<string, ResolverExtensionInterface>
     */
    protected array $extensions = [];

    public function resolve(array $options = []): array
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        foreach ($this->extensions as $extension) {
            $extension->configureOptions($resolver);
        }

        return $resolver->resolve($options);
    }

    /**
     * @return static
     */
    public function extend(ResolverExtensionInterface $extension)
    {
        $this->extensions[$extension::class] = $extension;

        return $this;
    }

    abstract protected function configureOptions(OptionsResolver $resolver): void;
}
