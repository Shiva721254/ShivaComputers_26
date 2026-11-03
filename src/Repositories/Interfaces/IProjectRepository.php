<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Enums\Language;
use App\Models\Project;

interface IProjectRepository
{
    /** @return list<Project> */
    public function getAll(Language $language): array;
}
