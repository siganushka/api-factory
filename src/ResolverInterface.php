<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory;

interface ResolverInterface
{
    public function resolve(array $options = []): array;

    /**
     * @return static
     */
    public function extend(ResolverExtensionInterface $extension);
}
