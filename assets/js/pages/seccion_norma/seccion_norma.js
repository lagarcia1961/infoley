$(document).ready(function () {
    activeChoiceNorma();
});

const activeChoiceNorma = () => {
    choicesOrigen = $("#seccion_norma_norma");

    choicesOrigen.select2({
        placeholder: "Seleccione una norma",
        allowClear: true,
        width: '100%',
        language: "es",
    });


}