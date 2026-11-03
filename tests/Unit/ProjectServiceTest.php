<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Enums\Language;
use App\Models\Project;
use App\Repositories\Interfaces\IProjectRepository;
use App\Services\ProjectService;
use PHPUnit\Framework\TestCase;

final class ProjectServiceTest extends TestCase
{
    public function testGetFeaturedReturnsOnlyFeaturedProjects(): void
    {
        // The interface lets this test use fake data instead of the real file.
        $repository = new class implements IProjectRepository {
            public function getAll(Language $language): array
            {
                return [
                    new Project('Featured', 'https://example.com/a', ['PHP'], true, 'Label', 'Text'),
                    new Project('Hidden', 'https://example.com/b', ['PHP'], false, 'Label', 'Text'),
                ];
            }
        };

        $projects = (new ProjectService($repository))->getFeatured(Language::English);

        self::assertCount(1, $projects);
        self::assertSame('Featured', $projects[0]->name);
    }
}
