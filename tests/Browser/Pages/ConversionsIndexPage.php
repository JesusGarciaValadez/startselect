<?php

namespace Tests\Browser\Pages;

use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class ConversionsIndexPage extends Page
{
    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url())
            ->assertSee('Coding test for the PHP Developer role.')
            ->assertSee('Conversions')
            ->assertSee('A table of money currency conversions from EUR to other currencies.')
            ->assertSee('New conversion');
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return '/conversions';
    }
}
