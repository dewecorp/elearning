// DataTables export functionality
$(document).ready(function() {
    // Check if DataTables is available
    if ($.fn.DataTable) {
        // Add export functionality to all tables with export enabled
        $('table[data-export="true"]').each(function() {
            var tableId = $(this).attr('id');
            if (tableId) {
                // Check if DataTable has already been initialized
                if (!$('#' + tableId).hasClass('dataTable')) {
                    $('#' + tableId).DataTable({
                        "language": {
                            "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                        },
                        "pageLength": 10,
                        "responsive": true,
                        "ordering": true,
                        "info": true,
                        "dom": 'Bfrtip',
                        "buttons": [
                            'copy', 'csv', 'excel', 'pdf', 'print'
                        ]
                    });
                }
            }
        });
    }
});

// Also reinitialize on AJAX content load
$(document).on('DOMNodeInserted', function() {
    if ($.fn.DataTable) {
        $('table[data-export="true"]:not(.dataTable)').each(function() {
            var tableId = $(this).attr('id');
            if (tableId) {
                $('#' + tableId).DataTable({
                    "language": {
                        "url": "//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json"
                    },
                    "pageLength": 10,
                    "responsive": true,
                    "ordering": true,
                    "info": true,
                    "dom": 'Bfrtip',
                    "buttons": [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
            }
        });
    }
});