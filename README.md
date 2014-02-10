php-color-extractor
===============
[![Build Status](https://travis-ci.org/bensquire/php-color-extractor.png)](https://travis-ci.org/bensquire/php-color-extractor)

The purpose of this library is to extract the main colour(s) from images. Taken from the stackoverflow answer:

    http://stackoverflow.com/questions/3468500/detect-overall-average-color-of-the-picture?answertab=active#tab-top

Installation:
-------------
The library is PSR-0 compliant and the simplest way to install it is via composer, at the moment it isn't part of the
main composer package library so it can be included by putting:

    {
        "repositories": [
            {
                "type": "vcs",
                "url": "https://github.com/bensquire/php-color-extractor"
            }
        ],
        "require": {
            "bensquire/php-color-extractor": "master"
        }
    }

into your composer.json, then run 'composer install' or 'composer update' as required.


Example:
--------
This example demonstrates the optimisation of a PNG file, by chaining several commands together.

    <?php
        include('../vendor/autoload.php');  //As needed

        use PHPColorExtractor\PHPColorExtractor;

        $extractor = new PHPColorExtractor();
        $extractor->setImage('/path/to/img.jpg')->setTotalColors(5)->setGranularity(10);
        $palette = $extractor->extractPalette();