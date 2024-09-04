<?php

namespace Tests\Unit\app\Models;

use App\Models\Currency;
use App\Models\Operation;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OperationTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function itCanCreateAMoneyModel(): void
    {
        $currency = Currency::factory()->create(['code' => 'EUR']);
        $moneyModel = Operation::create([
            'operation' => 'add',
            'operand1' => 10.00,
            'operand2' => 20.00,
            'result' => 30.00,
            'currency_id' => $currency->id,
        ]);

        $this->assertInstanceOf(Operation::class, $moneyModel);
        $this->assertEquals(10.00, $moneyModel->operand1);
        $this->assertEquals('EUR', $currency->code);
    }

    #[Test]
    public function itCanSaveAMoneyModelToDatabase(): void
    {
        $currency = Currency::factory()->create(['code' => 'GBP']);
        $moneyModel = new Operation([
            'operation' => 'add',
            'operand1' => 100.00,
            'operand2' => 200.00,
            'result' => 300.00,
            'currency_id' => $currency->id,
        ]);
        $moneyModel->save();

        $this->assertDatabaseHas('operations', [
            'result' => 300.00,
            'currency_id' => $currency->id,
        ]);
    }

    #[Test]
    public function itCanUpdateMoneyModelInDatabase(): void
    {
        $currency = Currency::factory()->create(['code' => 'JPY']);
        $moneyModel = new Operation([
            'operation' => 'add',
            'operand1' => 2000.00,
            'operand2' => 3000.00,
            'result' => 5000.00,
            'currency_id' => $currency->id,
        ]);
        $moneyModel->save();

        $moneyModel->update([
            'operand1' => 5000.00,
            'operand2' => 3000.00,
            'result' => 8000.00,
        ]);

        $this->assertDatabaseHas('operations', [
            'result' => 8000.00,
            'currency_id' => $currency->id,
        ]);
    }

    #[Test]
    public function itCanDeleteAMoneyModelFromDatabase(): void
    {
        $currency = Currency::factory()->create(['code' => 'AUD']);
        $moneyModel = Operation::create([
            'operation' => 'add',
            'operand1' => 1.00,
            'operand2' => 2.00,
            'result' => 3.00,
            'currency_id' => $currency->id,
        ]);

        $moneyModel->delete();

        $this->assertDatabaseMissing('operations', [
            'result' => 3.00,
            'currency_id' => $currency->id,
        ]);
    }

    #[Test]
    public function itThrowsExceptionWhenAmountIsMissing(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['currency' => 'USD']);
    }

    #[Test]
    public function itThrowsExceptionWhenCurrencyIsMissing(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => 100.00]);
    }

    #[Test]
    public function itThrowsExceptionWhenCurrencyIsNotAString(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 100]);
    }

    #[Test]
    public function itThrowsExceptionWhenAmountIsNotANumber(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => '100.00', 'currency' => 'USD']);
    }

    #[Test]
    public function itThrowsAnExceptionWhenTheCurrencyIsNotAValidOne(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'US']);
    }

    #[Test]
    public function itThrowsAnExceptionWhenTheOperationIsMissing(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD']);
    }

    #[Test]
    public function itThrowsExceptionWhenOperationIsNotAString(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 100]);
    }

    #[Test]
    public function itThrowsAnExceptionWhenAnOperationIsNotAValidOne(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'divide']);
    }

    #[Test]
    public function itThrowsAnExceptionWhenTheOperand1IsMissing(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'add']);
    }

    #[Test]
    public function itThrowsAnExceptionWhenTheOperand1IsNotANumber(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'add', 'operand1' => '100.00']);
    }

    #[Test]
    public function itThrowsAnExceptionWhenTheOperand2IsMissing(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'add', 'operand1' => 100.00]);
    }

    #[Test]
    public function itThrowsAnExceptionWhenTheOperand2IsNotANumber(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'add', 'operand1' => 100.00, 'operand2' => '100.00']);
    }

    #[Test]
    public function itThrowsAnExceptionWhenTheResultIsMissing(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'add', 'operand1' => 100.00, 'operand2' => 100.00]);
    }

    #[Test]
    public function itThrowsAnExceptionWhenTheResultIsNotANumber(): void
    {
        $this->expectException(QueryException::class);

        Operation::create(['amount' => 100.00, 'currency' => 'USD', 'operation' => 'add', 'operand1' => 100.00, 'operand2' => 100.00, 'result' => '100.00']);
    }

    #[Test]
    public function itCanRetrieveCurrency(): void
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
