<?php

declare(strict_types=1);

use App\Framework\Request;

// The PHP built-in server sends every request here, so let it serve real files itself.
if (PHP_SAPI === 'cli-server') {
    $requestedPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if (is_string($requestedPath) && $requestedPath !== '/' && is_file(__DIR__ . $requestedPath)) {
        return false;
    }
}

require dirname(__DIR__) . '/vendor/autoload.php';

$application = require dirname(__DIR__) . '/config/bootstrap.php';
$application->handle(Request::fromGlobals())->send();
