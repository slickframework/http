# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

[Unreleased]

## [v3.0.2] - 2020-11-18
### Adds
- `provides` entry on composer file allowing other packages to be
  aware of `psr/http-message-implementation`:1.0 implementation."

## [v3.0.0] - 2020-04-28
### Removed
- ``Slick\Http\HttpClientInterface`` interface  and ``CurlHttpClient::send()``method
- ``ClientErrorException``, ``HttpResponseException`` and ``ServerErrorException``
-  ``react/promise`` as a dependency

## [v2.1.0] - 2020-04-28
### Added
- [PSR-18 HTTP Client](https://www.php-fig.org/psr/psr-18/) support
### Changed
- ``Slick\Http\Client\CurlHttpClient`` to implement ``Psr\Http\Client\ClientInterface``
### Deprecates
- ``Slick\Http\HttpClientInterface`` interface  and ``CurlHttpClient::send()``method

## [v2.0.1] - 2020-03-07
### Fixes
- Move uploaded file always throws Upload failure exception

## [v2.0.0] - 2019-03-19
## Added
- ``Slick\Http\Server\MiddlewareStack`` based on the [PSR-15](https://www.php-fig.org/psr/psr-15/)
- ``Slick\Http\HttpClientInterface`` and an implementation using the PHP's ``cURL`` extension
- ``Slick\Http\Message\Server\Request`` wraps all environment information regarding incoming request
- ``Slick\Http\Message\Server\BodyParserInterface`` with allows you to define how ``Request::getParsedBody()``
  returned data will be parsed
- JSON, XML and simple text body parsers
- Use PHPSpec for unit tests 
- Code of conduct
- Issue template
- Pull request template
- Documentation on [Read the docs](https://readthedocs.org/) at [Slick documentation](http://www.slick-framework.com)

## Changed
- Uploaded files respects the tree dept that was submitted
- HTTP Client now returns a Promise (see [React/Promise](https://github.com/reactphp/promise))

## Removed
- ``Slick\Http\Stream`` Use one of ``Slick\Http\Message\Stream`` implementations
- ``Slick\Http\PhpEnvironment`` the new ``Slick\Http\Message\Server`` is a more descriptive
  path and is strict with the PSR-7 interface
- ``Slick\Common`` dependency
- Test suit with PHPUnit
- HTTP Client ``guzzlehttp/guzzle`` dependency
- Support for PHP5.6 and 7.0

## [v1.2.3] - 2016-06-23
### Added
- Middleware server has a request object setter. This enables the possibility
  to run the same middleware over different requests.

## [v1.2.2] - 2016-02-21
### Fixed
- Fixed the method acknowledge for magic methods is<Method>()

## [v1.2.1] - 2016-02-21
### Fixed
- Form submissions with multipart/form-data now uses the url encoded body parser.

## [v1.2.0] - 2016-01-30 
### Added
- Initial release

[Unreleased]: https://github.com/slickframework/http/compare/v3.0.2...HEAD
[v3.0.2]: https://github.com/slickframework/http/compare/v3.0.0...v3.0.2
[v3.0.0]: https://github.com/slickframework/http/compare/v2.1.0...v3.0.0
[v2.1.0]: https://github.com/slickframework/http/compare/v2.0.1...v2.1.0
[v2.0.1]: https://github.com/slickframework/http/compare/v2.0.0...v2.0.1
[v2.0.0]: https://github.com/slickframework/http/compare/v1.2.3...v2.0.0
[v1.2.3]: https://github.com/slickframework/http/compare/v1.2.2...v1.2.3
[v1.2.2]: https://github.com/slickframework/http/compare/v1.2.1...v1.2.2
[v1.2.1]: https://github.com/slickframework/http/compare/v1.2.0...v1.2.1
[v1.2.0]: https://github.com/slickframework/http/compare/479ea2e...v1.2.0
