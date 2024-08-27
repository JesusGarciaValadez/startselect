<?php

namespace Database\Factories;

use App\Enums\Currency as CurrencyCatalog;
use App\Models\Conversion;
use App\Models\Currency;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Random\RandomException;

/**
 * @extends Factory<Conversion>
 */
class ConversionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * @throws RandomException
     */
    public function definition(): array
    {
        $exchangeRates = $this->getExchangeRates();
        $currencyFrom = Currency::find(
            CurrencyCatalog::cases()[random_int(0, count(CurrencyCatalog::cases()) - 1)]->value
        );
        $currencyTo = Currency::find(array_rand($exchangeRates, 1));
        $decimals = (CurrencyCatalog::from($currencyTo->id))->decimals() + 1;
        $amount = new MoneyValueObject($this->faker->randomFloat($decimals, 0, 1000), $currencyFrom->code);

        return [
            'from_currency_id' => $currencyFrom->id,
            'to_currency_id' => $currencyTo->id,
            'amount' => $amount->getAmount(),
            'conversion' => $amount->convertTo($currencyTo->code)->getAmount(),
        ];
    }

    /**
     * Get exchange rates from the exchange_rates.php file.
     */
    private function getExchangeRates(): array
    {
        $rates = [];
        include app_path('/Helpers/exchange_rates.php');

        return $rates;
    }
}
