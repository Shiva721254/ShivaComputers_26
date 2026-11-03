<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Language;
use App\Enums\Page;
use App\Framework\Response;
use App\Framework\Translator;
use App\Framework\View;
use App\Services\Interfaces\IProjectService;
use App\ViewModels\PageViewModelFactory;

final class PortfolioController extends Controller
{
    public function __construct(
        View $view,
        PageViewModelFactory $pages,
        Translator $translator,
        private readonly IProjectService $projects,
    ) {
        parent::__construct($view, $pages, $translator);
    }

    // GET: /{language}/portfolio
    public function index(Language $language): Response
    {
        return $this->render($language, Page::Portfolio, [
            'projects' => $this->projects->getAll($language),
        ]);
    }
}
