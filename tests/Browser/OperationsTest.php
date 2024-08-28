<?php

namespace Tests\Browser;

use App\Models\Operation;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class OperationsTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function testOperationsIndex()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/operations')
                ->assertSee('Operations')
                ->assertSee('A table of money operations in Euros.');
        });
    }

    public function testCreateOperation()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/operation/create')
                ->assertSee('Create operation')
                ->type('operand1', '100')
                ->type('operand2', '50')
                ->select('operation', 'add')
                ->press('Save')
                ->assertPathIs('/operations')
                ->assertSee('100')
                ->assertSee('+')
                ->assertSee('50')
                ->assertSee('150');
        });
    }

    public function testCreateOperationWithEmptyFields()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/operation/create')
                ->press('Save')
                ->assertSee('The operand1 field is required.')
                ->assertSee('The operand2 field is required.');
        });
    }

    public function testDeleteOperation()
    {
        $operation = Operation::factory()->create();

        $this->browse(function (Browser $browser) use ($operation) {
            $browser->visit('/operations')
                ->press('Delete')
                ->assertDontSee($operation->operand1);
        });
    }
}
