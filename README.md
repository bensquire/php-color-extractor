# PHP Color Extractor

[![Tests](https://github.com/bensquire/php-color-extractor/workflows/Tests/badge.svg)](https://github.com/bensquire/php-color-extractor/actions)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%20max-brightgreen.svg)](https://phpstan.org/)
[![PHP Version](https://img.shields.io/badge/php-%5E8.3-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

A modern PHP library to extract the dominant color palette from images.

## Features

- Extract dominant colors from any image format supported by GD
- Configurable number of colors to extract
- Adjustable granularity for performance vs. accuracy tradeoff
- Fluent interface for easy chaining
- Type-safe with strict PHP 8.3+ type declarations
- Full PHPStan level max compliance
- 100% test coverage

## Requirements

- PHP 8.3 or 8.4
- GD extension

## Installation

Install via Composer:

```bash
composer require bensquire/php-color-extractor
```

## Usage

### Basic Example

```php
<?php

use PHPColorExtractor\PHPColorExtractor;

$extractor = new PHPColorExtractor();
$extractor->setImage('/path/to/image.jpg');
$palette = $extractor->extractPalette();

// Returns array of hex colors, e.g., ['CC6666', 'FF9933', '66CC99', ...]
print_r($palette);
```

### Advanced Usage with Fluent Interface

```php
<?php

use PHPColorExtractor\PHPColorExtractor;

$extractor = new PHPColorExtractor();
$palette = $extractor
    ->setImage('/path/to/image.jpg')
    ->setTotalColors(5)        // Extract 5 colors (default: 10)
    ->setGranularity(10)        // Check every 10th pixel (default: 5)
    ->extractPalette();

foreach ($palette as $color) {
    echo "#{$color}\n";
}
```

### Configuration Options

#### `setImage(string $image): self`

Set the path to the image file to analyze.

**Throws:** `Exception` if the file doesn't exist.

#### `setTotalColors(int $totalColors = 10): self`

Set the number of dominant colors to extract.

**Default:** 10
**Throws:** `Exception` if the value is less than or equal to 0.

#### `setGranularity(int $granularity = 5): self`

Set the sampling granularity. Higher values = faster but less accurate. Lower values = slower but more accurate.

**Default:** 5
**Throws:** `Exception` if the value is less than or equal to 0.

#### `extractPalette(): array<int, string>`

Extract and return the color palette as an array of hex color strings.

**Returns:** Array of hex color strings (without # prefix)
**Throws:** `Exception` if no image has been set or if image processing fails.

## Examples

### Web Color Palette Display

```php
<?php

use PHPColorExtractor\PHPColorExtractor;

$extractor = new PHPColorExtractor();
$palette = $extractor
    ->setImage('uploaded-image.jpg')
    ->setTotalColors(8)
    ->extractPalette();

echo '<div class="palette">';
foreach ($palette as $color) {
    echo sprintf(
        '<div class="color" style="background-color: #%s;"></div>',
        $color
    );
}
echo '</div>';
```

### Fast Preview (Low Accuracy)

```php
<?php

use PHPColorExtractor\PHPColorExtractor;

// Quick extraction for thumbnails or previews
$palette = (new PHPColorExtractor())
    ->setImage('large-image.jpg')
    ->setTotalColors(3)
    ->setGranularity(20)  // Sample every 20th pixel for speed
    ->extractPalette();
```

### High Accuracy Analysis

```php
<?php

use PHPColorExtractor\PHPColorExtractor;

// Detailed extraction for color analysis
$palette = (new PHPColorExtractor())
    ->setImage('artwork.jpg')
    ->setTotalColors(15)
    ->setGranularity(1)   // Sample every pixel for maximum accuracy
    ->extractPalette();
```

## Development

### Running Tests

```bash
composer install
vendor/bin/phpunit
```

### Running Static Analysis

```bash
vendor/bin/phpstan analyse
```

### Running All Quality Checks

```bash
vendor/bin/phpstan analyse && vendor/bin/phpunit
```

## How It Works

The library samples pixels from the image at intervals determined by the granularity setting. It then:

1. Rounds RGB values to reduce similar colors
2. Counts occurrences of each color
3. Sorts by frequency
4. Returns the most common colors

This approach is based on [this Stack Overflow answer](http://stackoverflow.com/questions/3468500/detect-overall-average-color-of-the-picture?answertab=active#tab-top).

## Contributing

Contributions are welcome! Please ensure:

- All tests pass (`vendor/bin/phpunit`)
- PHPStan analysis passes at max level (`vendor/bin/phpstan analyse`)
- Code follows PSR-4 autoloading standards
- New features include tests

## License

This library is licensed under the MIT License. See [LICENSE](LICENSE) for details.

## Credits

- [Ben Squire](https://github.com/bensquire) - Original author
- Inspired by [Stack Overflow community](http://stackoverflow.com/questions/3468500/)

## Changelog

See [CHANGELOG.md](CHANGELOG.md) for version history and changes.
