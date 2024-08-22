@php use App\Enums\Currency; @endphp
<x-layout>
    <x-slot:title>
        StartSelect coding test
    </x-slot>


    <!-- Feature section -->
    <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-56 lg:px-8">
        <div class="mx-auto max-w-2xl lg:text-center">
            <h2 class="text-base font-semibold leading-7 text-indigo-600">Startselect</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Coding test</p>
            <p class="mt-6 text-lg leading-8 text-gray-600">Coding test for the PHP Developer role.</p>
        </div>
        <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
                <div class="space-y-12 sm:space-y-16">
                    <div>
                        <div class="px-4 sm:px-6 lg:px-8">
                            <div class="sm:flex sm:items-center">
                                <div class="sm:flex-auto">
                                    <h1 class="text-base font-semibold leading-6 text-gray-900">Operations</h1>
                                    <p class="mt-2 text-sm text-gray-700">A table of money operations in Euros.</p>
                                </div>
                                <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                                    <a href="{{ route('operation.create') }}"
                                       class="block rounded-md bg-indigo-600 px-3 py-2 text-center text-sm
                                       font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline
                                       focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">New
                                        operation</a>
                                </div>
                            </div>
                            <div class="mt-8 flow-root">
                                <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                                    <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                        @if(count($operations) <= 0)
                                            <h2 class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">No new operations to show.</h2>
                                        @else
                                            @error('error')
                                            <div class="block text-sm font-medium leading-6 text-red-600">{{ $message }}</div>
                                            @enderror
                                            <table class="min-w-full divide-y divide-gray-300">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left
                                                        text-sm font-semibold text-gray-900">Operand 1
                                                        </th>
                                                        <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left
                                                        text-sm font-semibold text-gray-900"></th>
                                                        <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left
                                                        text-sm font-semibold text-gray-900">Operand 2
                                                        </th>
                                                        <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left
                                                        text-sm font-semibold text-gray-900">Result
                                                        </th>
                                                        <th scope="col" class="whitespace-nowrap px-2 py-3.5 text-left
                                                        text-sm font-semibold text-gray-900">
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-200 bg-white">
                                                @foreach ($operations as $operation)
                                                    @php
                                                        $operationSign = match ($operation->operation) {
                                                            'add' => '+',
                                                            'subtract' => '-',
                                                            'multiply' => 'x',
                                                            'divide' => '/',
                                                            'min' => 'min',
                                                            'max' => 'max',
                                                            'avg' => 'average',
                                                            'total' => 'total',
                                                            'discount' => 'discount %',
                                                        };
                                                        $currencySymbol = Currency::from
                                                        ($operation->currency->id)->symbol()
                                                    @endphp
                                                    <tr>
                                                        <td class="whitespace-nowrap px-2 py-2 text-sm
                                                        text-gray-900">
                                                            {{ $currencySymbol }} {{$operation->operand1 }}
                                                        </td>
                                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                                                            {{ $operationSign }}
                                                        </td>
                                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                                                            @switch($operation->operation)
                                                                @case('add')
                                                                @case('subtract')
                                                                @case('min')
                                                                @case('max')
                                                                @case('avg')
                                                                @case('total')
                                                                    {{ Currency::from($operation->currency->id)->symbol() }}
                                                                    @break
                                                                @default
                                                            @endswitch
                                                            {{$operation->operand2 }}
                                                        </td>
                                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                                                            {{ $currencySymbol }} {{ $operation->result }}
                                                        </td>
                                                        <td class="whitespace-nowrap px-2 py-2 text-sm text-gray-500">
                                                            <form method="POST"
                                                                  action="{{ route('operation.destroy', ['operation'
                                                                  => $operation]) }}"
                                                            >
                                                                @method('DELETE')
                                                                @csrf
                                                                <button type="submit" class="inline-flex
                                                                justify-center rounded-md bg-red-600 px-3 py-2
                                                                text-sm font-semibold text-white shadow-sm
                                                                hover:bg-red-500 focus-visible:outline
                                                                focus-visible:outline-2
                                                                focus-visible:outline-offset-2
                                                                focus-visible:outline-red-600">Delete</button>
                                                            </form>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</x-layout>
