<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Enums\Language;
use App\Models\Offering;

interface IOfferingRepository
{
    /** @return list<Offering> */
    public function getAll(Language $language): array;
}
