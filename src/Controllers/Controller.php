<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Language;
use App\Enums\Page;
use App\Framework\PageTranslator;
use App\Framework\Response;
use App\Framework\Translator;
use App\Framework\View;
use App\ViewModels\PageViewModelFactory;

abstract class Controller
{
    public function __construct(
        private readonly View $view,
        private readonly PageViewModelFactory $pages,
        private readonly Translator $translator,
    ) {
    }

    /** @param array<string, mixed> $data */
    protected function render(Language $language, Page $page, array $data = [], int $status = 200): Response
    {
        $html = $this->view->render($page->template(), $data + [
            'page' => $this->pages->create($language, $page),
            't' => new PageTranslator($this->translator, $language),
        ]);

        return Response::html($html, $status);
    }
}
