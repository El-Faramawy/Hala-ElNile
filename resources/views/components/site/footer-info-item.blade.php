@props([
    'label',
    'value' => null,
])

<strong class="text-white">{{ $label }}:</strong> {{ $value ?? $slot }}<br>
