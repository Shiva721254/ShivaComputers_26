<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Models\BusinessDetails;

interface IBusinessDetailsRepository
{
    public function get(): BusinessDetails;
}
