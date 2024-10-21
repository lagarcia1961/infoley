$(document).ready(() => {
    // Recorre los <th> para detectar tipos de columna
    var columnDefs = [];
    $('#datatable-simple thead th').each(function(index) {
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

    var datatable = $('#datatable-simple').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
        },
        columnDefs: columnDefs // Aplica las definiciones de columna basadas en los <th>
    });
});