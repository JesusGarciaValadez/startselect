<?php

namespace Tests\Unit\app\Traits;

use App\Enums\Currency;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EnumToArrayTest extends TestCase
{
    #[Test]
    public function itCanGetNames(): void
    {
        $this->assertCount(count(Currency::cases()), Currency::names());
    }

    #[Test]
    public function itCanGetValues(): void
    {
        $this->assertArrayHasKey(1, Currency::values());
    }

    #[Test]
    public function itCanCheckIfKeyExists(): void
    {
        $this->assertTrue(Currency::key('USD'));
        $this->assertFalse(Currency::key('GBM'));
    }

    #[Test]
    public function itCanGetArrayRepresentation(): void
    {
        $this->assertCount(count(Currency::cases()), Currency::array());
        $this->assertCount(164, Currency::array());
    }
}
