<?php

namespace App\Http\Controllers;

use App\Enums\Currency as CurrencyCatalog;
use App\Http\Requests\StoreConversionRequest;
use App\Models\Conversion;
use App\ValueObjects\MoneyValueObject;
use Illuminate\Support\Collection;

class ConversionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('conversion.index')
            ->with('conversions', Conversion::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $exchangeRates = $this->getExchangeRates();
        $currencies = Collection::make(array_map(static function(string $id, string $rate) {
            if (CurrencyCatalog::from($id)) {
                return [
                    'id' => $id,
                    'rate' => $rate,
                    'name' => CurrencyCatalog::from($id)->name,
                    'symbol' => CurrencyCatalog::from($id)->symbol(),
                ];
            }
        }, array_keys($exchangeRates), array_values($exchangeRates)));

        return view('conversion.create')
            ->with('currencies', $currencies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConversionRequest $request)
    {
        $fromCurrency = CurrencyCatalog::from($request->validated('from_currency_id'));
        $toCurrency = CurrencyCatalog::EUR;
        $amount = New MoneyValueObject($request->validated('amount'), $fromCurrency->name);
        $payload = [
            'from_currency_id' => $fromCurrency->value,
            'to_currency_id' => $toCurrency->value,
            'amount' => $amount->getAmount(),
            'conversion' => $amount->convertTo($fromCurrency->name)->getAmount(),
        ];

        if (!Conversion::create($payload)) {
            return back()->withInput();
        }

        return redirect()->route('conversions');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conversion $conversion)
    {
        try {
            $conversion->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Conversion could not be deleted');
        }

        return redirect()->route('conversions');
    }

    private function getExchangeRates(): array
    {
        include app_path('/Helpers/exchange_rates.php');
        return $rates;
    }
}
