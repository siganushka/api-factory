<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Response;

use Symfony\Contracts\HttpClient\ResponseInterface;

class CachedResponse implements ResponseInterface
{
    private string $body;
    private array $headers;

    final public function __construct(string $body, array $headers = [])
    {
        $this->body = $body;
        $this->headers = $headers;
    }

    public static function createFromJson(array $data): self
    {
        return new static(json_encode($data, \JSON_UNESCAPED_UNICODE));
    }

    public function getStatusCode(): int
    {
        return 200;
    }

    public function getHeaders(bool $throw = true): array
    {
        return $this->headers;
    }

    public function getContent(bool $throw = true): string
    {
        return $this->body;
    }

    public function toArray(bool $throw = true): array
    {
        return json_decode($this->body, true);
    }

    public function cancel(): void
    {
    }

    public function getInfo(string $type = null): array
    {
        return [];
    }
}
