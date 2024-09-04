<?php

namespace Tests\Browser;

use App\Enums\Currency as CurrencyEnum;
use App\Models\Currency;
use App\Models\Operation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\OperationCreatePage;
use Tests\Browser\Pages\OperationsIndexPage;
use Tests\DuskTestCase;
use Throwable;

class OperationsTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * Indicates which tables should be excluded from truncation.
     */
    protected array $exceptTables = ['currencies'];

    /**
     * Indicates which connections should have their tables truncated.
     */
    protected array $connectionsToTruncate = ['mysql'];

    public function setUp(): void
    {
        parent::setUp();

        array_map(static function (CurrencyEnum $currency) {
            Currency::factory()->create(['id' => $currency->value, 'code' => $currency->name]);
        }, CurrencyEnum::cases());
    }

    #[Test]
    public function itShowsAllTheAvailableOperations(): void
    {
        $operation = Operation::factory()->create([
            'operand1' => 100,
            'operand2' => 50,
            'operation' => 'add',
            'result' => 150,
        ]);

        $this->browse(function (Browser $browser) use ($operation) {
            $browser->visit(new OperationsIndexPage);
            $browser->assertPathIs((new OperationsIndexPage)->url())
                ->assertSee('Operations')
                ->assertSee('A table of money operations in Euros.')
                ->assertDontSee('No new operations to show.')
                ->storeSource('operations')
                ->assertPresent('@delete_operation_'.$operation->id)
                ->assertSee('Delete')
                ->assertSee('150');
        });
    }

    /**
     * @throws Throwable
     */
    #[Test]
    public function itCreatesANewOperation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new OperationCreatePage);

            if ($browser->seeLink('New conversion')) {
                $browser->clickLink('New conversion')
                    ->waitForLocation((new OperationCreatePage)->url())
                    ->on(new OperationCreatePage)
                    ->createNewOperation(100, 50, 'add')
                    ->on(new OperationsIndexPage)
                    ->assertPathIs((new OperationsIndexPage)->url())
                    ->assertSee('100')
                    ->assertSee('+')
                    ->assertSee('50')
                    ->assertSee('150');
            }
        });
    }

    #[Test]
    public function itCreatesANewOperationWithEmptyFields(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new OperationCreatePage)
                ->press('@save')
                ->assertPathIs((new OperationCreatePage)->url())
                ->assertSee('The operand1 field is required.')
                ->assertSee('The operand2 field is required.');
        });
    }

    #[Test]
    public function itDeletesAGivenOperation(): void
    {
        $operation = Operation::factory()->create([
            'operand1' => 100,
            'operand2' => 50,
            'operation' => 'add',
            'result' => 150,
        ]);

        $this->browse(function (Browser $browser) use ($operation) {
            $browser->visit(new OperationsIndexPage)
                ->assertDontSee('No new operations to show.')
                ->storeSource('operations')
                ->press('@delete_operation_'.$operation->id)
                ->assertSee('No new operations to show.');
        });
    }
}
