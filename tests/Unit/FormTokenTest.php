<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use App\Framework\FormToken;
use PHPUnit\Framework\TestCase;

final class FormTokenTest extends TestCase
{
    private FormToken $tokens;

    protected function setUp(): void
    {
        $this->tokens = new FormToken('test-secret', minimumSeconds: 3, maximumSeconds: 7200);
    }

    public function testTokenIsValidAfterTheMinimumTime(): void
    {
        $token = $this->tokens->issue(1000);

        self::assertSame(FormToken::VALID, $this->tokens->check($token, 1010));
    }

    public function testTokenSubmittedTooQuicklyIsRejected(): void
    {
        $token = $this->tokens->issue(1000);

        self::assertSame(FormToken::TOO_FAST, $this->tokens->check($token, 1001));
    }

    public function testOldTokenHasExpired(): void
    {
        $token = $this->tokens->issue(1000);

        self::assertSame(FormToken::EXPIRED, $this->tokens->check($token, 1000 + 7201));
    }

    public function testChangedOrForeignTokenIsInvalid(): void
    {
        $token = $this->tokens->issue(1000);

        self::assertSame(FormToken::INVALID, $this->tokens->check('999.' . explode('.', $token)[1], 1010));
        self::assertSame(FormToken::INVALID, $this->tokens->check((new FormToken('other-secret'))->issue(1000), 1010));
        self::assertSame(FormToken::INVALID, $this->tokens->check('', 1010));
    }
}
