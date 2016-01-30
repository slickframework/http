# Slick Http package

[![Latest Version](https://img.shields.io/github/release/slickframework/http.svg?style=flat-square)](https://github.com/slickframework/http/releases)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/slickframework/http/master.svg?style=flat-square)](https://travis-ci.org/slickframework/http)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/slickframework/http/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/slickframework/http/code-structure?branch=master)
[![Quality Score](https://img.shields.io/scrutinizer/g/slickframework/http/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/slickframework/http?branch=master)
[![Total Downloads](https://img.shields.io/packagist/dt/slick/http.svg?style=flat-square)](https://packagist.org/packages/slick/http)

`Slick/Http` is an useful library for Web  application foundation. It implements the PSR-7
message interface and has simple middleware server where you can build on top of. You Can
also add session handling to the mix and even create a session driver that fit your needs.
For API request it has a client witch is a wrapper to the extraordinary GuzzleHttp library.

This package is compliant with PSR-2 code standards and PSR-4 autoload standards. It
also applies the [semantic version 2.0.0](http://semver.org) specification.

## Install

Via Composer

``` bash
$ composer require slick/http
```

## Usage

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

## Testing

``` bash
$ vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email silvam.filipe@gmail.com instead of using the issue tracker.

## Credits

- [Slick framework](https://github.com/slickframework)
- [All Contributors](https://github.com/slickframework/common/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

