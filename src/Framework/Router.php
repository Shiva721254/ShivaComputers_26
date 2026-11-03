<?php

declare(strict_types=1);

namespace App\Framework;

use FastRoute\Dispatcher;

final class Router
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function dispatch(string $method, string $path): RouteResult
    {
        $result = $this->dispatcher->dispatch($method, $path);

        return match ($result[0]) {
            Dispatcher::FOUND => RouteResult::found($result[1], $result[2]),
            Dispatcher::METHOD_NOT_ALLOWED => RouteResult::methodNotAllowed($result[1]),
            default => $this->redirectTrailingSlash($method, $path) ?? RouteResult::notFound(),
        };
    }

    /**
     * Redirects /nl/about/ to /nl/about. It only redirects to a route that exists,
     * so a crafted URL cannot send a visitor to another website.
     */
    private function redirectTrailingSlash(string $method, string $path): ?RouteResult
    {
        if ($path === '/' || !str_ends_with($path, '/')) {
            return null;
        }

        $cleanPath = substr($path, 0, -1);
        $result = $this->dispatcher->dispatch($method, $cleanPath);

        return $result[0] === Dispatcher::FOUND ? RouteResult::redirect($cleanPath) : null;
    }
}
