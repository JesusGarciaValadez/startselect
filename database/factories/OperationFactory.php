<?php

namespace Database\Factories;

use App\Enums\Currency as CurrencyCatalog;
use App\Models\Currency;
use App\Models\Operation;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Database\Eloquent\Factories\Factory;
use Random\RandomException;

/**
 * @extends Factory<Operation>
 */
class OperationFactory extends Factory
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
        $currency = Currency::find(
            CurrencyCatalog::cases()[random_int(0, count(CurrencyCatalog::cases()) - 1)]->value
        );
        $decimals = (CurrencyCatalog::from($currency->id))->decimals() + 1;
        $operand1 = new MoneyValueObject($this->faker->randomFloat($decimals, 0, 1000), $currency->code);
        $operation = $this->faker->randomElement(['add', 'subtract', 'multiply', 'divide', 'min', 'max', 'avg', 'total', 'discount']);
        $operand2 = new MoneyValueObject($this->faker->randomFloat($decimals, 0, 1000), $currency->code);
        $result = match ($operation) {
            'add' => $operand1->add($operand2->getAmount())->getAmount(),
            'subtract' => $operand1->subtract($operand2->getAmount())->getAmount(),
            'multiply' => $operand1->multiply($operand2->getAmount())->getAmount(),
            'divide' => $operand1->divide($operand2->getAmount())->getAmount(),
            'min' => MoneyValueObject::min([$operand1, $operand2])->getAmount(),
            'max' => MoneyValueObject::max([$operand1, $operand2])->getAmount(),
            'avg' => MoneyValueObject::average([$operand1, $operand2])->getAmount(),
            'total' => MoneyValueObject::total([$operand1, $operand2])->getAmount(),
            'discount' => $operand1->applyDiscount($operand2->getAmount())->getAmount(),
        };

        return [
            'currency_id' => $currency->id,
            'operation' => $operation,
            'operand1' => $operand1->getAmount(),
            'operand2' => $operand2->getAmount(),
            'result' => $result,
        ];
    }
}
