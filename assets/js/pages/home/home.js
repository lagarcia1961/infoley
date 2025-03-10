$(document).ready(function () {
    // Inicialización de Select2 para el campo tema en búsqueda avanzada
    $('#busqueda_avanzada_tema').select2({
        placeholder: "Seleccione uno o varios temas",
        allowClear: true,
        width: '100%',
        language: "es",
        multiple: true
    });
});