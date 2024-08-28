<?php

namespace App\Services;

use App\Enums\Currency;
use App\Models\Operation;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Support\Collection;

class OperationService
{
    public function getAllOperations(): Collection
    {
        return Operation::all();
    }

    public function getCurrencies(): array
    {
        return Currency::cases();
    }

    public function getOperations(): array
    {
        return ['add', 'subtract', 'multiply', 'divide', 'min', 'max', 'avg', 'total', 'discount'];
    }

    public function storeOperation(array $data): bool
    {
        $currency = Currency::from($data['currency_id']);
        $operand1 = new MoneyValueObject($data['operand1'], $currency->name);
        $operand2 = new MoneyValueObject($data['operand2'], $currency->name);
        $result = match ($data['operation']) {
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
        $payload = array_merge($data, ['result' => $result]);

        return Operation::create($payload) !== null;
    }

    public function deleteOperation(Operation $operation): void
    {
        $operation->delete();
    }
}
