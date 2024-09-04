<?php

namespace Tests\Unit\app\Services;

use App\Enums\Currency as CurrencyEnum;
use App\Models\Currency;
use App\Models\Operation;
use App\Services\OperationService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OperationServiceTest extends TestCase
{
    use RefreshDatabase;

    private OperationService $operationService;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->operationService = $this->app->make(OperationService::class);
        array_map(static function (CurrencyEnum $currency) {
            Currency::factory()->create(['id' => $currency->value, 'code' => $currency->name]);
        }, CurrencyEnum::cases());
    }

    #[Test]
    public function itGetAllOperations(): void
    {
        Operation::factory()->count(3)->create();

        $operations = $this->operationService->getAllOperations();

        $this->assertCount(3, $operations);
    }

    #[Test]
    public function itGetsCurrencies(): void
    {
        $currencies = $this->operationService->getCurrencies();

        $this->assertIsArray($currencies);
    }

    #[Test]
    public function itGetsOperations(): void
    {
        $operations = $this->operationService->getOperations();

        $this->assertIsArray($operations);
    }

    #[Test]
    public function itStoresAnOperation(): void
    {
        $data = [
            'currency_id' => 1,
            'operation' => 'add',
            'operand1' => 100,
            'operand2' => 50,
        ];

        $result = $this->operationService->storeOperation($data);

        $this->assertTrue($result);
        $this->assertDatabaseHas('operations', [
            'currency_id' => 1,
            'operation' => 'add',
            'operand1' => 100,
            'operand2' => 50,
        ]);
    }

    #[Test]
    public function itDeletesAnOperation(): void
    {
        $operation = Operation::factory()->create();

        $this->operationService->deleteOperation($operation);

        $this->assertDatabaseMissing('operations', ['id' => $operation->id]);
    }
}
