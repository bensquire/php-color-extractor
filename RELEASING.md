# Release Process

This document describes how to create a new release of `bensquire/php-color-extractor`.

## Versioning

This project follows [Semantic Versioning](https://semver.org/spec/v2.0.0.html):

- **MAJOR** version for incompatible API changes
- **MINOR** version for backwards-compatible new functionality
- **PATCH** version for backwards-compatible bug fixes

## Steps to Create a Release

### 1. Update CHANGELOG.md

Move items from `[Unreleased]` to a new versioned section with today's date:

```markdown
## [Unreleased]

## [X.Y.Z] - YYYY-MM-DD

### Added
- ...

### Changed
- ...

### Fixed
- ...
```

Update the comparison links at the bottom of `CHANGELOG.md`:

```markdown
[Unreleased]: https://github.com/bensquire/php-color-extractor/compare/vX.Y.Z...HEAD
[X.Y.Z]: https://github.com/bensquire/php-color-extractor/releases/tag/vX.Y.Z
[PREV]: https://github.com/bensquire/php-color-extractor/compare/vPREV...vX.Y.Z
```

### 2. Commit the Changes

```bash
git add CHANGELOG.md
git commit -m "chore: prepare release vX.Y.Z"
git push origin master
```

### 3. Create and Push the Tag

```bash
git tag -a vX.Y.Z -m "Release vX.Y.Z"
git push origin vX.Y.Z
```

### 4. Create a GitHub Release

Either:
- Go to [GitHub Releases](https://github.com/bensquire/php-color-extractor/releases/new), select the tag, paste the CHANGELOG section as the body, and publish.
- Or use the GitHub CLI: `gh release create vX.Y.Z --title "vX.Y.Z" --notes "$(cat release-notes.md)"`

## Automating Releases with GitHub Actions

Add the following workflow to `.github/workflows/release.yml` to automatically create GitHub Releases when a version tag is pushed:

```yaml
name: Release

on:
  push:
    tags:
      - 'v*.*.*'

permissions:
  contents: write

jobs:
  release:
    name: Create GitHub Release
    runs-on: ubuntu-latest

    steps:
      - name: Checkout code
        uses: actions/checkout@v4

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.3'
          extensions: gd

      - name: Install dependencies
        run: composer install --prefer-dist --no-progress --no-dev

      - name: Extract version from tag
        id: version
        run: echo "version=${GITHUB_REF#refs/tags/}" >> $GITHUB_OUTPUT

      - name: Extract release notes from CHANGELOG
        id: changelog
        run: |
          VERSION="${{ steps.version.outputs.version }}"
          # Strip 'v' prefix for CHANGELOG lookup
          VERSION_NUM="${VERSION#v}"
          NOTES=$(awk "/^## \[$VERSION_NUM\]/{flag=1; next} /^## \[/{flag=0} flag" CHANGELOG.md | sed '/^$/d' | head -100)
          echo "notes<<EOF" >> $GITHUB_OUTPUT
          echo "$NOTES" >> $GITHUB_OUTPUT
          echo "EOF" >> $GITHUB_OUTPUT

      - name: Create GitHub Release
        uses: softprops/action-gh-release@v2
        with:
          tag_name: ${{ steps.version.outputs.version }}
          name: Release ${{ steps.version.outputs.version }}
          body: ${{ steps.changelog.outputs.notes }}
          draft: false
          prerelease: ${{ contains(steps.version.outputs.version, '-') }}
```

## Packagist

This package is listed on [Packagist](https://packagist.org/packages/bensquire/php-color-extractor). Packagist automatically picks up new tags pushed to GitHub, making the new version available to Composer users within minutes.

Users can install a specific version with:

```bash
composer require bensquire/php-color-extractor:^0.0.1
```

Or the latest development version from master:

```bash
composer require bensquire/php-color-extractor:dev-master
```

## Current Release History

| Version | Date       | Notes               |
|---------|------------|---------------------|
| 0.0.1   | 2026-02-28 | Initial tagged release |
