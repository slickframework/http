# Slick Configuration

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

`Slick/Http` is an useful library for HTTP foundation. It implements the PSR-7 message
interface and has simple middleware dispatcher based on the PSR-15 middleware interfaces proposal
that can help you dealing with HTTP requests and use other middleware packages to compose it.

This package is compliant with PSR-2 code standards and PSR-4 autoload standards. It
also applies the [semantic version 2.0.0](http://semver.org) specification.

## Install

Via Composer

``` bash
$ composer require slick/http
```

## Usage

### Server Request Message

This is an handy way to have a HTTP request message that has all necessary information that was sent by
the client to the server.

Its very simple to get this:
``` php
use Slick\Http\Message\Server\Request;

$request = new Request();
```

The ``$request`` encapsulates all data as it has arrived at the
application from the CGI and/or PHP environment, including:
 - The values represented in $_SERVER.
 - Any cookies provided (generally via $_COOKIE)
 - Query string arguments (generally via $_GET, or as parsed via parse_str())
 - Upload files, if any (as represented by $_FILES)
 - Deserialized body parameters (generally from $_POST)

Consider the following message:
``` txt
POST /path?_goto=home HTTP/1.1
Host: www.example.org
Content-Type: application/x-www-form-urlencoded; charset=utf-8
Content-Lenght: 5
Authorization: Bearer PAOIPOI-ASD9POKQWEL-KQWELKAD==

foo=bar&bar=baz

```
Now lest retrieve its information using the ``$request`` object:

``` php
echo $request->getHeaderLine('Authorization');  // will print "Bearer PAOIPOI-ASD9POKQWEL-KQWELKAD=="

print_r($request->getParsedBody());
# Array (
#   [foo] => bar,
#   [bar] => baz
#)

print_r($request->getQueryParam());
# Array (
#   [_goto] => home
#)
```


### HTTP Server

The HTTP server is a middleware runner. You can pass a collection of middleware objects
to the server and they all will be called in their sequence in order to compose a
response for current server request.

Lets create a simple web application that will print out a greeting to a query parameter
named 'name'. Lets first create our middleware classes:

```php
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;

class Greeting implements MiddlewareInterface
{
  
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $params = $request->getQueryParams();
        $request = (isset($params['name'])
            ? $request->withAttribute('greeting', "Hello, {$params['name']}!")
            : $request;
        
        $response = $handler->handle($request);
        return $response;
    }
}
```
This middleware retrieves the query parameter with name and add an attribute to the
request object passed to the next middleware in the stack. Lets create our printer:

```php
use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;

class Printer implements MiddlewateInterface
{
  
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
      $greeting = $request->getAttribute('greeting', false);
      $text = $greeting ?: 'Hi!';
      
      $response = $handler->handle($request);
      
      $response->getBody()->write($text);
      
      return $response;
    }
}
```

Now lets create our main server application:

```php
use Slick\Http\Server\MiddlewareStack;
use Slick\Http\Message\Response;
use Slick\Http\Message\Server\Request;

$stack = (new MiddlewareStack())
    ->push(new Greeting())
    ->push(new Printer())
    ->push(function () { return new Response(200); }); // Order matters!

$response = $stack->process(new Request);

// Emit headers iteratively:
foreach ($response->getHeaders() as $name => $values) {
    header(sprintf('%s: %s', $name, implode(', ', $value)), false);
}

// Print out the message body
echo $response->getBody();

```

### HTTP Client

Working with HTTP requests (as a client) is one of the most common tasks nowadays. Almost every application
need to retrieve data from a web service or API and therefore an HTTP client is a must have.

``Slick\Http\Client\CurlHttpClient`` has a very simple interface:

``` php
public function send(RequestInterface $request): PromiseInterface
```

It depends of ``React\Promise`` and PHP's ``cURL`` extension to connect and make HTTP requests.
Lets see an example:

``` php
use Psr\Http\Message\ResponseInterface;
use Slick\Http\Client\CurlHttpClient;
use Slick\Http\Message\Request;
use Slick\Http\Message\Uri;

$request = new Request('GET', new Uri('https://example.com'));
$client = new CurlHttpClient();

$promise = $client->send($request);

$promise->then(
    function(ResponseInterface $response) { // handles the success response },
    function($failureReason) { // handles the failed request },
);

```

### Session

`Slick\Http\Session` has a very simple and ease implementation. By default it will use the
PHP session handling and it comes with a simple factory that you can use to create it:

```php
use Slick\Http\Session;

$session = Session::create();
```
##### Write session data
```php
$session->set('foo', 'bar');
```

##### Read session data
```php
$session->read('foo', null); // if foo is not found the default (null) will be returned.
```

##### Deleting session data
```php
$session->erase('foo');
```

You can create your own session drivers if you need to encrypt you data or change the save
handler to save in a database by simply implementing the `Slick\Http\Session\SessionDriverInterface`.
It also comes with a `Slick\Http\Session\Driver\AbstractDriver` class that has all the basic
operations of the interface already implemented.

Please check [documentation site](http://http.slick-framework.com) for a complete reference. 

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email slick.framework@gmail.com instead of using the issue tracker.

## Credits

- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/slick/http.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/slickframework/http/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/slickframework/http.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/slickframework/http.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/slick/http.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/slick/http
[link-travis]: https://travis-ci.org/slickframework/http
[link-scrutinizer]: https://scrutinizer-ci.com/g/slickframework/http/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/slickframework/http
[link-downloads]: https://packagist.org/packages/slickframework/http
[link-contributors]: https://github.com/slickframework/http/graphs/contributors

