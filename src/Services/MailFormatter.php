<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\ContactMessage;

/** Builds the subject, body and headers of the email Shiva receives. */
final class MailFormatter
{
    public function __construct(
        private readonly string $fromAddress,
        private readonly string $fromName,
    ) {
    }

    public function subject(ContactMessage $message): string
    {
        return $this->encode('Contact form: ' . $message->name);
    }

    public function body(ContactMessage $message): string
    {
        $lines = [
            'New message from the contact form on shivacomputers.nl',
            '',
            'Name:     ' . $message->name,
            'Email:    ' . $message->email,
            'Phone:    ' . ($message->phone !== '' ? $message->phone : '-'),
            'Language: ' . $message->language->value,
            'Sent at:  ' . date('Y-m-d H:i'),
            '',
            'Message:',
            $message->message,
            '',
            '--',
            'Reply to this email to answer ' . $message->name . ' directly.',
        ];

        return str_replace("\n", "\r\n", str_replace(["\r\n", "\r"], "\n", implode("\n", $lines)));
    }

    /** @return array<string, string> */
    public function headers(ContactMessage $message): array
    {
        return [
            'From' => $this->encode($this->fromName) . ' <' . $this->fromAddress . '>',
            'Reply-To' => $this->encode($message->name) . ' <' . $message->email . '>',
            'MIME-Version' => '1.0',
            'Content-Type' => 'text/plain; charset=UTF-8',
            'Content-Transfer-Encoding' => '8bit',
            'X-Mailer' => 'shivacomputers.nl',
        ];
    }

    public function fromAddress(): string
    {
        return $this->fromAddress;
    }

    private function encode(string $text): string
    {
        return mb_encode_mimeheader($text, 'UTF-8', 'Q');
    }
}
