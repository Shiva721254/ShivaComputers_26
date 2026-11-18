<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Framework\Application;
use App\Framework\FormToken;
use App\Framework\Request;
use App\Framework\Response;
use App\Framework\Storage;
use PHPUnit\Framework\TestCase;

final class ContactFormTest extends TestCase
{
    private function send(Request $request): Response
    {
        /** @var Application $application */
        $application = require __DIR__ . '/../../config/bootstrap.php';

        return $application->handle($request);
    }

    private function validToken(): string
    {
        $config = require __DIR__ . '/../../config/app.php';

        return (new FormToken((new Storage($config['storage']))->secret()))->issue(time() - 30);
    }

    public function testContactPageShowsTheForm(): void
    {
        $response = $this->send(new Request('GET', '/en/contact'));

        self::assertSame(200, $response->status);
        self::assertStringContainsString('<form class="contact-form" method="post" action="/en/contact#contact-form"', $response->body);
        self::assertStringContainsString('name="form_token"', $response->body);
        self::assertStringContainsString("form-action 'self'", $response->headers['Content-Security-Policy']);
    }

    public function testInvalidSubmissionShowsErrorsAndKeepsInput(): void
    {
        $response = $this->send(new Request('POST', '/nl/contact', [
            'name' => 'Jan',
            'email' => 'wrong',
            'message' => 'Hi',
            'form_token' => $this->validToken(),
        ], ip: '203.0.113.9'));

        self::assertSame(422, $response->status);
        self::assertStringContainsString('Vul een geldig e-mailadres in', $response->body);
        self::assertStringContainsString('value="Jan"', $response->body);
        self::assertStringContainsString('aria-invalid="true"', $response->body);
    }

    public function testSpamIsRedirectedAsIfItWasSent(): void
    {
        $response = $this->send(new Request('POST', '/en/contact', [
            'name' => 'Spam Bot',
            'email' => 'bot@example.com',
            'message' => 'Buy cheap things now!!!',
            'website' => 'https://spam.example',
            'form_token' => $this->validToken(),
        ]));

        self::assertSame(303, $response->status);
        self::assertSame('/en/contact?sent=1#contact-form', $response->headers['Location']);
    }

    public function testThankYouMessageAfterSending(): void
    {
        $response = $this->send(new Request('GET', '/en/contact', query: ['sent' => '1']));

        self::assertStringContainsString('Thank you, your message has been sent.', $response->body);
        self::assertStringNotContainsString('<form class="contact-form"', $response->body);
    }
}
