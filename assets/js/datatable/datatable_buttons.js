$(document).ready(() => {
    var datatable = $('#datatable-buttons').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/2.1.4/i18n/es-MX.json',
        },
        dom: '<"row mb-3"<"col-3"l><"col-6 text-center"B><"col-3"f>><"row"<"col-12"r>><"row"<"col-12"t>><"row mt-3 mb-3"<"col-3"i><"col-9 text-end"p>>',
        buttons: [
            'copy', 'excel', 'pdf', 'print',
        ]
    });
});