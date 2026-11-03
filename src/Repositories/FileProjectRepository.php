<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\Language;
use App\Models\Project;
use App\Repositories\Interfaces\IProjectRepository;

final class FileProjectRepository implements IProjectRepository
{
    public function __construct(private readonly string $file)
    {
    }

    public function getAll(Language $language): array
    {
        return array_map(
            static fn (array $row): Project => new Project(
                $row['name'],
                $row['repository'],
                $row['stack'],
                $row['featured'],
                $row['label'][$language->value],
                $row['description'][$language->value],
            ),
            require $this->file,
        );
    }
}
