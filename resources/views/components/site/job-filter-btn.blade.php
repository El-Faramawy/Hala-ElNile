@props([
    'filter',
    'icon' => null,
    'label' => null,
    'active' => false,
])

<button type="button" class="job-filter-btn {{ $active ? 'active' : '' }}" data-filter="{{ $filter }}" {{ $attributes }}>
  @if($icon)<i class="{{ $icon }}"></i>@endif
  {{ $label ?? $slot }}
</button>
