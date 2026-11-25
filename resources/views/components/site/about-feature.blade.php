@props([
    'title',
    'description' => null,
    'icon' => 'fa-solid fa-check-circle text-success fs-5',
    'stagger' => 1,
    'col' => 'col-sm-6',
])

<div class="{{ $col }}">
  <div class="about-feature-box reveal-on-scroll stagger-{{ $stagger }}">
    <div class="d-flex align-items-center gap-2 mb-2">
      <i class="{{ $icon }}"></i>
      <h6 class="fw-bold mb-0">{{ $title }}</h6>
    </div>
    <p class="small text-muted mb-0">{{ $description ?? $slot }}</p>
  </div>
</div>
