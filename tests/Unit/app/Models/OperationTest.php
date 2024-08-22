<?php

namespace Tests\Unit\app\Models;

use App\Models\Currency;
use App\Models\Operation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OperationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_can_create_a_money_model(): void
    {
        $currency = Currency::factory()->create(['code' => 'EUR']);
        $moneyModel = Operation::create([
            'operation' => 'add',
            'operand1' => 10.00,
            'operand2' => 20.00,
            'result' => 30.00,
            'currency_id' => $currency->id
        ]);

        $this->assertInstanceOf(Operation::class, $moneyModel);
        $this->assertEquals(10.00, $moneyModel->operand1);
        $this->assertEquals('EUR', $currency->code);
    }

    #[Test]
    public function it_can_save_a_money_model_to_database(): void
    {
        $currency = Currency::factory()->create(['code' => 'GBP']);
        $moneyModel = new Operation([
            'operation' => 'add',
            'operand1' => 100.00,
            'operand2' => 200.00,
            'result' => 300.00,
            'currency_id' => $currency->id
        ]);
        $moneyModel->save();

        $this->assertDatabaseHas('operations', [
            'result' => 300.00,
            'currency_id' => $currency->id
        ]);
    }

    #[Test]
    public function it_can_update_a_money_model_in_database(): void
    {
        $currency = Currency::factory()->create(['code' => 'JPY']);
        $moneyModel = new Operation([
            'operation' => 'add',
            'operand1' => 2000.00,
            'operand2' => 3000.00,
            'result' => 5000.00,
            'currency_id' => $currency->id
        ]);
        $moneyModel->save();

        $moneyModel->update([
            'operand1' => 5000.00,
            'operand2' => 3000.00,
            'result' => 8000.00
        ]);

        $this->assertDatabaseHas('operations', [
            'result' => 8000.00,
            'currency_id' => $currency->id
        ]);
    }

    #[Test]
    public function it_can_delete_a_money_model_from_database(): void
    {
        $currency = Currency::factory()->create(['code' => 'AUD']);
        $moneyModel = Operation::create([
            'operation' => 'add',
            'operand1' => 1.00,
            'operand2' => 2.00,
            'result' => 3.00,
            'currency_id' => $currency->id
        ]);

        $moneyModel->delete();

        $this->assertDatabaseMissing('operations', [
            'result' => 3.00,
            'currency_id' => $currency->id
        ]);
    }

    #[Test]
    public function it_throws_exception_when_amount_is_missing(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['currency' => 'USD']);
    }

    #[Test]
    public function it_throws_exception_when_currency_is_missing(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => 100.00]);
    }

    #[Test]
    public function it_throws_exception_when_currency_is_not_a_string(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 100]);
    }

    #[Test]
    public function it_throws_exception_when_amount_is_not_a_number(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => '100.00', 'currency' => 'USD']);
    }

    #[Test]
    public function it_throws_exception_when_currency_is_not_a_valid_currency(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'US']);
    }

    #[Test]
    public function it_throws_exception_when_operation_is_missing(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD']);
    }

    #[Test]
    public function it_throws_exception_when_operation_is_not_a_string(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 100]);
    }

    #[Test]
    public function it_throws_exception_when_operation_is_not_a_valid_operation(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'divide']);
    }

    #[Test]
    public function it_throws_exception_when_operand1_is_missing(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'add']);
    }

    #[Test]
    public function it_throws_exception_when_operand1_is_not_a_number(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'add', 'operand1' => '100.00']);
    }

    #[Test]
    public function it_throws_exception_when_operand2_is_missing(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'add', 'operand1' => 100.00]);
    }

    #[Test]
    public function it_throws_exception_when_operand2_is_not_a_number(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'add', 'operand1' => 100.00, 'operand2' => '100.00']);
    }

    #[Test]
    public function it_throws_exception_when_result_is_missing(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'add', 'operand1' => 100.00, 'operand2' => 100.00]);
    }

    #[Test]
    public function it_throws_exception_when_result_is_not_a_number(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'add', 'operand1' => 100.00, 'operand2' => 100.00, 'result' => '100.00']);
    }

    #[Test]
    public function it_can_retrieve_currency(): void
    {
        $currency = Currency::factory()->create(['code' => 'JPY']);
        $operation = Operation::create([
            'currency_id' => $currency->id,
            'operation' => 'add',
            'operand1' => 10.0,
            'operand2' => 10.0,
            'result' => 100,
        ]);

        $this->assertEquals($currency->code, $operation->currency->code);
    }
}
