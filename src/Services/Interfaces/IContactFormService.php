<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Enums\Language;
use App\Models\ContactResult;

interface IContactFormService
{
    /** A fresh token for the hidden form field. */
    public function token(): string;

    /** @param array<string, string> $input the submitted, trimmed fields */
    public function submit(array $input, string $visitorIp, Language $language): ContactResult;
}
