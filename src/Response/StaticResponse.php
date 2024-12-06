<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Response;

use Symfony\Contracts\HttpClient\ResponseInterface;

class StaticResponse implements ResponseInterface
{
    private string $body;
    private array $headers;

    final public function __construct(string $body, array $headers = [])
    {
        $this->body = $body;
        $this->headers = $headers;
    }

    /**
     * @deprecated use createFromArray instead
     */
    public static function createFromJson(array $data): self
    {
        return static::createFromArray($data);
    }

    public static function createFromArray(array $data, array $headers = []): self
    {
        $body = json_encode($data, \JSON_UNESCAPED_UNICODE);
        if (!\is_string($body)) {
            throw new \RuntimeException('Unable to JSON encode.');
        }

        return new static($body, $headers);
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
        $data = json_decode($this->body, true);
        if (!\is_array($data)) {
            throw new \RuntimeException('Unable to JSON decode.');
        }

        return $data;
    }

    public function cancel(): void
    {
    }

    public function getInfo(?string $type = null): array
    {
        return [];
    }
}
