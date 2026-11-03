<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\BusinessDetails;
use App\Repositories\Interfaces\IBusinessDetailsRepository;
use App\Services\Interfaces\IBusinessDetailsService;

final class BusinessDetailsService implements IBusinessDetailsService
{
    public function __construct(private readonly IBusinessDetailsRepository $details)
    {
    }

    public function get(): BusinessDetails
    {
        return $this->details->get();
    }
}
