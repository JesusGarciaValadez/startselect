<?php

namespace Tests\Unit\database\factories;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CurrencyFactoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_valid_currency(): void
    {
        $currency = Currency::factory()->create();

        $this->assertDatabaseHas('currencies', [
            'id' => $currency->id,
            'code' => $currency->code,
        ]);
    }
}
