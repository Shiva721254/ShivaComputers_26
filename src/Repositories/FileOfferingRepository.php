<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\Language;
use App\Models\Offering;
use App\Repositories\Interfaces\IOfferingRepository;

final class FileOfferingRepository implements IOfferingRepository
{
    public function __construct(private readonly string $file)
    {
    }

    public function getAll(Language $language): array
    {
        return array_map(
            static fn (array $row): Offering => new Offering(
                $row['title'][$language->value],
                $row['description'][$language->value],
            ),
            require $this->file,
        );
    }
}
