<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Enums\Language;
use App\Framework\FormToken;
use App\Models\ContactMessage;
use App\Models\ContactResult;
use App\Services\ContactFormService;
use App\Services\Interfaces\IMailer;
use App\Services\Interfaces\IRateLimiter;
use PHPUnit\Framework\TestCase;

final class ContactFormServiceTest extends TestCase
{
    private FormToken $tokens;
    private object $mailer;
    private object $limiter;

    protected function setUp(): void
    {
        $this->tokens = new FormToken('test-secret');

        // Fake mailer that remembers what it was asked to send.
        $this->mailer = new class implements IMailer {
            /** @var list<ContactMessage> */
            public array $sent = [];
            public bool $works = true;

            public function send(ContactMessage $message): bool
            {
                $this->sent[] = $message;

                return $this->works;
            }
        };

        $this->limiter = new class implements IRateLimiter {
            public bool $allow = true;

            public function attempt(string $visitor): bool
            {
                return $this->allow;
            }
        };
    }

    private function service(): ContactFormService
    {
        return new ContactFormService($this->tokens, $this->limiter, $this->mailer);
    }

    /** @return array<string, string> */
    private function validInput(array $overrides = []): array
    {
        return $overrides + [
            'name' => 'Jan de Vries',
            'email' => 'jan@example.com',
            'phone' => '+31 6 12345678',
            'message' => 'I would like a website for my bakery.',
            'website' => '',
            'form_token' => $this->tokens->issue(time() - 30),
        ];
    }

    public function testValidMessageIsSent(): void
    {
        $result = $this->service()->submit($this->validInput(), '203.0.113.5', Language::Dutch);

        self::assertSame(ContactResult::SENT, $result->status);
        self::assertCount(1, $this->mailer->sent);
        self::assertSame('jan@example.com', $this->mailer->sent[0]->email);
        self::assertSame(Language::Dutch, $this->mailer->sent[0]->language);
    }

    public function testFilledTrapFieldIsSilentlyIgnored(): void
    {
        $result = $this->service()->submit($this->validInput(['website' => 'https://spam.example']), '203.0.113.5', Language::English);

        self::assertSame(ContactResult::SENT, $result->status, 'Bots must not learn they were caught');
        self::assertCount(0, $this->mailer->sent);
    }

    public function testFormSubmittedInstantlyIsSilentlyIgnored(): void
    {
        $result = $this->service()->submit($this->validInput(['form_token' => $this->tokens->issue()]), '203.0.113.5', Language::English);

        self::assertSame(ContactResult::SENT, $result->status);
        self::assertCount(0, $this->mailer->sent);
    }

    public function testExpiredFormAsksToSendAgain(): void
    {
        $result = $this->service()->submit($this->validInput(['form_token' => $this->tokens->issue(time() - 8000)]), '203.0.113.5', Language::English);

        self::assertSame(ContactResult::EXPIRED, $result->status);
        self::assertCount(0, $this->mailer->sent);
    }

    public function testInvalidFieldsAreReported(): void
    {
        $result = $this->service()->submit($this->validInput([
            'name' => 'J',
            'email' => 'not-an-email',
            'phone' => 'call me',
            'message' => 'Hi',
        ]), '203.0.113.5', Language::English);

        self::assertSame(ContactResult::INVALID, $result->status);
        self::assertSame(['name', 'email', 'phone', 'message'], array_keys($result->errors));
        self::assertCount(0, $this->mailer->sent);
    }

    public function testPhoneNumberIsOptional(): void
    {
        $result = $this->service()->submit($this->validInput(['phone' => '']), '203.0.113.5', Language::English);

        self::assertSame(ContactResult::SENT, $result->status);
    }

    public function testLineBreaksCannotBeInjectedIntoTheName(): void
    {
        $this->service()->submit($this->validInput(['name' => "Jan\r\nBcc: victim@example.com"]), '203.0.113.5', Language::English);

        self::assertStringNotContainsString("\n", $this->mailer->sent[0]->name);
    }

    public function testTooManyMessagesAreRefused(): void
    {
        $this->limiter->allow = false;

        $result = $this->service()->submit($this->validInput(), '203.0.113.5', Language::English);

        self::assertSame(ContactResult::TOO_MANY, $result->status);
        self::assertCount(0, $this->mailer->sent);
    }

    public function testFailedSendingIsReported(): void
    {
        $this->mailer->works = false;

        $result = $this->service()->submit($this->validInput(), '203.0.113.5', Language::English);

        self::assertSame(ContactResult::FAILED, $result->status);
    }
}
