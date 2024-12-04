<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory;

interface RequestInterface extends ResolverInterface
{
    public function build(array $options = []): RequestOptions;

    /**
     * @return mixed
     */
    public function send(array $options = []);
}
