<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\Currency as CurrencyEnum;
use App\Models\Currency;
use App\Models\Operation;
use App\Services\OperationService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OperationControllerTest extends TestCase
{
    use RefreshDatabase;

    private OperationService $operationService;

    #[Test]
    public function it_shows_all_the_operations(): void
    {
        $response = $this->get(route('operations'));

        $response->assertStatus(200);
        $response->assertViewHas('operations');
    }

    #[Test]
    public function it_shows_the_form_to_create_a_new_operation(): void
    {
        $response = $this->get(route('operation.create'));

        $response->assertStatus(200);
        $response->assertViewHas('currencies');
        $response->assertViewHas('operations');
    }

    #[Test]
    public function it_stores_a_new_operation(): void
    {
        $data = [
            'currency_id' => 1,
            'operation' => 'add',
            'operand1' => 100,
            'operand2' => 50,
        ];

        $response = $this->post(route('operation.store'), $data);

        $response->assertRedirect(route('operations'));
        $this->assertDatabaseHas('operations', [
            'currency_id' => 1,
            'operation' => 'add',
            'operand1' => 100,
            'operand2' => 50,
        ]);
    }

    #[Test]
    public function it_destroys_a_given_operation(): void
    {
        $operation = Operation::factory()->create();

        $response = $this->delete(route('operation.destroy', $operation));

        $response->assertRedirect(route('operations'));
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
