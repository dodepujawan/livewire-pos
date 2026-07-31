@props([
    'label' => null,
    'name',
])

<div wire:ignore>
    @if($label)
        <label
            for="{{ $name }}"
            class="mb-2 block text-sm font-medium text-gray-700"
        >
            {{ $label }}
        </label>
    @endif

    <select
        id="{{ $name }}"
        {{ $attributes }}
        class="w-full rounded-lg border-gray-300"
    >
        {{ $slot }}
    </select>

    @error($name)
        <p class="mt-1 text-sm text-red-600">
            {{ $message }}
        </p>
    @enderror
</div>

@once
<script>
document.addEventListener('livewire:init', () => {

    Livewire.hook('morph.updated', ({ el }) => {

        el.querySelectorAll('select[data-tom-select]').forEach(select => {

            if (select.tomselect) {
                return;
            }

            new TomSelect(select, {
                create: false,
                allowEmptyOption: true,
                searchField: ['text'],
                maxOptions: 500,
            });

        });

    });

});
</script>
@endonce
