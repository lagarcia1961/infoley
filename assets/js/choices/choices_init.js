$(document).ready(function() {
    $('.choice_multiple_default').each(function() {
        new Choices(this, {
            removeItemButton: true, // Habilita el botón de eliminación
            placeholder: true,      // Agrega un texto de marcador de posición
            placeholderValue: 'Seleccione uno o varios tipos de norma',
            noResultsText: 'No hay más resultados',
            noChoicesText: 'No hay más opciones disponibles',
            itemSelectText: 'Haga click para seleccionar esta opción'
        });
    });
});