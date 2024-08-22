<?php

namespace Tests\Unit\app\Traits;

use App\Enums\Currency;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class EnumToArrayTest extends TestCase
{
    #[Test]
    public function it_can_get_names(): void
    {
        $this->assertCount(count(Currency::cases()), Currency::names());
    }

    #[Test]
    public function it_can_get_values(): void
    {
        $this->assertArrayHasKey(1, Currency::values());
    }

    #[Test]
    public function it_can_check_if_key_exists(): void
    {
        $this->assertTrue(Currency::key('USD'));
        $this->assertFalse(Currency::key('GBM'));
    }

    #[Test]
    public function it_can_get_array_representation(): void
    {
        $this->assertCount(count(Currency::cases()), Currency::array());
        $this->assertCount(164, Currency::array());
    }
}
