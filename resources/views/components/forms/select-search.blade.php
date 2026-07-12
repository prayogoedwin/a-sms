@props([
    'name',
    'id' => null,
    'label' => null,
    'placeholder' => 'Cari...',
    'required' => false,
    'submitOnChange' => false,
])

@php
    $selectId = $id ?? $name;
@endphp

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if($label)
        <label for="{{ $selectId }}" class="block text-sm text-gray-600 dark:text-gray-400 mb-1">{{ $label }}</label>
    @endif
    <select
        id="{{ $selectId }}"
        name="{{ $name }}"
        data-select-search
        data-placeholder="{{ $placeholder }}"
        data-submit-on-change="{{ $submitOnChange ? '1' : '0' }}"
        @if($required) required @endif
        class="w-full"
    >
        {{ $slot }}
    </select>
</div>

@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.bootstrap5.min.css" />
        <style>
            .ts-wrapper.single .ts-control {
                border-radius: 0.5rem;
                min-height: 2.5rem;
                padding: 0.375rem 0.75rem;
                border-color: #d1d5db;
                background: #f9fafb;
            }

            html.dark .ts-wrapper.single .ts-control {
                background: #374151;
                border-color: #4b5563;
                color: #f3f4f6;
            }

            html.dark .ts-dropdown {
                background: #1f2937;
                border-color: #4b5563;
                color: #f3f4f6;
            }

            html.dark .ts-dropdown .option {
                color: #f3f4f6;
            }

            html.dark .ts-dropdown .active {
                background: #374151;
                color: #fff;
            }

            html.dark .ts-wrapper .ts-control input {
                color: #f3f4f6;
            }
        </style>
    @endpush
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('[data-select-search]').forEach(function(el) {
                    if (el.tomselect) {
                        return;
                    }

                    var submitOnChange = el.dataset.submitOnChange === '1';
                    var form = el.closest('form');

                    new TomSelect(el, {
                        placeholder: el.dataset.placeholder || 'Cari...',
                        allowEmptyOption: true,
                        maxOptions: null,
                        create: false,
                        onChange: function() {
                            if (submitOnChange && form) {
                                form.submit();
                            }
                        },
                    });
                });
            });
        </script>
    @endpush
@endonce
