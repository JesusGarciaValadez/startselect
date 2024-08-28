<?php

namespace Tests\Unit\app\Services;

use App\Enums\Currency as CurrencyEnum;
use App\Models\Conversion;
use App\Models\Currency;
use App\Services\ConversionService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ConversionServiceTest extends TestCase
{
    use RefreshDatabase;

    private ConversionService $conversionService;

    #[Test]
    public function it_gets_all_conversions(): void
    {
        Conversion::factory()->count(3)->create();

        $conversions = $this->conversionService->getAllConversions();

        $this->assertCount(3, $conversions);
    }

    #[Test]
    public function get_currencies_with_rates(): void
    {
        $currencies = $this->conversionService->getCurrenciesWithRates();

        $this->assertInstanceOf(Collection::class, $currencies);
    }

    #[Test]
    public function it_stores_conversion(): void
    {
        $data = [
            'from_currency_id' => 69,
            'amount' => 100,
        ];

        $conversion = $this->conversionService->storeConversion($data);

        $this->assertInstanceOf(Conversion::class, $conversion);
        $this->assertDatabaseHas('conversions', [
            'from_currency_id' => 69,
            'amount' => 100,
        ]);
    }

    #[Test]
    public function it_deleted_conversion(): void
    {
        $conversion = Conversion::factory()->create();

        $this->conversionService->deleteConversion($conversion);

        $this->assertDatabaseMissing('conversions', ['id' => $conversion->id]);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->conversionService = $this->app->make(ConversionService::class);
        array_map(static function (CurrencyEnum $currency) {
            Currency::factory()->create(['id' => $currency->value, 'code' => $currency->name]);
        }, CurrencyEnum::cases());
    }
}
