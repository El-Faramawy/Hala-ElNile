@props([
    'id',
    'label',
    'type' => 'text',
    'placeholder' => null,
    'required' => false,
    'wrapperClass' => 'mb-3',
    'inputClass' => '',
    'value' => null,
])

<div class="{{ $wrapperClass }}">
  <label for="{{ $id }}" class="form-label fw-bold small text-dark">{{ $label }}</label>
  <input
    type="{{ $type }}"
    id="{{ $id }}"
    class="form-control rounded-3 {{ $inputClass }}"
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($value !== null) value="{{ $value }}" @endif
    @if($required) required @endif
    {{ $attributes }}
  >
</div>
