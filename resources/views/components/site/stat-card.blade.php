@props([
    'icon',
    'iconClass' => 'bg-soft-green text-success',
    'target',
    'plus' => '+',
    'label' => null,
    'col' => 'col-6 col-lg-3',
])

<div class="{{ $col }}">
  <div class="stat-card">
    <div class="stat-icon {{ $iconClass }}">
      <i class="{{ $icon }}"></i>
    </div>
    <div class="stat-number-wrapper">
      <span class="stat-number" data-target="{{ $target }}">0</span>
      <span class="stat-plus">{{ $plus }}</span>
    </div>
    <div class="stat-label">{{ $label ?? $slot }}</div>
  </div>
</div>
