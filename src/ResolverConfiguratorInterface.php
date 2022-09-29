<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory;

interface ResolverConfiguratorInterface
{
    public function configure(ResolverInterface $resolver): void;
}
