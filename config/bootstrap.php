<?php

declare(strict_types=1);

use App\Framework\Application;
use App\Framework\Container;
use App\Framework\Router;
use App\Middleware\SecurityHeadersMiddleware;
use function FastRoute\simpleDispatcher;

$config = require __DIR__ . '/app.php';

ini_set('display_errors', $config['env'] === 'development' ? '1' : '0');
ini_set('log_errors', '1');

$container = new Container();
(require __DIR__ . '/services.php')($container, $config, dirname(__DIR__));

return new Application(
    new Router(simpleDispatcher(require __DIR__ . '/routes.php')),
    $container,
    [new SecurityHeadersMiddleware()],
);
