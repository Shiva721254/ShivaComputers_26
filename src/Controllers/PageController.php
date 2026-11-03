<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Translator;
use App\Core\View;

final class PageController
{
    /** @param array<int, array<string, mixed>> $projects */
    public function __construct(
        private readonly View $view,
        private readonly array $projects,
        private readonly string $translationDirectory,
    ) {
    }

    public function show(string $language, string $page): string
    {
        return $this->view->render($page, [
            't' => new Translator($this->translationDirectory, $language),
            'language' => $language,
            'page' => $page,
            'indexable' => true,
            'projects' => $this->projects,
        ]);
    }

    public function notFound(string $language): string
    {
        return $this->view->render('not-found', [
            't' => new Translator($this->translationDirectory, $language),
            'language' => $language,
            'page' => 'not-found',
            'indexable' => false,
            'projects' => [],
        ]);
    }
}
