<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory;

class ResolverConfigurator implements ResolverConfiguratorInterface
{
    /**
     * @var array<string, ResolverExtensionInterface>
     */
    private array $extensions = [];

    public function __construct(iterable $extensions = [])
    {
        foreach ($extensions as $extension) {
            if (!$extension instanceof ResolverExtensionInterface) {
                throw new \UnexpectedValueException(\sprintf('Expected argument of type "%s", "%s" given', ResolverExtensionInterface::class, get_debug_type($extension)));
            }

            $this->extensions[\get_class($extension)] = $extension;
        }
    }

    public function configure(ResolverInterface $resolver): void
    {
        foreach ($this->extensions as $extension) {
            foreach ($extension::getExtendedClasses() as $className) {
                if ($resolver instanceof $className) {
                    $resolver->extend($extension);
                }
            }
        }
    }
}
