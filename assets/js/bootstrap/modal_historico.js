$(document).ready(function () {
    $('.datatable-historico').on('click', '.btn-historico', function () {

        let urlHistorico = $('.datatable-historico').data('url-historico');

        let id = $(this).data('id');
        let entidad = $(this).data('entidad');
        $('#spinnerHistorico').removeClass('d-none');
        $('#contentHistorico').addClass('d-none');
        $('#modalHistorico').modal('show');

        $.ajax({
            url: urlHistorico,
            type: 'POST',
            data: { id: id, entidad: entidad },
            success: async function (response) {
                if (response.success) {

                    let historicos = response.data;

                    $('#tbody-historico').html('');

                    historicos.forEach(historico => {
                        let fila = `
                            <tr>
                                <td>${historico.id}</td>
                                <td>${historico.accion}</td>
                                <td>${historico.fecha}</td>
                                <td>${historico.usuario_nombre}</td>
                                <td>${historico.usuario_usuario}</td>
                                <td>${historico.usuario_email}</td>
                                <td class="text-center">
                                    <a href="/secure/auditoria/ver/${historico.id}" title="Ver auditoria">
                                        <i class="fas fa-eye text-primary"></i>
                                    </a>
                                </td>
                            </tr>
                        `;

                        $('#tbody-historico').append(fila);
                    });

                    $('#spinnerHistorico').addClass('d-none');
                    $('#contentHistorico').removeClass('d-none');
                } else {
                    $('#modalHistorico').modal('hide');
                    console.error('Error:', error);
                    Swal.fire('Error', 'Hubo un problema con la solicitud.', 'error');
                }
            },
            error: function (xhr, status, error) {
                $('#modalHistorico').modal('hide');
                console.error('Error:', error);
                Swal.fire('Error', 'Hubo un problema con la solicitud.', 'error');
            }
        });

    });
});
