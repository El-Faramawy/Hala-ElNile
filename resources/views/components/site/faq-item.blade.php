@props([
    'id',
    'question',
    'answer' => null,
    'parent' => '#faqAccordion',
    'show' => false,
    'icon' => 'fa-solid fa-circle-question text-success me-2',
])

<div class="accordion-item shadow-sm">
  <h2 class="accordion-header">
    <button class="accordion-button {{ $show ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $id }}">
      <i class="{{ $icon }}"></i>
      {{ $question }}
    </button>
  </h2>
  <div id="{{ $id }}" class="accordion-collapse collapse {{ $show ? 'show' : '' }}" data-bs-parent="{{ $parent }}">
    <div class="accordion-body">
      {{ $answer ?? $slot }}
    </div>
  </div>
</div>
