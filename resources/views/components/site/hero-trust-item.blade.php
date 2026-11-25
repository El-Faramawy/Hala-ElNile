@props([
    'icon',
    'text' => null,
])

<div class="hero-trust-item">
  <i class="{{ $icon }}"></i>
  <span>{{ $text ?? $slot }}</span>
</div>
