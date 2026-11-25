@props([
    'icon',
    'title',
    'description' => null,
    'stagger' => 1,
    'col' => 'col-md-6 col-lg-4',
])

<div class="{{ $col }}">
  <div class="feature-card reveal-on-scroll stagger-{{ $stagger }}">
    <div class="feature-icon-box">
      <i class="{{ $icon }}"></i>
    </div>
    <h5 class="fw-bold text-dark mb-3">{{ $title }}</h5>
    <p class="text-muted small leading-relaxed mb-0">
      {{ $description ?? $slot }}
    </p>
  </div>
</div>
