<?php

declare(strict_types=1);

use App\Controllers\AboutController;
use App\Controllers\ContactController;
use App\Controllers\ErrorController;
use App\Controllers\HomeController;
use App\Controllers\PortfolioController;
use App\Controllers\PrivacyController;
use App\Controllers\ServiceController;
use App\Framework\Container;
use App\Framework\Translator;
use App\Framework\View;
use App\Repositories\ConfigBusinessDetailsRepository;
use App\Repositories\FileOfferingRepository;
use App\Repositories\FileProjectRepository;
use App\Repositories\Interfaces\IBusinessDetailsRepository;
use App\Repositories\Interfaces\IOfferingRepository;
use App\Repositories\Interfaces\IProjectRepository;
use App\Services\BusinessDetailsService;
use App\Services\Interfaces\IBusinessDetailsService;
use App\Services\Interfaces\IOfferingService;
use App\Services\Interfaces\IProjectService;
use App\Services\OfferingService;
use App\Services\ProjectService;
use App\ViewModels\PageViewModelFactory;

/**
 * The one place that decides which class implements each interface.
 * Moving projects to MySQL later means changing one line here.
 */
return static function (Container $c, array $config, string $root): void {
    // Repositories
    $c->set(IProjectRepository::class, fn () => new FileProjectRepository($root . '/data/projects.php'));
    $c->set(IOfferingRepository::class, fn () => new FileOfferingRepository($root . '/data/offerings.php'));
    $c->set(IBusinessDetailsRepository::class, fn () => new ConfigBusinessDetailsRepository($config['name'], $config['contact']));

    // Services
    $c->set(IProjectService::class, fn (Container $c) => new ProjectService($c->get(IProjectRepository::class)));
    $c->set(IOfferingService::class, fn (Container $c) => new OfferingService($c->get(IOfferingRepository::class)));
    $c->set(IBusinessDetailsService::class, fn (Container $c) => new BusinessDetailsService($c->get(IBusinessDetailsRepository::class)));

    // Presentation
    $c->set(Translator::class, fn () => new Translator($root . '/translations'));
    $c->set(View::class, fn () => new View($root . '/src/Views'));
    $c->set(PageViewModelFactory::class, fn (Container $c) => new PageViewModelFactory(
        $c->get(Translator::class),
        $c->get(IBusinessDetailsService::class),
        $config['base_url'],
        $config['content_approved'],
        $config['env'] === 'development',
    ));

    // Controllers
    $presentation = fn (Container $c): array => [$c->get(View::class), $c->get(PageViewModelFactory::class), $c->get(Translator::class)];

    $c->set(HomeController::class, fn (Container $c) => new HomeController(...[...$presentation($c), $c->get(IOfferingService::class), $c->get(IProjectService::class)]));
    $c->set(ServiceController::class, fn (Container $c) => new ServiceController(...[...$presentation($c), $c->get(IOfferingService::class)]));
    $c->set(PortfolioController::class, fn (Container $c) => new PortfolioController(...[...$presentation($c), $c->get(IProjectService::class)]));
    $c->set(AboutController::class, fn (Container $c) => new AboutController(...$presentation($c)));
    $c->set(ContactController::class, fn (Container $c) => new ContactController(...$presentation($c)));
    $c->set(PrivacyController::class, fn (Container $c) => new PrivacyController(...$presentation($c)));
    $c->set(ErrorController::class, fn (Container $c) => new ErrorController(...$presentation($c)));
};
