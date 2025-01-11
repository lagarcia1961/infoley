$(document).ready(function () {
    activeChoiceNorma();
});

const activeChoiceNorma = () => {
    choicesOrigen = new Choices('#seccion_norma_norma', {
        placeholder: true,
        placeholderValue: 'Seleccione una norma',
    });
}