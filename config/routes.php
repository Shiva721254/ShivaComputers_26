<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\PortfolioController;
use App\Controllers\PrivacyController;
use App\Controllers\RemoteSupportController;
use App\Controllers\SeoController;
use App\Controllers\ServiceController;
use FastRoute\RouteCollector;

return static function (RouteCollector $routes): void {
    $routes->addRoute('GET', '/', [HomeController::class, 'redirectToDefaultLanguage']);

    // Files for search engines.
    $routes->addRoute('GET', '/robots.txt', [SeoController::class, 'robots']);
    $routes->addRoute('GET', '/sitemap.xml', [SeoController::class, 'sitemap']);

    // Short links that are easy to say on the phone: shivacomputers.nl/hulp and /help.
    $routes->addRoute('GET', '/hulp', [RemoteSupportController::class, 'startDutch']);
    $routes->addRoute('GET', '/help', [RemoteSupportController::class, 'startEnglish']);

    // {language} is limited to supported values, so anything else becomes a 404.
    $routes->addGroup('/{language:nl|en}', static function (RouteCollector $routes): void {
        $routes->addRoute('GET', '', [HomeController::class, 'index']);
        $routes->addRoute('GET', '/services', [ServiceController::class, 'index']);
        $routes->addRoute('GET', '/portfolio', [PortfolioController::class, 'index']);
        $routes->addRoute('GET', '/about', [AboutController::class, 'index']);
        $routes->addRoute('GET', '/contact', [ContactController::class, 'index']);
        $routes->addRoute('POST', '/contact', [ContactController::class, 'submit']);
        $routes->addRoute('GET', '/privacy', [PrivacyController::class, 'index']);
        $routes->addRoute('GET', '/remote-support', [RemoteSupportController::class, 'index']);
    });
};
