<?php

namespace App\ValueObjects;

use App\Enums\Currency;
use InvalidArgumentException;
use TypeError;

class MoneyValueObject
{
    private float $amount;

    private string $currency;

    /**
     * Constructor expects both amount and currency.
     *
     * @throws InvalidArgumentException
     * @throws TypeError
     */
    public function __construct(float $amount, string $currency)
    {
        // Validate and set the amount
        $this->setAmount($amount);

        // Validate and set the currency
        $this->setCurrency($currency);
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getCurrencyId(): int
    {
        return Currency::key($this->currency);
    }

    /**
     * Validate and set the amount.
     *
     * @throws TypeError
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    /**
     * Validate and set the currency.
     *
     * @throws InvalidArgumentException
     */
    public function setCurrency(string $currency): void
    {
        if (empty($currency)) {
            throw new InvalidArgumentException('Currency must be provided.');
        }

        if (! Currency::key(strtoupper($currency))) {
            throw new InvalidArgumentException('Invalid currency code.');
        }

        $this->currency = strtoupper($currency);
    }

    /**
     * Add two MoneyValueObject instances.
     *
     * @throws InvalidArgumentException
     */
    public function add(float $other): MoneyValueObject
    {
        $result = bcadd($this->getAmount(), (string) $other, 10); // Use high precision

        return new self(amount: $result, currency: $this->getCurrency());
    }

    /**
     * Subtract one MoneyValueObject from another.
     *
     * @throws InvalidArgumentException
     */
    public function subtract(float $other): MoneyValueObject
    {
        $result = bcsub($this->getAmount(), (string) $other, 10); // Use high precision

        return new self(amount: $result, currency: $this->getCurrency());
    }

    /**
     * Multiply the MoneyValueObject by a factor.
     */
    public function multiply(float $factor): MoneyValueObject
    {
        $result = bcmul($this->getAmount(), (string) $factor, 10); // Use high precision

        return new self(amount: $result, currency: $this->getCurrency());
    }

    /**
     * Divide the MoneyValueObject by a divisor.
     */
    public function divide(float $divisor): MoneyValueObject
    {
        $result = bcdiv($this->getAmount(), (string) $divisor, 10); // Use high precision

        return new self(amount: $result, currency: $this->getCurrency());
    }

    /**
     * Apply a percentage discount.
     */
    public function applyDiscount(float $percentage): MoneyValueObject
    {
        $discountedAmount = bcmul($this->getAmount(), (string) ((100 - $percentage) / 100), 10);

        return new self(amount: $discountedAmount, currency: $this->getCurrency());
    }

    /**
     * Convert the MoneyValueObject to a different currency.
     *
     * @throws InvalidArgumentException
     */
    public function convertTo(string $toCurrency): MoneyValueObject
    {
        if (Currency::key($this->currency) === null) {
            throw new InvalidArgumentException('Invalid currency code.');
        }

        $listOfCurrencies = Currency::cases();
        $currencyToConvert = null;
        foreach ($listOfCurrencies as $currency) {
            if ($currency->name !== $toCurrency) {
                continue;
            }

            $currencyToConvert = $currency;
        }

        $exchangeRates = $this->getExchangeRates();

        if (empty($exchangeRates[$currencyToConvert->value])) {
            throw new InvalidArgumentException('Invalid exchange rate.');
        }

        $rateFrom = 1;
        $rateTo = $exchangeRates[$currencyToConvert->value];

        $convertedAmount = bcmul($this->getAmount(), (string) ($rateTo / $rateFrom), 10);

        return new self($convertedAmount, $toCurrency);
    }

    /**
     * Ensure that both MoneyValueObject instances have the same currency.
     *
     * @throws InvalidArgumentException
     */
    private function ensureSameCurrency(MoneyValueObject $other): void
    {
        if ($this->getCurrency() !== $other->getCurrency()) {
            throw new InvalidArgumentException('Currency mismatch between '.$this->getCurrency().' and '.$other->getCurrency());
        }
    }

    public static function min(array $moneyObjects): MoneyValueObject
    {
        return array_reduce($moneyObjects, static function ($a, $b) {
            if ($a === null) {
                return $b;
            }

            return $a->getAmount() < $b->getAmount() ? $a : $b;
        });
    }

    public static function max(array $moneyObjects): MoneyValueObject
    {
        return array_reduce($moneyObjects, static function ($a, $b) {
            if ($a === null) {
                return $b;
            }

            return $a->getAmount() > $b->getAmount() ? $a : $b;
        });
    }

    public static function average(array $moneyObjects): MoneyValueObject
    {
        $total = array_reduce($moneyObjects, static fn ($carry, $item) => $carry + $item->getAmount(), 0);
        $currency = $moneyObjects[0]->getCurrency();

        return new MoneyValueObject(amount: $total / count($moneyObjects), currency: $currency);
    }

    public static function total(array $moneyObjects): MoneyValueObject
    {
        $total = array_reduce($moneyObjects, static fn ($carry, $item) => $carry + $item->getAmount(), 0);
        $currency = $moneyObjects[0]->getCurrency();

        return new MoneyValueObject(amount: $total, currency: $currency);
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

    /**
     * Convert the MoneyValueObject to an array.
     *
     * @return array{amount: float, currency: string}
     */
    public function toArray(): array
    {
        return [
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
        ];
    }
}
