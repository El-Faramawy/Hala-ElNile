@props([
    'href',
    'icon' => 'fa-solid fa-angle-left',
    'label' => null,
])

<li>
  <a href="{{ $href }}" {{ $attributes }}>
    <i class="{{ $icon }}"></i> {{ $label ?? $slot }}
  </a>
</li>
