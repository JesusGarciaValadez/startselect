<x-layout>
    <x-slot:title>
        StartSelect coding test | Create conversion
    </x-slot>

    <div class="mx-auto mt-32 max-w-7xl px-6 sm:mt-56 lg:px-8">
        <div class="mx-auto max-w-2xl lg:text-center">
            <h2 class="text-base font-semibold leading-7 text-indigo-600">Startselect</h2>
            <p class="mt-2 text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Coding test</p>
            <p class="mt-6 text-lg leading-8 text-gray-600">Coding test for the PHP Developer role.</p>
        </div>
        <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-4xl">
            <form method="POST"
                  action="{{ route('conversion.store') }}"
            >
                @csrf
                <div class="space-y-12 sm:space-y-16">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Conversion</h2>
                        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-600">
                            Money currency conversions from different currencies to EUR.
                        </p>
                        <div class="border-b border-gray-900/10 pb-12">
                            <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                                <x-form-select id="from_currency_id"
                                               name="from_currency_id"
                                               label="Convert from currency to EUR:"
                                               :options="$currencies"
                                               :selected="old('from_currency_id')"
                                />
                                <x-form-number-input id="amount"
                                                     name="amount"
                                                     label="Amount to convert:"
                                                     :value="old('amount')"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <a href="{{ route('conversions') }}"
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
