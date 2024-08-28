<?php

namespace Tests\Feature\Http\Controllers;

use App\Enums\Currency as CurrencyEnum;
use App\Models\Conversion;
use App\Models\Currency;
use App\Services\ConversionService;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ConversionControllerTest extends TestCase
{
    use RefreshDatabase;

    private ConversionService $conversionService;

    #[Test]
    public function it_shows_all_the_conversions_available(): void
    {
        $response = $this->get(route('conversions'));

        $response->assertStatus(200);
        $response->assertViewHas('conversions');
    }

    #[Test]
    public function it_shows_the_form_to_create_a_new_conversion(): void
    {
        $response = $this->get(route('conversion.create'));

        $response->assertStatus(200);
        $response->assertViewHas('currencies');
    }

    #[Test]
    public function it_stores_a_new_conversion(): void
    {
        $data = [
            'from_currency_id' => 69,
            'amount' => 100,
        ];

        $response = $this->post(route('conversion.store'), $data);

        $response->assertRedirect(route('conversions'));
        $this->assertDatabaseHas('conversions', [
            'from_currency_id' => 69,
            'amount' => 100,
        ]);
    }

    #[Test]
    public function it_destroys_a_given_conversion(): void
    {
        $conversion = Conversion::factory()->create();

        $response = $this->delete(route('conversion.destroy', $conversion));

        $response->assertRedirect(route('conversions'));
        $this->assertDatabaseMissing('conversions', ['id' => $conversion->id]);
    }

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->conversionService = $this->app->make(ConversionService::class);
        array_map(static function (CurrencyEnum $currency) {
            Currency::factory()->create(['id' => $currency->value, 'code' => $currency->name]);
        }, CurrencyEnum::cases());
    }
}