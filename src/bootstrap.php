<?php

declare(strict_types=1);

use App\Controllers\PageController;
use App\Core\Application;
use App\Core\Router;
use App\Core\View;

$root = dirname(__DIR__);

spl_autoload_register(static function (string $class) use ($root): void {
    if (str_starts_with($class, 'App\\')) {
        $file = $root . '/src/' . str_replace('\\', '/', substr($class, 4)) . '.php';
        if (is_file($file)) {
            require $file;
        }
    }
});

require $root . '/src/helpers.php';

$config = require $root . '/config/app.php';

ini_set('display_errors', $config['env'] === 'development' ? '1' : '0');
ini_set('log_errors', '1');

set_exception_handler(static function (Throwable $exception): void {
    error_log((string) $exception);
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/plain; charset=utf-8');
    }
    echo 'Internal Server Error';
});

$view = new View($root . '/views', $config);
$projects = require $root . '/data/projects.php';

return new Application(new Router(), new PageController($view, $projects, $root . '/translations'));
