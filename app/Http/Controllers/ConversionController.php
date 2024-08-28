<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreConversionRequest;
use App\Models\Conversion;
use App\Services\ConversionService;
use Exception;

class ConversionController extends Controller
{
    private ConversionService $conversionService;

    public function __construct(ConversionService $conversionService)
    {
        $this->conversionService = $conversionService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('conversion.index')
            ->with('conversions', $this->conversionService->getAllConversions());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currencies = $this->conversionService->getCurrenciesWithRates();

        return view('conversion.create')
            ->with('currencies', $currencies);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConversionRequest $request)
    {
        if ($this->conversionService->storeConversion($request->validated()) === false) {
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
            $this->conversionService->deleteConversion($conversion);
        } catch (Exception $e) {
            return back()->with('error', 'Conversion could not be deleted');
        }

        return redirect()->route('conversions');
    }
}
