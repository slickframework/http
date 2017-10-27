# Slick Configuration

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

`Slick/Configuration` is a simple package that deals with configuration files. It has a very simple
interface that you can use to set your own configuration drivers. By default it uses the PHP arrays
for configuration as it does not need any parser and therefore is more performance friendly.

This package is compliant with PSR-2 code standards and PSR-4 autoload standards. It
also applies the [semantic version 2.0.0](http://semver.org) specification.

## Install

Via Composer

``` bash
$ composer require slick/configuration
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

### HTTP Client

Create a simple request;
```php
use Slick\Http\Request;

$request = new Request(
    Request::GET,
    '/posts',
    ['Authorization' => 'Basic iuoqywer87324:owiuyqwe9r']
);
```

The above requests represents the following HTTP message:
```txt
GET /posts HTTP/1.0
Authorization: Basic iuoqywer87324:owiuyqwe9r
```
Now lets send this request to our API server:

```php
use Slick\Http\Client;

$client = new Client(['base_uri' => 'https://example.com']);
$response = $client->send($request);

$data = json_decode($response->getBody()->getContents());
```

### HTTP Server

The HTTP server is a middleware runner. You can pass a collection of middleware objects
to the server and they all will be called in their sequence in order to compose a
response for current server request.

Lets create a simple web application that will print out a greeting to a query parameter
named 'name'. Lets first create our middleware classes:

```php
use Slick\Http\Server\MiddlewareInterface;
use Slick\Http\Server\AbstractMiddleware;

class Greeting extends AbstractMiddleware implements MiddlewareInterface
{
  
    public function handle(
        ServerRequestInterface $request, ResponseInterface $response
    ) {
      $name = $request->getQuery('name', null);
      $request = $request->withAttribute('greeting', "Hello {$name}!");
      
      return $this->executeNext($request, $response);
    }
}
```
This middleware retrieves the query parameter with name and add an attribute to the
request object passed to the next middleware in the stack. Lets create our printer:

```php
use Slick\Http\Server\MiddlewateInterface;
use Slick\Http\Server\AbstractMiddlewate;

class Printer extends AbstractMiddlewate implements MiddlewateInterface
{
  
    public function handle(
        ServerRequestInterface $request, ResponseInterface $response
    ) {
      $greeting = $this->request->getAttribute('greeting', false);
      $text = $greeting ?: 'Hi!';
      $response->getBody()->write($text);
      
      return $this->executeNext($request, $response);
    }
}
```

Now lets create our main server application:

```php
use Slick\Http\Server;

$server = new Server();

$server
  ->add(new Greeting())
  ->add(new Printer()); // Order matters!
  
$response = $server->run();

$response->send(); // sends out the processed response.
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

##### Readwing session data
```php
$session->read('foo', null); // if foo is not found the default (null) will be returned.
```

##### Deleting session data
```php
$session->erase('foo');
```

You can create your own session drivers if you need to encrypt you data or change the save
handler to save in a database by simply implementing the `Slick\Http\SessionDriverInterface`.
It also comes with a `Slick\Http\Session\Driver\AbstractDriver` class that has all the basic
operations of the interface implemented.

Please check (documentation site)[http://configuration.slick-framework.com] for a complete reference. 

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

