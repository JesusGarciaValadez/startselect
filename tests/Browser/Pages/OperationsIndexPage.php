<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class OperationsIndexPage extends Page
{
    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url())
            ->assertSee('Coding test for the PHP Developer role.')
            ->assertSee('Operations')
            ->assertSee('A table of money operations in Euros.')
            ->assertSee('New operation');
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return '/operations';
    }
}
