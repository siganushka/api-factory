<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory;

use Symfony\Component\HttpClient\HttpOptions;

class RequestOptions extends HttpOptions
{
    private ?string $method = null;
    private ?string $url = null;

    public function __construct(?string $method = null, ?string $url = null)
    {
        $this->method = $method;
        $this->url = $url;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

    public function setMethod(string $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function __toString(): string
    {
        $serialized = serialize([
            $this->method,
            $this->url,
            $this->toArray(),
        ]);

        return \sprintf('%s_%s', __CLASS__, md5($serialized));
    }
}
