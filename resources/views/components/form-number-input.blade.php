@props(['id', 'name', 'label', 'value', 'placeholder' => '0.00', 'step' => '0.01'])

<div class="sm:col-span-2">
    <label for="{{ $id }}" class="block text-sm font-medium leading-6 text-gray-900">
        {{ $label }}
    </label>
    <div class="relative mt-2 rounded-md shadow-sm">
        <input type="number"
               name="{{ $name }}"
               id="{{ $id }}"
               class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300
               placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6
               @error($name) text-red-600 ring-red-300 @enderror"
               placeholder="{{ $placeholder }}"
               value="{{ $value }}"
               step="{{ $step }}"
               dusk="{{ $name }}"
        >
    </div>
    @error($name)
    <div class="block text-sm font-medium leading-6 text-red-600">{{ $message }}</div>
    @enderror
</div>
