<?php

namespace Tests\Unit\app\Models;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itCanCreateACurrency(): void
    {
        Currency::create(['code' => 'USD']);

        $this->assertDatabaseHas('currencies', [
            'code' => 'USD',
        ]);
        $this->assertDatabaseCount('currencies', 1);
    }

    #[Test]
    public function itCanUpdateACurrency(): void
    {
        $currency = Currency::create(['code' => 'USD']);
        $currency->update(['code' => 'JPY']);

        $this->assertDatabaseHas('currencies', [
            'code' => 'JPY',
        ]);
        $this->assertDatabaseCount('currencies', 1);
    }

    #[Test]
    public function itCanDeleteACurrency(): void
    {
        $currency = Currency::create(['code' => 'USD']);
        $currency->delete();

        $this->assertDatabaseMissing('currencies', [
            'code' => 'JPY',
        ]);
        $this->assertDatabaseCount('currencies', 0);
    }
}
