# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.1.0] - 2026-11-05

### Added
- `setImageFromString(string $data)` to extract a palette from raw image data (uploads, storage responses, images fetched over HTTP by the caller) without writing a temporary file
- `setImageFromGd(GdImage $image)` to extract a palette from an existing GD image

### Changed
- Renamed the protected `$image` property to `$imagePath`, since it holds a file path. This only affects classes that extend `PHPColorExtractor` and read the property directly.
- Images set with `setImage()` are now read from disk once per `extractPalette()` call instead of twice

## [1.0.1] - 2026-11-05

Development tooling only; no changes to the library code or its runtime requirements.

### Changed
- Upgraded PHPUnit from 11.5 to 12.5 (PHPUnit 13 requires PHP 8.4, above this library's 8.3 minimum)
- Refreshed lockfile for PHPStan and PHP CS Fixer
- Updated `phpunit.xml` schema to 12.5
- Bumped GitHub Actions: `actions/checkout` v4 → v7, `actions/cache` v4 → v6

## [1.0.0] - 2026-11-05

First stable release.

### Security
- Updated development dependencies to resolve Dependabot alerts:
  - `phpunit/phpunit` 11.5.43 → 11.5.57 (unsafe deserialization in PHPT code coverage handling)
  - `symfony/process` 7.3.4 → 7.4.22 (argument escaping under MSYS2/Git Bash on Windows)
  - These were dev-only dependencies and never shipped to consumers of the package.

### Added
- Dependabot configuration for Composer and GitHub Actions updates
- `.gitattributes` so tests and tooling config are excluded from the Composer dist archive
- Release workflow that creates a GitHub release when a version tag is pushed
- CI coverage for PHP 8.4 and 8.5

### Removed
- Leftover `.travis.yml`
- Claude Code GitHub workflows (automated PR review and `@claude` mentions)

## [0.0.1] - 2026-02-28

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

## Pre-release (2014)

### Added
- Initial version
- Basic color extraction from images
- Configurable color count
- Configurable granularity
- PSR-0 autoloading
- Basic test coverage

[1.1.0]: https://github.com/bensquire/php-color-extractor/compare/1.0.1...1.1.0
[1.0.1]: https://github.com/bensquire/php-color-extractor/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/bensquire/php-color-extractor/compare/0.0.1...1.0.0
[0.0.1]: https://github.com/bensquire/php-color-extractor/releases/tag/0.0.1
