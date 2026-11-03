<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Language;
use App\Repositories\Interfaces\IOfferingRepository;
use App\Services\Interfaces\IOfferingService;

final class OfferingService implements IOfferingService
{
    public function __construct(private readonly IOfferingRepository $offerings)
    {
    }

    public function getAll(Language $language): array
    {
        return $this->offerings->getAll($language);
    }
}
