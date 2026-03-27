@props([
    'tableId',
    'orderColumn' => 0,
])

@once
    @push('styles')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css" />
        <style>
            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter,
            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
            }

            html.dark .dataTables_wrapper .dataTables_length,
            html.dark .dataTables_wrapper .dataTables_filter,
            html.dark .dataTables_wrapper .dataTables_info,
            html.dark .dataTables_wrapper .dataTables_paginate {
                color: #d1d5db;
            }

            html.dark .dataTables_wrapper .dataTables_filter input {
                background: #1f2937;
                border: 1px solid #4b5563;
                color: #f3f4f6;
                border-radius: 0.25rem;
                padding: 0.25rem 0.5rem;
            }

            html.dark table.dataTable {
                color: #e5e7eb;
            }

            html.dark table.dataTable thead th,
            html.dark table.dataTable tbody td {
                border-color: #374151 !important;
            }

            html.dark table.dataTable thead th {
                background: #111827;
            }

            html.dark table.dataTable tbody tr {
                background: #1f2937;
            }

            html.dark table.dataTable tbody tr:nth-child(even) {
                background: #1a2230;
            }

            html.dark .dataTables_wrapper .dataTables_paginate .paginate_button {
                color: #d1d5db !important;
            }

            html.dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
                background: #374151 !important;
                border-color: #4b5563 !important;
                color: #fff !important;
            }

            html.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            html.dark .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
                background: #374151 !important;
                border-color: #4b5563 !important;
                color: #fff !important;
            }
        </style>
    @endpush
    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    @endpush
@endonce

@push('scripts')
    <script>
        $(function() {
            var tid = @json($tableId);
            if (!document.getElementById(tid)) {
                return;
            }
            $('#' + tid).DataTable({
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Menampilkan 0 sampai 0 dari 0 data',
                    infoFiltered: '(disaring dari _MAX_ data)',
                    zeroRecords: 'Tidak ada data yang cocok',
                    emptyTable: 'Tidak ada data',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Selanjutnya',
                        previous: 'Sebelumnya'
                    },
                },
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'Semua']
                ],
                order: [
                    [{{ (int) $orderColumn }}, 'asc']
                ],
                columnDefs: [{
                    orderable: false,
                    targets: -1
                }],
            });
        });
    </script>
@endpush
