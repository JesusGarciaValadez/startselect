<?php

namespace App\Http\Controllers;

use App\Enums\Currency;
use App\Http\Requests\StoreOperationRequest;
use App\Models\Operation;
use App\ValueObjects\MoneyValueObject;

class OperationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('operation.index')
            ->with('operations', Operation::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('operation.create')
            ->with('currencies', Currency::cases())
            ->with('operations', ['add', 'subtract', 'multiply', 'divide', 'min', 'max', 'avg', 'total', 'discount']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOperationRequest $request)
    {
        $currency = Currency::from($request->validated('currency_id'));
        $operand1 = New MoneyValueObject($request->validated('operand1'), $currency->name);
        $operand2 = New MoneyValueObject($request->validated('operand2'), $currency->name);
        $result = match ($request->validated('operation')) {
            'add' => $operand1->add($operand2->getAmount())->getAmount(),
            'subtract' => $operand1->subtract($operand2->getAmount())->getAmount(),
            'multiply' => $operand1->multiply($operand2->getAmount())->getAmount(),
            'divide' => $operand1->divide($operand2->getAmount())->getAmount(),
            'min' => MoneyValueObject::min([$operand1, $operand2])->getAmount(),
            'max' => MoneyValueObject::max([$operand1, $operand2])->getAmount(),
            'avg' => MoneyValueObject::average([$operand1, $operand2])->getAmount(),
            'total' => MoneyValueObject::total([$operand1, $operand2])->getAmount(),
            'discount' => $operand1->applyDiscount($operand2->getAmount())->getAmount(),
        };
        $payload = array_merge($request->validated(), ['result' => $result]);

        if (!Operation::create($payload)) {
            return back()->withInput();
        }

        return redirect()->route('operations');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Operation $operation)
    {
        try {
            $operation->delete();
        } catch (\Exception $e) {
            return back()->with('error', 'Operation could not be deleted');
        }

        return redirect()->route('operations');
    }
}
