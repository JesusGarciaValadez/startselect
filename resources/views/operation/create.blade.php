<x-layout>
    <x-slot:title>
        StartSelect coding test | Create operation
    </x-slot>


    <!-- Feature section -->
    <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-56 lg:px-8">
        <div class="mx-auto max-w-2xl lg:text-center">
            <h2 class="text-base font-semibold leading-7 text-indigo-600">Startselect</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Coding test</p>
            <p class="mt-6 text-lg leading-8 text-gray-600">Coding test for the PHP Developer role.</p>
        </div>
        <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
            <form method="POST" action="{{ route('operation.store') }}">
                @csrf
                <div class="space-y-12 sm:space-y-16">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Operation</h2>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">Different operations with money.</p>

                        <div class="border-b border-gray-900/10 pb-12">
                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-2 sm:col-start-1">
                                    <label for="currency_id" class="block text-sm font-medium leading-6
                                    text-gray-900">Currency:</label>
                                    <select id="currency_id" name="currency_id" class="mt-2 block w-full rounded-md
                                    border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        @foreach($currencies as $currency)
                                            @if($currency->name === old('currency'))
                                                <option value="{{ $currency }}" selected aria-selected="true">
                                                    {{ $currency->name }} - {{ $currency->symbol() }}
                                                </option>
                                            @elseif($currency->name === 'EUR')
                                                <option value="{{ $currency }}" selected aria-selected="true">
                                                    {{ $currency->name }} - {{ $currency->symbol() }}
                                                </option>
                                            @else
                                                <option value="{{ $currency }}"  >
                                                    {{ $currency->name }} - {{ $currency->symbol() }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                @error('currency')
                                <div class="block text-sm font-medium leading-6 text-red-600">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <div class="sm:col-span-2">
                                    <label for="operand1" class="block text-sm font-medium leading-6
                                    text-gray-900">Operand 1</label>
                                    <div class="relative mt-2 rounded-md shadow-sm">
                                        <input type="number"
                                               name="operand1"
                                               id="operand1"
                                               class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20
                                                ring-1 ring-inset
                                               placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                               focus:ring-indigo-600 sm:text-sm sm:leading-6
                                               @error('operand1')
                                               text-red-600 ring-red-300
                                               @else
                                               text-gray-900 ring-gray-300
                                               @enderror"
                                               value="{{ old('operand1') }}"
                                               placeholder="0.00"
                                               step="0.01"
                                        >
                                    </div>
                                    @error('operand1')
                                    <div class="block text-sm font-medium leading-6 text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="sm:col-span-1">
                                    <label for="operation" class="block text-sm font-medium leading-6
                                    text-gray-900">Operation</label>
                                    <div class="mt-2">
                                        <select id="operation"
                                                name="operation"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900
                                                text-center
                                                shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm sm:leading-6"
                                        >
                                            @foreach($operations as $operation)
                                                @php
                                                    $operationSign = match ($operation) {
                                                        'add' => '+',
                                                        'subtract' => '-',
                                                        'multiply' => 'x',
                                                        'divide' => '/',
                                                        'min' => 'min',
                                                        'max' => 'max',
                                                        'avg' => 'average',
                                                        'total' => 'total',
                                                        'discount' => 'discount %',
                                                    }
                                                @endphp
                                                @if($loop->first)
                                                    <option value="{{ $operation }}" selected aria-selected="true">
                                                        {{ $operationSign }}
                                                    </option>
                                                @else
                                                    <option value="{{ $operation }}">
                                                        {{ $operationSign }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('operation')
                                    <div class="block text-sm font-medium leading-6 text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <div class="sm:col-span-2 sm:col-start-1">
                                        <label for="operand2" class="block text-sm font-medium leading-6
                                        text-gray-900">Operand 2</label>
                                        <div class="relative mt-2 rounded-md shadow-sm">
                                            <input type="number"
                                                   name="operand2"
                                                   id="operand2"
                                                   class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20
                                                   text-gray-900 ring-1 ring-inset ring-gray-300
                                                   placeholder:text-gray-400 focus:ring-2 focus:ring-inset
                                                   focus:ring-indigo-600 sm:text-sm sm:leading-6
                                                   @error('operand1')
                                                    text-red-600 ring-red-300
                                                   @else
                                                    text-gray-900 ring-gray-300
                                                   @enderror"
                                                   value="{{ old('operand2') }}"
                                                   placeholder="0.00"
                                                   step="0.01"
                                            >
                                        </div>
                                        @error('operand2')
                                        <div class="block text-sm font-medium leading-6 text-red-600">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{ route('operations') }}"
                       class="text-sm font-semibold leading-6 text-gray-900"
                    >
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
