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

### Example:

Github get a user: https://docs.github.com/cn/rest/users/users#get-a-user

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
        $resolve
            ->define('username')
            ->required()
            ->allowedTypes('string')
        ;
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

$request = new GithubUsers();

$result = $request->send();
// throws MissingOptionsException: The required options "username" are missing.

$result = $request->send(['username' => 'siganushka']);
// {
//  "login": "siganushka",
//  "id": 45844370,
//  "node_id": "MDQ6VXNlcjQ1ODQ0Mzcw",
//  "avatar_url": "https://avatars.githubusercontent.com/u/45844370?v=4",
//  "gravatar_id": "",
//  "url": "https://api.github.com/users/siganushka",
//  "html_url": "https://github.com/siganushka",
//  "followers_url": "https://api.github.com/users/siganushka/followers",
//  "following_url": "https://api.github.com/users/siganushka/following{/other_user}",
//  "gists_url": "https://api.github.com/users/siganushka/gists{/gist_id}",
//  ...
// }
```

### Implementation

* [Github API](https://github.com/siganushka/github-api)
* [Wechat API](https://github.com/siganushka/wechat-api)
* [Wxpay API](https://github.com/siganushka/wxpay-api)
* [Alipay API](https://github.com/siganushka/alipay-api)
