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

<div {{ $attributes->merge(['class' => 'w-full relative z-20']) }}>
    @if($label)
        <label for="{{ $selectId }}" class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $label }}</label>
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
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.default.min.css" />
        <style>
            /* Wrapper */
            .ts-wrapper.select-search {
                width: 100%;
            }

            .ts-wrapper.select-search.single .ts-control,
            .ts-wrapper.select-search.single.focus .ts-control {
                border-radius: 0.5rem;
                min-height: 2.625rem;
                padding: 0.5rem 2.25rem 0.5rem 1rem;
                border: 1px solid #d1d5db;
                background-color: #f9fafb;
                box-shadow: none;
                font-size: 0.875rem;
                line-height: 1.25rem;
                color: #374151;
            }

            .ts-wrapper.select-search.single.focus .ts-control {
                border-color: transparent;
                outline: 2px solid #3b82f6;
                outline-offset: 0;
            }

            .ts-wrapper.select-search .ts-control input {
                font-size: 0.875rem;
                color: #374151;
            }

            .ts-wrapper.select-search .ts-control input::placeholder {
                color: #9ca3af;
            }

            .ts-wrapper.select-search.single .ts-control::after {
                border-color: #6b7280 transparent transparent;
                right: 1rem;
            }

            /* Dropdown — rendered on body via dropdownParent */
            .ts-dropdown.select-search-dropdown {
                z-index: 9999 !important;
                margin-top: 0.25rem;
                border-radius: 0.5rem;
                border: 1px solid #e5e7eb;
                background-color: #ffffff !important;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.08);
                overflow: hidden;
            }

            .ts-dropdown.select-search-dropdown .ts-dropdown-content {
                max-height: 16rem;
                overflow-y: auto;
                background-color: #ffffff;
            }

            .ts-dropdown.select-search-dropdown .option {
                padding: 0.625rem 1rem;
                font-size: 0.875rem;
                color: #374151;
                background-color: #ffffff;
                border-bottom: 1px solid #f3f4f6;
            }

            .ts-dropdown.select-search-dropdown .option:last-child {
                border-bottom: none;
            }

            .ts-dropdown.select-search-dropdown .option.active {
                background-color: #eff6ff;
                color: #1d4ed8;
            }

            .ts-dropdown.select-search-dropdown .option:hover,
            .ts-dropdown.select-search-dropdown .option.active:hover {
                background-color: #dbeafe;
                color: #1e40af;
            }

            .ts-dropdown.select-search-dropdown .no-results {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
                color: #6b7280;
                background-color: #ffffff;
            }

            /* Dark mode */
            html.dark .ts-wrapper.select-search.single .ts-control,
            html.dark .ts-wrapper.select-search.single.focus .ts-control {
                background-color: #374151;
                border-color: #4b5563;
                color: #f3f4f6;
            }

            html.dark .ts-wrapper.select-search.single.focus .ts-control {
                outline-color: #3b82f6;
            }

            html.dark .ts-wrapper.select-search .ts-control input {
                color: #f3f4f6;
            }

            html.dark .ts-wrapper.select-search .ts-control input::placeholder {
                color: #9ca3af;
            }

            html.dark .ts-wrapper.select-search.single .ts-control::after {
                border-color: #9ca3af transparent transparent;
            }

            html.dark .ts-dropdown.select-search-dropdown {
                border-color: #4b5563;
                background-color: #1f2937 !important;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.45);
            }

            html.dark .ts-dropdown.select-search-dropdown .ts-dropdown-content {
                background-color: #1f2937;
            }

            html.dark .ts-dropdown.select-search-dropdown .option {
                color: #f3f4f6;
                background-color: #1f2937;
                border-bottom-color: #374151;
            }

            html.dark .ts-dropdown.select-search-dropdown .option.active {
                background-color: #1e3a5f;
                color: #93c5fd;
            }

            html.dark .ts-dropdown.select-search-dropdown .option:hover,
            html.dark .ts-dropdown.select-search-dropdown .option.active:hover {
                background-color: #374151;
                color: #ffffff;
            }

            html.dark .ts-dropdown.select-search-dropdown .no-results {
                color: #9ca3af;
                background-color: #1f2937;
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
                        dropdownParent: 'body',
                        plugins: ['dropdown_input'],
                        wrapperClass: 'ts-wrapper select-search',
                        dropdownClass: 'ts-dropdown select-search-dropdown',
                        onChange: function(value) {
                            if (submitOnChange && form && value) {
                                form.submit();
                            }
                        },
                    });
                });
            });
        </script>
    @endpush
@endonce
