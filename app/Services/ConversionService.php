<?php

namespace App\Services;

use App\Enums\Currency as CurrencyCatalog;
use App\Models\Conversion;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Support\Collection;

class ConversionService
{
    public function getAllConversions(): \Illuminate\Database\Eloquent\Collection|Collection
    {
        return Conversion::all()->sortByDesc('created_at');
    }

    public function getCurrenciesWithRates(): Collection
    {
        $exchangeRates = $this->getExchangeRates();

        return Collection::make(array_map(static function (string $id, string $rate) {
            if (CurrencyCatalog::tryFrom($id)) {
                return [
                    'id' => $id,
                    'rate' => $rate,
                    'name' => CurrencyCatalog::from($id)->name,
                    'symbol' => CurrencyCatalog::from($id)->symbol(),
                ];
            }

            return null;
        }, array_keys($exchangeRates), array_values($exchangeRates)))->filter();
    }

    private function getExchangeRates(): array
    {
        $rates = [];
        include app_path('/Helpers/exchange_rates.php');

        return $rates;
    }

    public function storeConversion(array $data): mixed
    {
        $fromCurrency = CurrencyCatalog::from($data['from_currency_id']);
        $toCurrency = CurrencyCatalog::EUR;
        $amount = new MoneyValueObject($data['amount'], $fromCurrency->name);
        $payload = [
            'from_currency_id' => $fromCurrency->value,
            'to_currency_id' => $toCurrency->value,
            'amount' => $amount->getAmount(),
            'conversion' => $amount->convertTo($fromCurrency->name)->getAmount(),
        ];

        return Conversion::create($payload);
    }

    public function deleteConversion(Conversion $conversion): void
    {
        $conversion->delete();
    }
}
