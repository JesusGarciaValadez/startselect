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

    #[Test]
    public function itGetsAllConversions(): void
    {
        Conversion::factory()->count(3)->create();

        $conversions = $this->conversionService->getAllConversions();

        $this->assertCount(3, $conversions);
    }

    #[Test]
    public function getCurrenciesWithRates(): void
    {
        $currencies = $this->conversionService->getCurrenciesWithRates();

        $this->assertInstanceOf(Collection::class, $currencies);
    }

    #[Test]
    public function itStoresConversion(): void
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
    public function itDeletedConversion(): void
    {
        $conversion = Conversion::factory()->create();

        $this->conversionService->deleteConversion($conversion);

        $this->assertDatabaseMissing('conversions', ['id' => $conversion->id]);
    }
}
