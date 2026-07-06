<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Response;

use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Contracts\HttpClient\ResponseInterface;

class StaticResponse implements ResponseInterface
{
    final public function __construct(
        private readonly string $body,
        private readonly array $headers = [],
        private readonly int $status = 200)
    {
    }

    public static function createFromArray(array $data, array $headers = []): self
    {
        $body = json_encode($data, \JSON_UNESCAPED_UNICODE | \JSON_THROW_ON_ERROR);

        return new static($body, $headers);
    }

    public function getStatusCode(): int
    {
        return $this->status;
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
        try {
            $data = json_decode($this->body, true, flags: \JSON_THROW_ON_ERROR);
        } catch (\JsonException $th) {
            throw new JsonException($th->getMessage(), previous: $th);
        }

        if (!\is_array($data)) {
            throw new JsonException(\sprintf('JSON content was expected to decode to an array, "%s" returned.', get_debug_type($data)));
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
