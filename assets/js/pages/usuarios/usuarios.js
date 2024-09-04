$(document).ready(function () {
    listenUsuarioRol();
})

const listenUsuarioRol = () => {
    let rol = $('#usuario_rol').val()
    if (rol == 1) {
        showTipoNormasDiv(true);
    }
    $('#usuario_rol').on('change', function () {
        rol = $(this).val();
        if (rol == 1) {
            showTipoNormasDiv(true);
        } else {
            showTipoNormasDiv(false);
        }
    });
}

const showTipoNormasDiv = (val) => {
    if (val) {
        $('#tipoNormasDiv').show();
    } else {
        $('#tipoNormasDiv').hide();
    }
}