@props([
    'step',
    'icon',
    'title',
    'description' => null,
    'stagger' => 1,
    'col' => 'col-md-6 col-lg',
])

<div class="{{ $col }}">
  <div class="step-box reveal-on-scroll stagger-{{ $stagger }}">
    <div class="step-number-badge">{{ $step }}</div>
    <div class="text-primary fs-3 mb-3">
      <i class="{{ $icon }}"></i>
    </div>
    <h5 class="fw-bold text-dark mb-2">{{ $title }}</h5>
    <p class="small text-muted mb-0">
      {{ $description ?? $slot }}
    </p>
  </div>
</div>
