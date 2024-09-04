@php use App\Enums\Currency; @endphp
@props(['id', 'name', 'options', 'selected' => null, 'label'])

<div class="sm:col-span-2 sm:col-start-1">
    <label for="{{ $id }}" class="block text-sm font-medium leading-6 text-gray-900">
        {{ $label }}
    </label>
    <select id="{{ $id }}"
            name="{{ $name }}"
            class="mt-2 block w-full rounded-md border-0 py-1.5 pl-3 pr-10 text-gray-900 ring-1 ring-inset
            ring-gray-300 focus:ring-2 focus:ring-indigo-600 sm:text-sm sm:leading-6"
            value="{{ $selected }}"
            dusk="{{ $name }}"
    >
        @foreach($options as $option)
            @if($option instanceof Currency)
                <option value="{{ $option->value }}" {{
                    (string) $option->value === (string) $selected
                        ? 'selected'
                        : ($option->name === 'EUR' && (string) !$selected ? 'selected' : '')
                }}>
                    {{ $option->name }} - {{ $option->symbol() }}
                </option>
            @else
                <option value="{{ $option['id'] }}" {{ $option['id'] === $selected ? 'selected' : '' }}>
                    {{ $option['name'] }} - {{ $option['symbol'] }}
                </option>
            @endif
        @endforeach
    </select>
    @error($name)
    <div class="block text-sm font-medium leading-6 text-red-600">{{ $message }}</div>
    @enderror
</div>
