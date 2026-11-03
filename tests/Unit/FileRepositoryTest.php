<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Enums\Language;
use App\Repositories\FileOfferingRepository;
use App\Repositories\FileProjectRepository;
use PHPUnit\Framework\TestCase;

final class FileRepositoryTest extends TestCase
{
    private const DATA = __DIR__ . '/../../data';

    public function testProjectsAreReturnedInTheRequestedLanguage(): void
    {
        $repository = new FileProjectRepository(self::DATA . '/projects.php');

        self::assertSame('Studieproject, team van 4', $repository->getAll(Language::Dutch)[0]->label);
        self::assertSame('Study project, team of 4', $repository->getAll(Language::English)[0]->label);
    }

    public function testOfferingsAreReturnedInTheRequestedLanguage(): void
    {
        $repository = new FileOfferingRepository(self::DATA . '/offerings.php');

        self::assertSame('Softwareontwikkeling', $repository->getAll(Language::Dutch)[0]->title);
        self::assertSame('Software development', $repository->getAll(Language::English)[0]->title);
    }
}
