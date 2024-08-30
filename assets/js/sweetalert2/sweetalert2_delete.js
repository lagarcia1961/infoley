$(document).ready(function () {
    $('.datatable-delete').on('click', '.btn-delete', function () {
        let urlEliminar = $('.datatable-delete').data('url-eliminar');

        var table = $(this).closest('.datatable-delete'); // Encuentra la tabla más cercana
        var datatable = table.DataTable();

        let row = datatable.row($(this).closest('tr'));
        let data = row.data();
        let itemId = $(this).data('id'); // El ID del item a eliminar

        Swal.fire({
            title: '¿Estás seguro?',
            text: 'No podrás revertir esto',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            },
        }).then(function (result) {
            if (result.isConfirmed) {

                $.ajax({
                    url: urlEliminar,
                    type: 'POST',
                    data: { id: itemId },
                    success: function (response) {
                        if (response.success) {
                            datatable.row(row).remove().draw(); // Elimina la fila del DataTable
                        }
                        Swal.fire(response.title, response.message, response.success ? 'success' : 'error');
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Hubo un problema con la solicitud.', 'error');
                    }
                });
            }
        });
    });
});
