@props([
    'href',
    'active' => false,
    'class' => '',
    'icon' => null,
])

<li class="nav-item">
  <a href="{{ $href }}" {{ $attributes->merge(['class' => 'nav-link' . ($active ? ' active' : '') . ($class ? ' ' . $class : '')]) }}>
    @if($icon)<i class="{{ $icon }}"></i>@endif
    {{ $slot }}
  </a>
</li>
