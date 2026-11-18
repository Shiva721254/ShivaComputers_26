<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ContactMessage;
use App\Services\Interfaces\IMailer;

/**
 * Sends through PHP's mail() function, which STRATO hosting provides. If sending fails,
 * the message is kept in private storage so it is never lost.
 */
final class NativeMailer implements IMailer
{
    public function __construct(
        private readonly string $recipient,
        private readonly MailFormatter $formatter,
        private readonly FileMailer $fallback,
    ) {
    }

    public function send(ContactMessage $message): bool
    {
        $sent = mail(
            $this->recipient,
            $this->formatter->subject($message),
            $this->formatter->body($message),
            $this->formatter->headers($message),
            '-f' . $this->formatter->fromAddress(),
        );

        if (!$sent) {
            error_log('Contact form: mail() failed; message kept in storage/unsent.');
            $this->fallback->send($message);
        }

        return $sent;
    }
}
