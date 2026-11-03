<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\BusinessDetails;

interface IBusinessDetailsService
{
    public function get(): BusinessDetails;
}
