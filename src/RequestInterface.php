<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory;

/**
 * @template TResponseData
 */
interface RequestInterface extends ResolverInterface
{
    public function build(array $options = []): RequestOptions;

    /**
     * @return TResponseData
     */
    public function send(array $options = []): mixed;
}
