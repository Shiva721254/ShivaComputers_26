<?php

declare(strict_types=1);

namespace App\Middleware\Interfaces;

use App\Framework\Request;
use App\Framework\Response;

interface IMiddleware
{
    /** @param callable(Request): Response $next */
    public function process(Request $request, callable $next): Response;
}
