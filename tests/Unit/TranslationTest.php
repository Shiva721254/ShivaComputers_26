<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class TranslationTest extends TestCase
{
    public function testDutchAndEnglishHaveTheSameKeys(): void
    {
        $dutch = array_keys(require __DIR__ . '/../../translations/nl.php');
        $english = array_keys(require __DIR__ . '/../../translations/en.php');

        sort($dutch);
        sort($english);

        self::assertSame($dutch, $english);
    }
}
