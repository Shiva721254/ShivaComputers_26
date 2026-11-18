<?php

declare(strict_types=1);

namespace App\Services\Interfaces;

use App\Models\ContactMessage;

interface IMailer
{
    /** Returns false when the message could not be handed over for delivery. */
    public function send(ContactMessage $message): bool;
}
