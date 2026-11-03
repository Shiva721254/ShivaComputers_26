<?php

declare(strict_types=1);

// The PHP built-in server sends every request here, so let it serve real files itself.
if (PHP_SAPI === 'cli-server') {
    $requestedPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    if (is_string($requestedPath) && $requestedPath !== '/' && is_file(__DIR__ . $requestedPath)) {
        return false;
    }
}

$application = require dirname(__DIR__) . '/src/bootstrap.php';
$application->run($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
