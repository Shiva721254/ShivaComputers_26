<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\Language;
use App\Framework\FormToken;
use App\Models\ContactMessage;
use App\Models\ContactResult;
use App\Services\Interfaces\IContactFormService;
use App\Services\Interfaces\IMailer;
use App\Services\Interfaces\IRateLimiter;

/**
 * Business rules for the contact form. Spam is stopped by three invisible checks instead of a
 * CAPTCHA: a hidden trap field, a minimum fill time and a limit per visitor.
 */
final class ContactFormService implements IContactFormService
{
    public const HONEYPOT_FIELD = 'website';

    private const MAX_NAME = 100;
    private const MAX_EMAIL = 254;
    private const MAX_PHONE = 30;
    private const MIN_MESSAGE = 10;
    private const MAX_MESSAGE = 5000;

    public function __construct(
        private readonly FormToken $tokens,
        private readonly IRateLimiter $rateLimiter,
        private readonly IMailer $mailer,
    ) {
    }

    public function token(): string
    {
        return $this->tokens->issue();
    }

    public function submit(array $input, string $visitorIp, Language $language): ContactResult
    {
        $tokenStatus = $this->tokens->check($input['form_token'] ?? '');

        // Bots fill the hidden field or submit instantly. Pretend it worked, so they learn nothing.
        if (($input[self::HONEYPOT_FIELD] ?? '') !== '' || $tokenStatus === FormToken::TOO_FAST || $tokenStatus === FormToken::INVALID) {
            return new ContactResult(ContactResult::SENT);
        }
        if ($tokenStatus === FormToken::EXPIRED) {
            return new ContactResult(ContactResult::EXPIRED);
        }

        $errors = $this->validate($input);
        if ($errors !== []) {
            return new ContactResult(ContactResult::INVALID, $errors);
        }

        if (!$this->rateLimiter->attempt($visitorIp)) {
            return new ContactResult(ContactResult::TOO_MANY);
        }

        $message = new ContactMessage(
            $this->singleLine($input['name']),
            $input['email'],
            $this->singleLine($input['phone'] ?? ''),
            $input['message'],
            $language,
        );

        return new ContactResult($this->mailer->send($message) ? ContactResult::SENT : ContactResult::FAILED);
    }

    /**
     * @param array<string, string> $input
     * @return array<string, string> field => translation key
     */
    private function validate(array $input): array
    {
        $errors = [];
        $name = $input['name'] ?? '';
        $email = $input['email'] ?? '';
        $phone = $input['phone'] ?? '';
        $message = $input['message'] ?? '';

        if (mb_strlen($name) < 2 || mb_strlen($name) > self::MAX_NAME) {
            $errors['name'] = 'contact.error.name';
        }
        if (mb_strlen($email) > self::MAX_EMAIL || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            $errors['email'] = 'contact.error.email';
        }
        if ($phone !== '' && (mb_strlen($phone) > self::MAX_PHONE || preg_match('/^[0-9 +()\-]{6,}$/', $phone) !== 1)) {
            $errors['phone'] = 'contact.error.phone';
        }
        if (mb_strlen($message) < self::MIN_MESSAGE || mb_strlen($message) > self::MAX_MESSAGE) {
            $errors['message'] = 'contact.error.message';
        }

        return $errors;
    }

    /** Removes line breaks, so a value can never add extra email headers. */
    private function singleLine(string $value): string
    {
        return trim((string) preg_replace('/[\r\n\t]+/', ' ', $value));
    }
}
