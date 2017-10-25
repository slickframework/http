# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

[Unreleased]
## Added
- Use PHPSpec for unit tests 
- Code of conduct
- Issue template
- Pull request template
- Documentation on [Read the docs](https://readthedocs.org/) at [HTTP documentation](http://http.slick-framework.com)

## Removed
- ``Slick/Common`` dependency
- Test suit with PHPUnit

## [1.2.3] - 2016-06-23
### Added
- Middleware server has a request object setter. This enables the possibility
  to run the same middleware over different requests.

## [1.2.2] - 2016-02-21
### Fixed
- Fixed the method acknowledge for magic methods is<Method>()

## [1.2.1] - 2016-02-21
### Fixed
- Form submissions with multipart/form-data now uses the url encoded body parser.

## [1.2.0] - 2016-01-30 
### Added
- Initial release

[Unreleased]: https://github.com/slickframework/configuration/compare/v1.0.0...HEAD
[v1.2.3]: https://github.com/slickframework/configuration/compare/v1.2.3...master
[v1.2.2]: https://github.com/slickframework/configuration/compare/v1.2.2...master
[v1.2.1]: https://github.com/slickframework/configuration/compare/v1.2.1...master
[v1.2.0]: https://github.com/slickframework/configuration/compare/v1.2.0...master