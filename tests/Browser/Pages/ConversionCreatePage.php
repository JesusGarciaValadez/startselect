<?php

namespace Tests\Browser\Pages;

use Facebook\WebDriver\Exception\TimeoutException;
use Laravel\Dusk\Browser;
use Laravel\Dusk\Page;

class ConversionCreatePage extends Page
{
    /**
     * Assert that the browser is on the page.
     */
    public function assert(Browser $browser): void
    {
        $browser->assertPathIs($this->url())
            ->assertSee('Coding test for the PHP Developer role.')
            ->assertSee('Conversion')
            ->assertSee('Money currency conversions from different currencies to EUR.');
    }

    /**
     * Get the URL for the page.
     */
    public function url(): string
    {
        return '/conversion/create';
    }

    /**
     * Create a new playlist.
     *
     * @throws TimeoutException
     */
    public function createNewConversion(Browser $browser, int $currency, float $amount): void
    {
        $browser->select('@from_currency_id', $currency)
            ->type('@amount', $amount)
            ->press('@save');
    }
}
