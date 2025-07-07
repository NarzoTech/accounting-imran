@props([
    'name' => '',
    'label' => '',
    'active_value' => '1',
    'inactive_value' => '0',
    'checked' => false,
])


<label class="switch switch-square switch-lg">
    <input type="hidden" class="switch-input is-valid" name="{{ $name }}" value="{{ $inactive_value }}" />
    <input type="checkbox" class="switch-input is-valid" name="{{ $name }}" value="{{ $active_value }}"
        {{ $checked ? 'checked' : '' }} />
    <span class="switch-toggle-slider">
        <span class="switch-on"><i class="icon-base bx bx-check"></i></span>
        <span class="switch-off"><i class="icon-base bx bx-x"></i></span>
    </span>
    @if ($label)
        <span class="switch-label text-dark">{{ $label }}</span>
    @endif
</label>
