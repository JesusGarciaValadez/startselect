<?php

namespace Tests\Unit\app\Models;

use App\Enums\Currency as CurrencyEnum;
use App\Models\Conversion;
use App\Models\Currency;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ConversionTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itCanCreateAConversion(): void
    {
        $exchangeRates = $this->getExchangeRates();
        array_map(static function (CurrencyEnum $currency) {
            Currency::factory()->create(['id' => $currency->value, 'code' => $currency->name]);
        }, CurrencyEnum::cases());

        $fromCurrency = Currency::where(['code' => 'EUR'])->first();
        $toCurrency = Currency::find(array_rand($exchangeRates, 1));
        $amount = new MoneyValueObject(100, $fromCurrency->code);
        Conversion::create([
            'from_currency_id' => $fromCurrency->id,
            'to_currency_id' => $toCurrency->id,
            'amount' => $amount->getAmount(),
            'conversion' => $amount->convertTo($toCurrency->code)->getAmount(),
        ]);

        $this->assertDatabaseHas('conversions', [
            'from_currency_id' => $fromCurrency->id,
            'to_currency_id' => $toCurrency->id,
            'amount' => $amount->getAmount(),
            'conversion' => $amount->convertTo($toCurrency->code)->getAmount(),
        ]);
        $this->assertDatabaseCount('conversions', 1);
    }

    #[Test]
    public function itCanUpdateAConversion(): void
    {
        $exchangeRates = $this->getExchangeRates();
        array_map(static function (CurrencyEnum $currency) {
            Currency::factory()->create(['id' => $currency->value, 'code' => $currency->name]);
        }, CurrencyEnum::cases());

        $fromCurrency = Currency::where(['code' => 'EUR'])->first();
        $toCurrency = Currency::find(array_rand($exchangeRates, 1));
        $amount = new MoneyValueObject(100, $fromCurrency->code);
        $conversion = Conversion::create([
            'from_currency_id' => $fromCurrency->id,
            'to_currency_id' => $toCurrency->id,
            'amount' => $amount->getAmount(),
            'conversion' => $amount->convertTo($toCurrency->code)->getAmount(),
        ]);

        $newToCurrency = Currency::find(array_rand($exchangeRates, 1));
        $conversion->update([
            'conversion' => $amount->convertTo($newToCurrency->code)->getAmount(),
        ]);

        $this->assertDatabaseHas('conversions', [
            'from_currency_id' => $fromCurrency->id,
            'to_currency_id' => $toCurrency->id,
            'amount' => $amount->getAmount(),
            'conversion' => $conversion->conversion,
        ]);
        $this->assertDatabaseCount('conversions', 1);
    }

    #[Test]
    public function itCanDeleteAConversion(): void
    {
        $exchangeRates = $this->getExchangeRates();
        array_map(static function (CurrencyEnum $currency) {
            Currency::factory()->create(['id' => $currency->value, 'code' => $currency->name]);
        }, CurrencyEnum::cases());

        $fromCurrency = Currency::where(['code' => 'EUR'])->first();
        $toCurrency = Currency::find(array_rand($exchangeRates, 1));
        $amount = new MoneyValueObject(100, $fromCurrency->code);
        $conversion = Conversion::create([
            'from_currency_id' => $fromCurrency->id,
            'to_currency_id' => $toCurrency->id,
            'amount' => $amount->getAmount(),
            'conversion' => $amount->convertTo($toCurrency->code)->getAmount(),
        ]);
        $conversion->delete();

        $this->assertDatabaseMissing('conversions', [
            'from_currency_id' => $fromCurrency->id,
            'to_currency_id' => $toCurrency->id,
            'amount' => $amount->getAmount(),
            'conversion' => $conversion->conversion,
        ]);
        $this->assertDatabaseCount('conversions', 0);
    }

    #[Test]
    public function itCanRetrieveFromCurrency(): void
    {
        $exchangeRates = $this->getExchangeRates();
        array_map(static function (CurrencyEnum $currency) {
            Currency::factory()->create(['id' => $currency->value, 'code' => $currency->name]);
        }, CurrencyEnum::cases());

        $fromCurrency = Currency::where(['code' => 'EUR'])->first();
        $toCurrency = Currency::find(array_rand($exchangeRates, 1));
        $amount = new MoneyValueObject(100, $fromCurrency->code);
        $conversion = Conversion::create([
            'from_currency_id' => $fromCurrency->id,
            'to_currency_id' => $toCurrency->id,
            'amount' => $amount->getAmount(),
            'conversion' => $amount->convertTo($toCurrency->code)->getAmount(),
        ]);

        $this->assertEquals($fromCurrency->id, $conversion->fromCurrency->id);
    }

    #[Test]
    public function itCanRetrieveToCurrency(): void
    {
        $exchangeRates = $this->getExchangeRates();
        array_map(static function (CurrencyEnum $currency) {
            Currency::factory()->create(['id' => $currency->value, 'code' => $currency->name]);
        }, CurrencyEnum::cases());

        $fromCurrency = Currency::where(['code' => 'EUR'])->first();
        $toCurrency = Currency::find(array_rand($exchangeRates, 1));
        $amount = new MoneyValueObject(100, $fromCurrency->code);
        $conversion = Conversion::create([
            'from_currency_id' => $fromCurrency->id,
            'to_currency_id' => $toCurrency->id,
            'amount' => $amount->getAmount(),
            'conversion' => $amount->convertTo($toCurrency->code)->getAmount(),
        ]);

        $this->assertEquals($toCurrency->id, $conversion->toCurrency->id);
    }

    private function getExchangeRates(): array
    {
        $rates = [];
        include app_path('/Helpers/exchange_rates.php');

        return $rates;
    }
}
