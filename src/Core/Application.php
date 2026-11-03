<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\PageController;

final class Application
{
    public function __construct(
        private readonly Router $router,
        private readonly PageController $controller,
    ) {
    }

    public function run(string $method, string $uri): void
    {
        $this->sendSecurityHeaders();

        if (!in_array($method, ['GET', 'HEAD'], true)) {
            http_response_code(405);
            header('Allow: GET, HEAD');
            header('Content-Type: text/plain; charset=utf-8');
            echo 'Method Not Allowed';
            return;
        }

        $path = parse_url($uri, PHP_URL_PATH);
        $route = $this->router->match(is_string($path) ? $path : '/invalid');

        if ($route['type'] === 'redirect') {
            header('Location: ' . $route['location'], true, $route['status']);
            return;
        }

        header('Content-Type: text/html; charset=utf-8');

        if ($route['type'] === 'not_found') {
            http_response_code(404);
            echo $this->controller->notFound($route['language']);
            return;
        }

        echo $this->controller->show($route['language'], $route['page']);
    }

    private function sendSecurityHeaders(): void
    {
        header_remove('X-Powered-By');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'none'; style-src 'self'; img-src 'self'; base-uri 'none'; form-action 'none'; frame-ancestors 'none'");
    }
}
