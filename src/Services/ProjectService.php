<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Language;
use App\Models\Project;
use App\Repositories\Interfaces\IProjectRepository;
use App\Services\Interfaces\IProjectService;

final class ProjectService implements IProjectService
{
    public function __construct(private readonly IProjectRepository $projects)
    {
    }

    public function getAll(Language $language): array
    {
        return $this->projects->getAll($language);
    }

    public function getFeatured(Language $language): array
    {
        return array_values(array_filter(
            $this->projects->getAll($language),
            static fn (Project $project): bool => $project->featured,
        ));
    }
}
