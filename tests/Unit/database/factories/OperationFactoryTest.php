<?php

namespace Tests\Unit\database\factories;

use App\Enums\Currency as CurrencyEnum;
use App\Models\Currency;
use App\Models\Operation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OperationFactoryTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_valid_operation(): void
    {
        array_map(static function (CurrencyEnum $currency) {
            Currency::factory()->create(['id' => $currency->value, 'code' => $currency->name]);
        }, CurrencyEnum::cases());

        $operation = Operation::factory()->create();

        $this->assertArrayIsEqualToArrayIgnoringListOfKeys([
            'currency_id' => $operation->currency_id,
            'operation' => $operation->operation,
            'operand1' => $operation->operand1,
            'operand2' => $operation->operand2,
            'result' => $operation->result,
        ], $operation->toArray(), ['id', 'created_at', 'updated_at']);
        $this->assertDatabaseCount('operations', 1);
    }
}
