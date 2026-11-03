<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Language;
use App\Enums\Page;
use App\Framework\Response;

final class AboutController extends Controller
{
    // GET: /{language}/about
    public function index(Language $language): Response
    {
        return $this->render($language, Page::About);
    }
}
