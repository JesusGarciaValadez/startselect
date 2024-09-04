<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class OperationCreatePage extends Page
{
    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url())
            ->assertSee('Coding test for the PHP Developer role.')
            ->assertSee('Operation')
            ->assertSee('Different operations with money.');
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return '/operation/create';
    }

    public function createNewOperation(Browser $browser, int $operand1, int $operand2, string $operator): void
    {
        $browser->type('@operand1', $operand1)
            ->type('@operand2', $operand2)
            ->select('@operation', $operator)
            ->press('@save');
    }
}
