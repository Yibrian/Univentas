@push('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.1/css/buttons.dataTables.min.css">
    <link href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <style>
        .dt-button {
            background-color: #B91C1C !important;
            color: #F9FAFB !important;
            border-radius: 0.375rem !important;
            margin-top: 20px !important;
            font-weight: bold;
            font-size: 12px !important;
        }

        .dt-button:hover {
            background-color: #941414 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.25rem 0.5rem;
            margin: 0 0.125rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            border-radius: 0.375rem;
            color: #1F2937;
            background-color: #F9FAFB;
            border-color: #D1D5DB;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }


        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: #E5E7EB;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background-color: #B91C1C;
            color: #F9FAFB;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .dataTables_wrapper .dataTables_paginate {
            float: right;
            margin-top: 20px;
        }

        .dataTables_info {
            margin-top: 20px;
            font-size: 15px;
        }

        .dataTables_wrapper .dataTables_length {
            float: left;
        }

        table.dataTable thead th {
            text-align: left;
        }

        table.dataTable {
            border-collapse: collapse;
        }

        .dataTables_wrapper .dataTables_length {
            margin-bottom: 1rem;
        }

        .dataTables_wrapper .dataTables_length label {
            font-weight: bold;
            font-size: 0.875rem;
            color: #1F2937;
        }

        .dataTables_wrapper .dataTables_length select {
            margin-left: 0.5rem;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            border-radius: 0.375rem;
            border: 1px solid #D1D5DB;
            color: #1F2937;
            background-color: #F9FAFB;
            transition: background-color 0.3s ease;
            margin-top: 5px;
        }

        .dataTables_wrapper .dataTables_length select:hover {
            background-color: #E5E7EB;
        }

        .dataTables_filter {
            position: relative;
            margin-right: 5px;

        }

        .dataTables_filter label {
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 0.875rem;
            color: #1F2937;
        }

        .dataTables_filter label input {
            margin-left: 0.5rem;
            font-size: 0.875rem;
            line-height: 1.25rem;
            border-radius: 0.375rem;
            border: 1px solid #D1D5DB;
            color: #1F2937;
            background-color: #F9FAFB;
            transition: background-color 0.3s ease;
            height: 30px;
        }

        .dataTables_filter label input:focus {
            background-color: #E5E7EB;
        }
    </style>
@endpush
@push('js')
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js" type="text/javascript"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js" type="text/javascript"></script>
    <script src="https://cdn.datatables.net/buttons/2.1.1/js/buttons.print.min.js" type="text/javascript" ></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>



    <script>
        $(document).ready(function() {
            function initializeDataTable() {
                $('#DataTable').DataTable().destroy();
                const locale = '{{ env('APP_LOCALE', 'en') }}';
                var words = ["View all", "Date"];
                let languageOptions = {
                    "lengthMenu": "Show _MENU_  entries",
                    "zeroRecords": "No records found",
                    "info": "Showing _END_ of _TOTAL_ entries",
                    "infoEmpty": "No entries available",
                    "infoFiltered": "(filtered from _MAX_ total entries)",
                    "search": "Search:",
                    "paginate": {
                        "first": "First",
                        "last": "Last",
                        "next": "Next",
                        "previous": "Previous"
                    }
                }

                if (locale === 'es') {
                    languageOptions = {
                        "lengthMenu": "Mostrar _MENU_  registros",
                        "zeroRecords": "No se encontraron registros",
                        "info": "Mostrando _END_ de _TOTAL_ registros",
                        "infoEmpty": "No hay registros disponibles",
                        "infoFiltered": "(filtrados de _MAX_ registros totales)",
                        "search": "Buscar:",
                        "paginate": {
                            "first": "Primero",
                            "last": "Último",
                            "next": "Siguiente",
                            "previous": "Anterior"
                        }
                    }
                    words[0] = "Mostrar todo";
                    words[1] = "Fecha"
                }

                $('#DataTable').DataTable({
                    "lengthMenu": [
                        [10, 25, 50, 75, 100, -1],
                        ["10", "25", "50", "75", "100", words[0]]
                    ],
                    "search": {
                        smart: true
                    },


                    "language": languageOptions,
                    "dom": '<"flex justify-between items-center"lf>rt<"flex justify-between items-center"ipB>',
                    "buttons": [{
                            extend: 'excelHtml5',
                            text: 'Excel',
                            exportOptions: {
                                columns: ':visible',
                                search: 'applied',
                            },
                            title: 'Informe'
                        },
                        {
                            extend: 'pdfHtml5',
                            text: 'PDF',
                            title: 'Informe',
                            orientation: 'Landscape',
                            pageSize: 'A4',
                            messageTop: words[1] + ": " + new Date().toLocaleDateString(),
                            exportOptions: {
                                columns: ':not(:last-child)'
                            },
                            customize: function(doc) {
                                var columnCount = doc.content[2].table.body[0].length;
                                var columnWidth = (100 / columnCount) + '%';
                                doc.content[2].table.widths = Array(columnCount).fill(columnWidth);
                            }
                        }
                    ],
                    "initComplete": function(settings, json) {
                        $('div.dataTables_length select').removeClass('custom-select form-select');
                    }
                    
                });
            }


            if ($('#DataTable').length) {
                initializeDataTable()
            }
            if ($('.DataTable').length) {
                $('.DataTable').each(function(e) {
                    initializeDataTable()
                });
            }
            

        });
        
    </script>
@endpush
