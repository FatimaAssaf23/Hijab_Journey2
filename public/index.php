<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Increase memory limit and execution time for file uploads
// Note: upload_max_filesize and post_max_size must be set in php.ini
// They cannot be changed at runtime with ini_set()
if (function_exists('ini_set')) {
    @ini_set('memory_limit', '512M');
    @ini_set('max_execution_time', '300');
}

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
