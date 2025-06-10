# API Factory

An API Factory Abstraction Layer.

### Installation

```bash
$ composer require siganushka/api-factory
```

### Usage

An API class needs to implement 3 methods:

```php
// Configure the options required for the request
AbstractRequest::configureOptions(OptionsResolver $resolver): void;

// Configure the request with resolved options
AbstractRequest::configureRequest(RequestOptions $request, array $options): void;

// Parse the response data (ParseResponseException when there is an error)
AbstractRequest::parseResponse(ResponseInterface $response): mixed;
```

Github get a user example:

https://docs.github.com/cn/rest/users/users#get-a-user

```php
namespace App\Request;

use Siganushka\ApiFactory\AbstractRequest;
use Siganushka\ApiFactory\RequestOptions;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @extends AbstractRequest<array>
 */
class GithubUsers extends AbstractRequest
{
    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('username');
        $resolver->setAllowedTypes('username', 'string');
    }

    protected function configureRequest(RequestOptions $request, array $options): void
    {
        $request
            ->setMethod('GET')
            ->setUrl(sprintf('https://api.github.com/users/%s', $options['username']))
        ;
    }

    protected function parseResponse(ResponseInterface $response): array
    {
        return $response->toArray();
    }
}
```

### Implementation

* [Github API](https://github.com/siganushka/github-api)
* [Wechat API](https://github.com/siganushka/wechat-api)
* [Wxpay API](https://github.com/siganushka/wxpay-api)
* [Alipay API](https://github.com/siganushka/alipay-api)
