<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Language;
use App\Enums\Page;
use App\Framework\Response;
use App\Framework\Translator;
use App\Framework\View;
use App\Services\Interfaces\IOfferingService;
use App\ViewModels\PageViewModelFactory;

final class ServiceController extends Controller
{
    public function __construct(
        View $view,
        PageViewModelFactory $pages,
        Translator $translator,
        private readonly IOfferingService $offerings,
    ) {
        parent::__construct($view, $pages, $translator);
    }

    // GET: /{language}/services
    public function index(Language $language): Response
    {
        return $this->render($language, Page::Services, [
            'offerings' => $this->offerings->getAll($language),
        ]);
    }
}
