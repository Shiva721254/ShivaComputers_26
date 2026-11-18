<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Enums\Language;
use App\Enums\Page;
use App\Framework\Request;
use App\Framework\Response;
use App\Framework\Translator;
use App\Framework\View;
use App\Models\ContactResult;
use App\Services\ContactFormService;
use App\Services\Interfaces\IContactFormService;
use App\ViewModels\PageViewModelFactory;

final class ContactController extends Controller
{
    private const FIELDS = ['name', 'email', 'phone', 'message', ContactFormService::HONEYPOT_FIELD, 'form_token'];

    public function __construct(
        View $view,
        PageViewModelFactory $pages,
        Translator $translator,
        private readonly IContactFormService $contactForm,
    ) {
        parent::__construct($view, $pages, $translator);
    }

    // GET: /{language}/contact
    public function index(Language $language, Request $request): Response
    {
        return $this->showForm($language, sent: ($request->query['sent'] ?? '') === '1');
    }

    // POST: /{language}/contact
    public function submit(Language $language, Request $request): Response
    {
        $input = [];
        foreach (self::FIELDS as $field) {
            $input[$field] = $request->input($field);
        }

        $result = $this->contactForm->submit($input, $request->ip, $language);

        // Post/Redirect/Get: refreshing the page afterwards cannot send the message twice.
        if ($result->isSent()) {
            return Response::redirect(Page::Contact->path($language) . '?sent=1#contact-form', 303);
        }

        [$status, $alert] = match ($result->status) {
            ContactResult::INVALID => [422, 'contact.error.summary'],
            ContactResult::EXPIRED => [422, 'contact.error.expired'],
            ContactResult::TOO_MANY => [429, 'contact.error.too_many'],
            default => [503, 'contact.error.failed'],
        };

        return $this->showForm($language, old: $input, errors: $result->errors, alert: $alert, status: $status);
    }

    /**
     * @param array<string, string> $old
     * @param array<string, string> $errors
     */
    private function showForm(Language $language, bool $sent = false, array $old = [], array $errors = [], ?string $alert = null, int $status = 200): Response
    {
        return $this->render($language, Page::Contact, [
            'form' => [
                'token' => $this->contactForm->token(),
                'sent' => $sent,
                'old' => $old,
                'errors' => $errors,
                'alert' => $alert,
                'honeypot' => ContactFormService::HONEYPOT_FIELD,
            ],
        ], $status);
    }
}
