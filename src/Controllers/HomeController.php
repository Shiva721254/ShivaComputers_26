<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Language;
use App\Enums\Page;
use App\Framework\Response;
use App\Framework\Translator;
use App\Framework\View;
use App\Services\Interfaces\IOfferingService;
use App\Services\Interfaces\IProjectService;
use App\ViewModels\PageViewModelFactory;

final class HomeController extends Controller
{
    public function __construct(
        View $view,
        PageViewModelFactory $pages,
        Translator $translator,
        private readonly IOfferingService $offerings,
        private readonly IProjectService $projects,
    ) {
        parent::__construct($view, $pages, $translator);
    }

    // GET: /
    public function redirectToDefaultLanguage(): Response
    {
        return Response::redirect(Page::Home->path(Language::DEFAULT));
    }

    // GET: /{language}
    public function index(Language $language): Response
    {
        return $this->render($language, Page::Home, [
            'offerings' => $this->offerings->getAll($language),
            'projects' => $this->projects->getFeatured($language),
        ]);
    }
}
