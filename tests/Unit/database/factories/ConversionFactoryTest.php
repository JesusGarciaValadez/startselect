<?php

namespace Tests\Unit\database\factories;

use App\Enums\Currency as CurrencyEnum;
use App\Models\Conversion;
use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ConversionFactoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_conversion_with_factory(): void
    {
        array_map(static function (CurrencyEnum $currency) {
            Currency::factory()->create(['id' => $currency->value, 'code' => $currency->name]);
        }, CurrencyEnum::cases());

        $conversion = Conversion::factory()->create();

        $this->assertArrayIsEqualToArrayIgnoringListOfKeys([
            'id' => $conversion->id,
            'from_currency_id' => $conversion->from_currency_id,
            'to_currency_id' => $conversion->to_currency_id,
            'amount' => $conversion->amount,
            'conversion' => $conversion->conversion,
        ], $conversion->toArray(), ['created_at', 'updated_at']);
        $this->assertDatabaseCount('conversions', 1);
    }
}
