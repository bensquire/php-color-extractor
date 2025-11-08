# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- PHPStan static analysis with maximum strictness level
- PHPStan configuration file (phpstan.neon)
- PHPStan checks in GitHub Actions workflow
- Comprehensive README documentation with examples
- CHANGELOG.md for tracking version history
- Type safety with strict type declarations (`declare(strict_types=1)`)
- Full type hints for all method parameters and return types
- Nullable types where appropriate (`?string`)
- GitHub Actions CI/CD workflow replacing Travis CI
- Modern PHPUnit 11 test suite
- Additional test cases for type errors and edge cases
- Fluent interface test coverage

### Changed
- **BREAKING**: Minimum PHP version raised from 5.6 to 8.3
- **BREAKING**: Migrated from PSR-0 to PSR-4 autoloading
- **BREAKING**: Type hints now enforced - incorrect types will throw `TypeError`
- Updated PHPUnit from 5.x to 11.x
- Replaced `ext-hash` with `ext-gd` (correct dependency)
- Modernized test syntax (replaced `@expectedException` with `expectException()`)
- Updated phpunit.xml to PHPUnit 11 schema
- Improved error handling for `imagecolorat()` return values
- Enhanced PHPDoc comments with modern type annotations
- Updated .gitignore for modern PHP development
- Replaced Travis CI with GitHub Actions

### Fixed
- Potential type error when `imagecolorat()` returns false
- Error message typo in `setGranularity()` method
- Removed redundant type assertions in tests

### Removed
- PHP 5.6, 7.x, 8.0, 8.1, 8.2 support
- Travis CI configuration
- Outdated composer installation instructions
- PSR-0 autoloading support

## [1.0.0] - Historical

### Added
- Initial release
- Basic color extraction from images
- Configurable color count
- Configurable granularity
- PSR-0 autoloading
- Basic test coverage

[Unreleased]: https://github.com/bensquire/php-color-extractor/compare/master...HEAD
