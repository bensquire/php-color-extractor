<?php

declare(strict_types=1);

$autoloadPath = __DIR__ . '/../vendor/autoload.php';

if (!file_exists($autoloadPath)) {
    exit("You must set up the project dependencies, run the following commands:\n> composer install\n");
}

require_once $autoloadPath;
