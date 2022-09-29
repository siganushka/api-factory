<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory;

interface RequestInterface extends ResolverInterface
{
    public function build(array $options = []): RequestOptions;

    /**
     * @psalm-return mixed
     */
    public function send(array $options = []);
}
