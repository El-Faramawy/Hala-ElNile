@props([
    'image',
    'alt' => '',
    'location',
    'locationIcon' => 'fa-solid fa-landmark me-1',
    'seal' => 'عقد دائم موثق',
    'sealIcon' => 'fa-solid fa-check-double me-1',
    'avatar',
    'avatarAlt' => '',
    'name',
    'subtitle',
    'quote' => null,
    'contractCode',
    'rating' => 5,
    'inSlide' => true,
])

@if($inSlide)
<div class="swiper-slide">
@endif
  <div class="traveler-story-card h-100">
    <div class="traveler-img-box">
      <img src="{{ $image }}" alt="{{ $alt }}">
      <span class="traveler-location-chip"><i class="{{ $locationIcon }}"></i> {{ $location }}</span>
      <span class="traveler-verified-seal"><i class="{{ $sealIcon }}"></i> {{ $seal }}</span>
    </div>
    <div class="p-4 d-flex flex-column flex-grow-1">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="traveler-avatar-wrapper">
          <img src="{{ $avatar }}" class="traveler-avatar-img" alt="{{ $avatarAlt ?: $name }}">
          <span class="traveler-avatar-badge" title="مسافر موثق"><i class="fa-solid fa-check"></i></span>
        </div>
        <div>
          <h6 class="fw-bold text-dark mb-0">{{ $name }}</h6>
          <small class="text-muted">{{ $subtitle }}</small>
        </div>
      </div>
      <p class="text-secondary small leading-relaxed mb-3">
        {{ $quote ?? $slot }}
      </p>
      <div class="d-flex justify-content-between align-items-center pt-2 mt-auto border-top">
        <span class="badge bg-light text-primary border">{{ $contractCode }}</span>
        <span class="text-warning small">
          @for($ratingIndex = 0; $ratingIndex < $rating; $ratingIndex++)
            <i class="fa-solid fa-star"></i>
          @endfor
        </span>
      </div>
    </div>
  </div>
@if($inSlide)
</div>
@endif
