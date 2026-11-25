@props([
    'image',
    'badgeText' => null,
    'badgeIcon' => null,
    'badgeClass' => '',
    'title' => null,
    'description' => null,
])

<div class="swiper-slide hero-slide" style="background-image: url('{{ $image }}');">
  <div class="hero-overlay"></div>
  <div class="container hero-content">
    <div class="row align-items-center">
      <div class="col-lg-9 col-xl-8">
        @if($badgeText || isset($badge))
          <span class="hero-badge {{ $badgeClass }}">
            @if(isset($badge))
              {{ $badge }}
            @else
              @if($badgeIcon)<i class="{{ $badgeIcon }}"></i>@endif
              {{ $badgeText }}
            @endif
          </span>
        @endif

        @if($title)
          <h1 class="hero-title">
            {!! $title !!}
          </h1>
        @endif

        @if($description)
          <p class="hero-desc">
            {{ $description }}
          </p>
        @endif

        @if(isset($actions))
          <div class="d-flex flex-wrap gap-3">
            {{ $actions }}
          </div>
        @endif

        @if(isset($trustItems))
          <div class="hero-trust-row">
            {{ $trustItems }}
          </div>
        @endif

        {{ $slot }}
      </div>
    </div>
  </div>
</div>
