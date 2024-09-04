<?php

namespace Tests\Browser;

use App\Enums\Currency as CurrencyEnum;
use App\Models\Conversion;
use App\Models\Currency;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use PHPUnit\Framework\Attributes\Test;
use Tests\Browser\Pages\ConversionCreatePage;
use Tests\Browser\Pages\ConversionsIndexPage;
use Tests\DuskTestCase;
use Throwable;

class ConversionsTest extends DuskTestCase
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

    /**
     * @throws Throwable
     */
    #[Test]
    public function itShowsAllTheAvailableConversions(): void
    {
        $conversion = Conversion::factory()->create(['amount' => 100, 'from_currency_id' => CurrencyEnum::JPY->value]);

        $this->browse(function (Browser $browser) use ($conversion) {
            $browser->visit(new ConversionsIndexPage);
            $browser->assertPathIs((new ConversionsIndexPage)->url())
                ->assertDontSee('No new conversions to show.')
                ->assertPresent('@delete_conversion_'.$conversion->id)
                ->assertSee('Delete');
        });
    }

    /**
     * @throws Throwable
     */
    #[Test]
    public function itCreatesANewConversion(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new ConversionsIndexPage);

            if ($browser->seeLink('New conversion')) {
                $browser->clickLink('New conversion')
                    ->waitForLocation((new ConversionCreatePage)->url())
                    ->on(new ConversionCreatePage)
                    ->createNewConversion(69, 100.00)
                    ->on(new ConversionsIndexPage)
                    ->assertPathIs((new ConversionsIndexPage)->url())
                    ->assertSee('100')
                    ->assertSee('JPY')
                    ->assertSee('8238.9');
            }
        });
    }

    /**
     * @throws Throwable
     */
    #[Test]
    public function itCreatesANewConversionWithEmptyFields(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(new ConversionCreatePage)
                ->press('@save')
                ->assertPathIs((new ConversionCreatePage)->url())
                ->assertSee('The amount field is required.');
        });
    }

    /**
     * @throws Throwable
     */
    #[Test]
    public function itDeletesAGivenConversion(): void
    {
        $conversion = Conversion::factory()->create([
            'amount' => '100.00',
            'from_currency_id' => CurrencyEnum::JPY->value,
        ]);

        $this->browse(function (Browser $browser) use ($conversion) {
            $browser->visit(new ConversionsIndexPage)
                ->assertDontSee('No new conversions to show.')
                ->click('@delete_conversion_'.$conversion->id)
                ->on(new ConversionsIndexPage)
                ->assertSee('No new conversions to show.');
        });
    }
}
