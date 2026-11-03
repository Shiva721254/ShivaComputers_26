<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Framework\Request;
use App\Framework\Response;
use App\Middleware\Interfaces\IMiddleware;

final class SecurityHeadersMiddleware implements IMiddleware
{
    private const HEADERS = [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Content-Security-Policy' => "default-src 'none'; style-src 'self'; img-src 'self'; base-uri 'none'; form-action 'none'; frame-ancestors 'none'",
    ];

    public function process(Request $request, callable $next): Response
    {
        return $next($request)->withHeaders(self::HEADERS);
    }
}
