<?php

declare(strict_types=1);

namespace Siganushka\ApiFactory\Tests\Fixtures;

use Siganushka\ApiFactory\AbstractRequest;
use Siganushka\ApiFactory\Exception\ParseResponseException;
use Siganushka\ApiFactory\RequestOptions;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @extends AbstractRequest<array>
 */
class FooRequest extends AbstractRequest
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->define('a')
            ->required()
            ->allowedTypes('string')
        ;

        $resolver
            ->define('b')
            ->default('world')
            ->allowedTypes('string')
        ;

        $resolver
            ->define('c')
            ->allowedTypes('int')
        ;
    }

    protected function configureRequest(RequestOptions $request, array $options): void
    {
        $query = [
            'options_a' => $options['a'],
            'options_b' => $options['b'],
        ];

        if (isset($options['c'])) {
            $query['options_c'] = $options['c'];
        }

        $request
            ->setMethod('GET')
            ->setUrl('/foo')
            ->setQuery($query)
        ;
    }

    protected function parseResponse(ResponseInterface $response): array
    {
        $result = $response->toArray();

        $errCode = $result['err_code'] ?? 0;
        $errMsg = $result['err_msg'] ?? '';

        if (0 === $errCode) {
            return $result;
        }

        throw new ParseResponseException($response, $errMsg, $errCode);
    }
}
