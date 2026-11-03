<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Enums\Language;
use App\Models\Project;

interface IProjectService
{
    /** @return list<Project> */
    public function getAll(Language $language): array;

    /** @return list<Project> */
    public function getFeatured(Language $language): array;
}
