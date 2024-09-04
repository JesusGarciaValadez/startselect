<x-layout>
    <x-slot:title>
        StartSelect coding test | Create operation
    </x-slot>

    <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-56 lg:px-8">
        <div class="mx-auto max-w-2xl lg:text-center">
            <h2 class="text-base font-semibold leading-7 text-indigo-600">
                Startselect
            </h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                Coding test
            </p>
            <p class="mt-6 text-lg leading-8 text-gray-600">
                Coding test for the PHP Developer role.
            </p>
        </div>
        <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
            <form method="POST"
                  action="{{ route('operation.store') }}"
            >
                @csrf
                <div class="space-y-12 sm:space-y-16">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">
                            Operation
                        </h2>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">
                            Different operations with money.
                        </p>
                        <div class="border-b border-gray-900/10 pb-12">
                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <x-form-select id="currency_id"
                                               name="currency_id"
                                               :options="$currencies"
                                               label="Currency:"
                                               selected="{{ old('currency_id') }}"
                                />
                            </div>
                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <x-form-number-input id="operand1"
                                                     name="operand1"
                                                     label="Operand 1"
                                                     :value="old('operand1')"
                                />
                                <div class="sm:col-span-1">
                                    <label for="operation" class="block text-sm font-medium leading-6 text-gray-900">
                                        Operation
                                    </label>
                                    <div class="mt-2">
                                        <select id="operation"
                                                name="operation"
                                                value="{{ old('operation') }}"
                                                class="block w-full rounded-md border-0 py-1.5 text-gray-900
                                                text-center shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2
                                                focus:ring-inset focus:ring-indigo-600 sm:max-w-xs sm:text-sm
                                                sm:leading-6"
                                                dusk="operation"
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
                                                <option value="{{ $operation }}" {{
                                                    $operation === old('operation')
                                                        ? 'selected'
                                                        : ($loop->first ? 'selected' : '')
                                                }}>
                                                    {{ $operationSign }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('operation')
                                    <div class="block text-sm font-medium leading-6 text-red-600">{{ $message }}</div>
                                    @enderror
                                </div>
                                <x-form-number-input id="operand2"
                                                     name="operand2"
                                                     label="Operand 2"
                                                     :value="old('operand2')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{ route('operations') }}"
                       class="text-sm font-semibold leading-6 text-gray-900"
                       dusk="cancel"
                    >
                        Cancel
                    </a>
                    <button type="submit"
                            class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold
                            text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2
                            focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            dusk="save"
                    >
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
