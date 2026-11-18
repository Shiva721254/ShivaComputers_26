<?php

declare(strict_types=1);

namespace App\Framework;

use App\Controllers\ErrorController;
use App\Enums\Language;
use App\Middleware\Interfaces\IMiddleware;
use Throwable;

final class Application
{
    /** @param list<IMiddleware> $middleware */
    public function __construct(
        private readonly Router $router,
        private readonly Container $container,
        private readonly array $middleware = [],
    ) {
    }

    /** Sends the request through each middleware, and finally to a controller. */
    public function handle(Request $request): Response
    {
        $next = fn (Request $request): Response => $this->dispatch($request);

        foreach (array_reverse($this->middleware) as $middleware) {
            $next = static fn (Request $request): Response => $middleware->process($request, $next);
        }

        return $next($request);
    }

    private function dispatch(Request $request): Response
    {
        try {
            $route = $this->router->dispatch($request->method, $request->path);

            return match ($route->type) {
                RouteResult::FOUND => $this->callController($route, $request),
                RouteResult::REDIRECT => Response::redirect($route->location, 301),
                RouteResult::METHOD_NOT_ALLOWED => Response::text('Method Not Allowed', 405, [
                    'Allow' => implode(', ', [...$route->allowedMethods, 'HEAD']),
                ]),
                default => $this->container->get(ErrorController::class)->notFound(Language::fromPath($request->path)),
            };
        } catch (Throwable $exception) {
            error_log((string) $exception);

            return Response::text('Internal Server Error', 500);
        }
    }

    private function callController(RouteResult $route, Request $request): Response
    {
        [$class, $method] = $route->handler;
        $arguments = $route->parameters;

        if (isset($arguments['language'])) {
            $arguments['language'] = Language::from($arguments['language']);
        }

        // Controller methods that declare a $request parameter receive the current request.
        foreach ((new \ReflectionMethod($class, $method))->getParameters() as $parameter) {
            if ($parameter->getName() === 'request') {
                $arguments['request'] = $request;
            }
        }

        return $this->container->get($class)->$method(...$arguments);
    }
}
