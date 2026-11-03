<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Language;
use App\Enums\Page;
use App\Framework\Response;

final class ErrorController extends Controller
{
    public function notFound(Language $language): Response
    {
        return $this->render($language, Page::NotFound, status: 404);
    }
}
