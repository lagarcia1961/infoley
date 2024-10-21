$(document).ready(() => {
    // Recorre los <th> para detectar tipos de columna
    var columnDefs = [];
    $('#datatable-buttons thead th').each(function(index) {
        var columnType = $(this).data('column-type');
        if (columnType) {
            var type;
            
            if (columnType === 'numeric') {
                type = 'num';
            } else if (columnType === 'date') {
                type = 'date';
            } else if (columnType === 'datetime') {
                type = 'datetime';
            } else if (columnType === 'string') {
                type = 'string';
            }

            if (type) {
                columnDefs.push({
                    type: type,
                    targets: index
                });
            }
        }
    });

    var datatable = $('#datatable-buttons').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
        },
        dom: '<"row mb-3"<"col-3"l><"col-6 text-center"B><"col-3"f>><"row"<"col-12"r>><"row"<"col-12"t>><"row mt-3 mb-3"<"col-3"i><"col-9 text-end"p>>',
        buttons: [
            'copy', 'excel', 'pdf', 'print',
        ],
        columnDefs: columnDefs // Aplica las definiciones de columna basadas en los <th>
    });
});