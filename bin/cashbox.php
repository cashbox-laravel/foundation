#!/usr/bin/env php
<?php

declare(strict_types=1);

use App\Commands\Composer;
use App\Commands\License;
use App\Commands\Readme;
use Symfony\Component\Console\Application;

error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);

$possibleFiles = [
    __DIR__ . '/../../../autoload.php',
    __DIR__ . '/../../autoload.php',
    __DIR__ . '/../vendor/autoload.php',
];

$file = null;

foreach ($possibleFiles as $possible) {
    if (file_exists($possible)) {
        $file = $possible;
        break;
    }
}

if (is_null($file)) {
    throw new RuntimeException('Unable to locate autoload.php file.');
}

require_once $file;

$application = new Application('Laravel Lang: Status Generator');

$application->add(new Composer());
$application->add(new License());
$application->add(new Readme());

$application->run();
