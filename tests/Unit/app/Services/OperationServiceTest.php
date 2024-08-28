<?php

namespace Tests\Unit\Services;

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

    #[Test]
    public function it_get_all_operations(): void
    {
        Operation::factory()->count(3)->create();

        $operations = $this->operationService->getAllOperations();

        $this->assertCount(3, $operations);
    }

    #[Test]
    public function it_gets_currencies(): void
    {
        $currencies = $this->operationService->getCurrencies();

        $this->assertIsArray($currencies);
    }

    #[Test]
    public function it_gets_operations(): void
    {
        $operations = $this->operationService->getOperations();

        $this->assertIsArray($operations);
    }

    #[Test]
    public function it_stores_an_operation(): void
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
    public function it_deletes_an_operation(): void
    {
        $operation = Operation::factory()->create();

        $this->operationService->deleteOperation($operation);

        $this->assertDatabaseMissing('operations', ['id' => $operation->id]);
    }

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
}
