@props([
    'href' => '#',
    'icon',
    'label',
    'target' => '_blank',
])

<a href="{{ $href }}" @if($target) target="{{ $target }}" rel="noopener noreferrer" @endif class="btn btn-outline-light btn-sm rounded-circle footer-social-btn" aria-label="{{ $label }}" {{ $attributes }}>
  <i class="{{ $icon }}"></i>
</a>
