<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Periksa mode pemeliharaan sebelum Laravel menangani request.
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Muat autoloader Composer untuk menyediakan class aplikasi dan dependency.
require __DIR__.'/../vendor/autoload.php';

// Siapkan aplikasi Laravel lalu proses request HTTP yang masuk.
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());
