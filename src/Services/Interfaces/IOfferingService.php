<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Enums\Language;
use App\Models\Offering;

interface IOfferingService
{
    /** @return list<Offering> */
    public function getAll(Language $language): array;
}
