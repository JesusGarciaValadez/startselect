<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOperationRequest;
use App\Models\Operation;
use App\Services\OperationService;
use Exception;

class OperationController extends Controller
{
    private OperationService $operationService;

    public function __construct(OperationService $operationService)
    {
        $this->operationService = $operationService;
    }

    public function index()
    {
        return view('operation.index')
            ->with('operations', $this->operationService->getAllOperations());
    }

    public function create()
    {
        return view('operation.create')
            ->with('currencies', $this->operationService->getCurrencies())
            ->with('operations', $this->operationService->getOperations());
    }

    public function store(StoreOperationRequest $request)
    {
        if (! $this->operationService->storeOperation($request->validated())) {
            return back()->withInput();
        }

        return redirect()->route('operations');
    }

    public function destroy(Operation $operation)
    {
        try {
            $this->operationService->deleteOperation($operation);
        } catch (Exception $e) {
            // Handle exception
        }

        return redirect()->route('operations');
    }
}
