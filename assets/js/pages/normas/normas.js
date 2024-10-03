$(document).ready(function () {
    listenTipoNorma();
    listenModals();
    listenEliminarNorma();
    readPdf();

    // Escucha cuando el modal se oculta
    $('#modalAgregarNormaOrigen, #modalAgregarNormaDestino').on('hidden.bs.modal', function () {
        resetModals('origen');
        resetModals('destino');
    });

    // Escucha el botón "Agregar" en el modal de Origen
    $('#modalAgregarNormaOrigen .btn-primary').on('click', function () {
        agregarNorma('origen');
    });

    // Escucha el botón "Agregar" en el modal de Destino
    $('#modalAgregarNormaDestino .btn-primary').on('click', function () {
        agregarNorma('destino');
    });
});

var tipoNormaId;
var choicesOrigen;
var choicesDestino;
var normasOrigenFetched = [];
var normasDestinoFetched = [];
var normasSeleccionadasOrigen = [];
var normasSeleccionadasDestino = [];

// Función para agregar una norma (Origen o Destino)
const agregarNorma = (tipo) => {
    const normaId = $(`#norma_norma${tipo === 'origen' ? 'Origen' : 'Destino'}`).val();
    const tipoReferenciaId = $(`#norma_tipoReferencia${tipo === 'origen' ? 'Origen' : 'Destino'}`).val();

    // Limpiar mensajes de error previos
    limpiarErrores(tipo);

    // Validar si ambos campos están seleccionados
    let errores = false;
    if (!normaId) {
        mostrarError(`#norma_norma${tipo === 'origen' ? 'Origen' : 'Destino'}`, 'Debe seleccionar una norma');
        errores = true;
    }
    if (!tipoReferenciaId) {
        mostrarError(`#norma_tipoReferencia${tipo === 'origen' ? 'Origen' : 'Destino'}`, 'Debe seleccionar un tipo de referencia');
        errores = true;
    }

    // Si hay errores, detener la ejecución
    if (errores) {
        return;
    }

    // Verificar si la norma ya está agregada en la tabla correspondiente
    const normaYaAgregada = $(`#tablaNormas${tipo === 'origen' ? 'Origen' : 'Destino'} tbody tr[id="row-norma-${normaId}-${tipo}"]`).length > 0;
    if (normaYaAgregada) {
        mostrarError(`#norma_norma${tipo === 'origen' ? 'Origen' : 'Destino'}`, 'La norma ya se encuenta agregada, si desea corregir, elimine primero la norma de la lista y luego vuelva a agregarla.');
        return;
    }

    // Obtener los datos de la norma seleccionada
    const norma = (tipo === 'origen' ? normasOrigenFetched : normasDestinoFetched).find(n => n.id == normaId);

    if (!norma) {
        mostrarError(`#norma_norma${tipo === 'origen' ? 'Origen' : 'Destino'}`, 'Norma no encontrada');
        return;
    }

    // Crear el objeto con los datos de la norma seleccionada
    const normaData = {
        id: norma.id,
        titulo: norma.titulo,
        tipoNorma: norma.tipoNorma,
        numero: norma.numero,
        anio: norma.anio,
        fechaPublicacion: norma.fechaPublicacion,
        tipoReferenciaId: tipoReferenciaId, // Guardar el ID de tipo de referencia
        tipoReferencia: $(`#norma_tipoReferencia${tipo === 'origen' ? 'Origen' : 'Destino'} option:selected`).text()  // Usar el texto del select para mostrarlo
    };

    // Agregar inputs hidden directamente dentro de la fila
    agregarFilaConInputsHidden(normaData, tipo);

    // Cerrar el modal
    $(`#modalAgregarNorma${tipo === 'origen' ? 'Origen' : 'Destino'}`).modal('hide');
    resetModals(tipo);
};


// Función para mostrar errores con Bootstrap
const mostrarError = (selector, mensaje) => {
    const elemento = $(selector);
    const errorHtml = `<div class="invalid-feedback">${mensaje}</div>`;

    // Añadir clase is-invalid de Bootstrap
    elemento.addClass('is-invalid');
    elemento.parent().append(errorHtml);
};

// Función para limpiar errores previos
const limpiarErrores = (tipo) => {
    $(`#norma_norma${tipo === 'origen' ? 'Origen' : 'Destino'}`).removeClass('is-invalid');
    $(`#norma_tipoReferencia${tipo === 'origen' ? 'Origen' : 'Destino'}`).removeClass('is-invalid');

    // Eliminar los mensajes de error previos
    $(`#norma_norma${tipo === 'origen' ? 'Origen' : 'Destino'}`).parent().find('.invalid-feedback').remove();
    $(`#norma_tipoReferencia${tipo === 'origen' ? 'Origen' : 'Destino'}`).parent().find('.invalid-feedback').remove();
};

// Función para agregar fila con inputs hidden
const agregarFilaConInputsHidden = (normaData, tipo) => {
    const tbody = $(`#tablaNormas${tipo === 'origen' ? 'Origen' : 'Destino'} tbody`);

    // Crear la fila con inputs hidden dentro de la estructura de "norma"
    tbody.append(`
        <tr id="row-norma-${normaData.id}-${tipo}">
            <td>${normaData.tipoNorma}</td>
            <td>${normaData.titulo}</td>
            <td>${normaData.tipoReferencia}</td>
            <td>${normaData.numero}</td>
            <td>${normaData.anio}</td>
            <td>${normaData.fechaPublicacion}</td>
            <td class="text-center">
                <a href="javascript:void(0);" class="text-danger eliminarNorma" data-id="${normaData.id}" data-tipo="${tipo}">
                    <i class="fas fa-trash"></i>
                </a>
                <input type="hidden" name="norma[normasAgregadas${tipo === 'origen' ? 'Origen' : 'Destino'}][${normaData.id}][norma]" value="${normaData.id}">
                <input type="hidden" name="norma[normasAgregadas${tipo === 'origen' ? 'Origen' : 'Destino'}][${normaData.id}][tipoReferencia]" value="${normaData.tipoReferenciaId}">
            </td>
        </tr>
    `);

    listenEliminarNorma();
};

const listenEliminarNorma = () => {

    $('.eliminarNorma').on('click', function () {
        const normaId = $(this).data('id');
        const tipo = $(this).data('tipo');
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
                eliminarFila(normaId, tipo);
            }
        });
    });
}
// Función para eliminar la fila y los inputs hidden
const eliminarFila = (normaId, tipo) => {
    // Eliminar la fila completa, incluyendo los inputs hidden
    $(`#row-norma-${normaId}-${tipo}`).remove();
};


// Función para actualizar la tabla de normas seleccionadas
const actualizarTablaNormas = (normas, tipo) => {
    const tbody = $(`#tablaNormas${tipo === 'origen' ? 'Origen' : 'Destino'} tbody`);
    tbody.empty(); // Limpiar la tabla antes de actualizar

    normas.forEach((norma, index) => {
        tbody.append(`
            <tr>
                <td>${norma.tipoNorma}</td>
                <td>${norma.titulo}</td>
                <td>${norma.tipoReferencia}</td>
                <td>${norma.numero}</td>
                <td>${norma.anio}</td>
                <td>${norma.fechaPublicacion}</td>
                <td><a href="javascript:void(0);" class="text-danger" data-index="${index}" data-tipo="${tipo}"><i class="fas fa-trash"></i></a></td>
            </tr>
        `);
    });

    // Añadir funcionalidad para eliminar normas
    $('.text-danger').on('click', function () {
        const index = $(this).data('index');
        const tipo = $(this).data('tipo');
        eliminarNorma(index, tipo);
    });
};

// Función para eliminar una norma seleccionada
const eliminarNorma = (index, tipo) => {
    if (tipo === 'origen') {
        normasSeleccionadasOrigen.splice(index, 1); // Eliminar del array
        actualizarTablaNormas(normasSeleccionadasOrigen, 'origen'); // Actualizar la tabla
    } else {
        normasSeleccionadasDestino.splice(index, 1); // Eliminar del array
        actualizarTablaNormas(normasSeleccionadasDestino, 'destino'); // Actualizar la tabla
    }
};

const listenTipoNorma = () => {
    tipoNormaId = $('#norma_tipoNorma').val();
    var normaIdEdit = $('#normaIdEdit').length ? $('#normaIdEdit').data('id') : false;
    activeBotonesModal(tipoNormaId); // Activar o desactivar botones según el valor
    if (tipoNormaId) {
        getNormas(false, 'norma_normaOrigen', normaIdEdit);
        getNormas(true, 'norma_normaDestino', normaIdEdit);
    }
    $('#norma_tipoNorma').on('change', function () {
        tipoNormaId = $(this).val();

        // Destruir las instancias de Choices si existen
        if (choicesOrigen) {
            choicesOrigen.destroy();
        }
        if (choicesDestino) {
            choicesDestino.destroy();
        }

        // Limpiar selects
        clearNormasOrigenYDestino();

        // Obtener nuevas normas según el nuevo tipo de norma
        getNormas(false, 'norma_normaOrigen');
        getNormas(true, 'norma_normaDestino');

        // Activar o desactivar botones modales según el nuevo tipo de norma
        activeBotonesModal(tipoNormaId);
    });
};

const clearNormasOrigenYDestino = () => {
    // Vaciar los selects para evitar residuos
    $('#norma_normaOrigen').empty().attr('disabled', 'disabled');
    $('#norma_normaDestino').empty().attr('disabled', 'disabled');
};

const activeBotonesModal = (status) => {
    if (status) {
        $('#botonModalAgregarNormaOrigen').removeClass('disabled');
        $('#botonModalAgregarNormaDestino').removeClass('disabled');
    } else {
        $('#botonModalAgregarNormaOrigen').addClass('disabled');
        $('#botonModalAgregarNormaDestino').addClass('disabled');
    }
};

const listenModals = () => {
    $('#botonModalAgregarNormaOrigen').on('click', () => {
        showModal('modalAgregarNormaOrigen');
    });
    $('#botonModalAgregarNormaDestino').on('click', () => {
        showModal('modalAgregarNormaDestino');
    });
};

const showModal = (idModal) => {
    $(`#${idModal}`).modal('show');
};

const getNormas = (menor, idSelectNormas, normaIdEdit) => {
    // Mostrar spinner y ocultar el contenido mientras se cargan las normas
    if (idSelectNormas === 'norma_normaOrigen') {
        $('#spinnerNormaOrigen').removeClass('d-none'); // Mostrar spinner
        $('#contentNormaOrigen').addClass('d-none'); // Ocultar contenido del modal
    } else {
        $('#spinnerNormaDestino').removeClass('d-none'); // Mostrar spinner
        $('#contentNormaDestino').addClass('d-none'); // Ocultar contenido del modal
    }

    $.ajax({
        url: BASE_URL + 'secure/norma/getNormas',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ tipoNormaId: parseInt(tipoNormaId), menor: menor, normaId: normaIdEdit }),
        success: async (res) => {
            let normasFetched = [];

            if (res.success) {
                normasFetched = res.normas;
                if (idSelectNormas === 'norma_normaOrigen') {
                    normasOrigenFetched = normasFetched;
                } else {
                    normasDestinoFetched = normasFetched;
                }

                const selectElement = document.getElementById(`${idSelectNormas}`);

                // Si hay normas, habilita los selectores
                if (normasFetched.length === 0) {
                    $(selectElement).attr('disabled', 'disabled');
                    $(`#norma_tipoReferencia${idSelectNormas === 'norma_normaOrigen' ? 'Origen' : 'Destino'}`).attr('disabled', 'disabled');
                } else {
                    $(selectElement).removeAttr('disabled');
                    $(`#norma_tipoReferencia${idSelectNormas === 'norma_normaOrigen' ? 'Origen' : 'Destino'}`).removeAttr('disabled');
                }

                await updateNormas(selectElement, idSelectNormas === 'norma_normaOrigen' ? 'origen' : 'destino');
            } else {
                console.log('Error al obtener normas');
                $(idSelectNormas).attr('disabled', 'disabled');
            }
        },
        error: function () {
            console.log('Error en la petición AJAX');
            $(idSelectNormas).attr('disabled', 'disabled');
        },
        complete: function () {
            // Ocultar spinner y mostrar contenido una vez completada la carga
            if (idSelectNormas === 'norma_normaOrigen') {
                $('#spinnerNormaOrigen').addClass('d-none'); // Ocultar spinner
                $('#contentNormaOrigen').removeClass('d-none'); // Mostrar contenido del modal
            } else {
                $('#spinnerNormaDestino').addClass('d-none'); // Ocultar spinner
                $('#contentNormaDestino').removeClass('d-none'); // Mostrar contenido del modal
            }
        }
    });
};


const updateNormas = (selectElement, tipo) => {
    // Rehabilitar los selects
    $('#norma_normaDestino').removeAttr('disabled');
    $('#norma_tipoReferenciaDestino').removeAttr('disabled');
    $('#norma_normaOrigen').removeAttr('disabled');
    $('#norma_tipoReferenciaOrigen').removeAttr('disabled');

    // Inicializar el plugin Choices.js para el select correspondiente
    let choices;
    if (tipo === 'origen') {
        choicesOrigen = new Choices(selectElement, {
            placeholder: true,
            placeholderValue: 'Seleccione una norma',
        });
        choices = choicesOrigen;
    } else {
        choicesDestino = new Choices(selectElement, {
            placeholder: true,
            placeholderValue: 'Seleccione una norma',
        });
        choices = choicesDestino;
    }

    // Limpiar y actualizar el select con las normas obtenidas
    choices.setChoices([], 'value', 'label', true);  // Limpiar las opciones actuales
    const normasMaped = (tipo === 'origen' ? normasOrigenFetched : normasDestinoFetched).map(norma => ({
        value: norma.id,
        label: norma.titulo + ' - ' + norma.numero + ' - ' + norma.anio
    }));

    normasMaped.unshift({ value: '', label: 'Seleccione una norma', disabled: true, selected: true });

    choices.setChoices(normasMaped);

    // Agregar un evento para cuando se selecciona un valor en el combo
    $(selectElement).on('change', function () {
        const selectedValue = this.value; // Valor seleccionado
        let selectedNorma;

        // Verifica si es de origen o destino y busca la norma correspondiente
        if (tipo === 'origen') {
            selectedNorma = normasOrigenFetched.find(norma => norma.id == selectedValue);

            // Limpia el div de destino si se selecciona una norma de origen
            $('#spanNormaDestino_titulo, #spanNormaDestino_numero, #spanNormaDestino_anio, #spanNormaDestino_fechaPublicacion, #spanNormaDestino_textoCompleto, #spanNormaDestino_urlPdf').text('');

            // Llenar los spans del origen
            if (selectedNorma) {
                $('#spanNormaOrigen_titulo').text(selectedNorma.titulo || '');
                $('#spanNormaOrigen_numero').text(selectedNorma.numero || '');
                $('#spanNormaOrigen_anio').text(selectedNorma.anio || '');
                $('#spanNormaOrigen_fechaPublicacion').text(selectedNorma.fechaPublicacion || '');
                // Obtiene el texto completo de selectedNorma
                var textoCompleto = selectedNorma.textoCompleto || '';
                // Limita el texto a 200 caracteres
                var textoLimitado = textoCompleto.length > 1500 ? textoCompleto.substring(0, 1500) + '...' : textoCompleto;
                // Establece el texto limitado en el span
                $('#spanNormaOrigen_textoCompleto').text(textoLimitado);
                $('#spanNormaOrigen_tipoNorma').text(selectedNorma.tipoNorma || '');
                $('#spanNormaOrigen_urlPdf').text(selectedNorma.urlPdf || '');
                if (selectedNorma.urlPdf) {
                    $('#aNormaOrigen_urlPdf').attr('href', window.location.origin + '/uploads/normas/' + selectedNorma.urlPdf)
                } else {
                    $('#aNormaOrigen_urlPdf').attr('href', '#');
                }
            }

        } else {
            selectedNorma = normasDestinoFetched.find(norma => norma.id == selectedValue);

            // Limpia el div de origen si se selecciona una norma de destino
            $('#spanNormaOrigen_titulo, #spanNormaOrigen_numero, #spanNormaOrigen_anio, #spanNormaOrigen_fechaPublicacion, #spanNormaOrigen_textoCompleto, #spanNormaOrigen_urlPdf').text('');

            // Llenar los spans del destino
            if (selectedNorma) {
                $('#spanNormaDestino_titulo').text(selectedNorma.titulo || '');
                $('#spanNormaDestino_numero').text(selectedNorma.numero || '');
                $('#spanNormaDestino_anio').text(selectedNorma.anio || '');
                $('#spanNormaDestino_fechaPublicacion').text(selectedNorma.fechaPublicacion || '');
                // Obtiene el texto completo de selectedNorma
                var textoCompleto = selectedNorma.textoCompleto || '';
                // Limita el texto a 200 caracteres
                var textoLimitado = textoCompleto.length > 1500 ? textoCompleto.substring(0, 1500) + '...' : textoCompleto;
                // Establece el texto limitado en el span
                $('#spanNormaDestino_textoCompleto').text(textoLimitado);
                $('#panNormaDestino_tipoNorma').text(selectedNorma.tipoNorma || '');
                $('#spanNormaDestino_urlPdf').text(selectedNorma.urlPdf || '');
                if (selectedNorma.urlPdf) {
                    $('#aNormaDestino_urlPdf').attr('href', window.location.origin + '/uploads/normas/' + selectedNorma.urlPdf)
                } else {
                    $('#aNormaDestino_urlPdf').attr('href', '#');
                }
            }
        }
    });
};

// Resetear los spans y selects cuando el modal se cierra
const resetModals = (tipo) => {
    // Limpiar todos los spans de Norma Origen
    $('#spanNormaOrigen_titulo, #spanNormaOrigen_numero, #spanNormaOrigen_anio, #spanNormaOrigen_fechaPublicacion, #spanNormaOrigen_textoCompleto, #spanNormaOrigen_urlPdf').text('');

    // Limpiar todos los spans de Norma Destino
    $('#spanNormaDestino_titulo, #spanNormaDestino_numero, #spanNormaDestino_anio, #spanNormaDestino_fechaPublicacion, #spanNormaDestino_textoCompleto, #spanNormaDestino_urlPdf').text('');

    // Reiniciar los selects de normaOrigen y normaDestino a su estado inicial
    if (choicesOrigen) {
        choicesOrigen.setChoiceByValue('');  // Reiniciar el select de origen
    }

    if (choicesDestino) {
        choicesDestino.setChoiceByValue('');  // Reiniciar el select de destino
    }

    // Restablecer los combos de tipoReferenciaOrigen y tipoReferenciaDestino a su estado inicial
    $('#norma_tipoReferenciaOrigen').val('');  // Desseleccionar cualquier opción
    $('#norma_tipoReferenciaDestino').val('');  // Desseleccionar cualquier opción
    limpiarErrores(tipo);
};

const readPdf = () => {
    $('#norma_urlPdf').on('change', function (event) {
        var file = event.target.files[0];
        if (file && file.type === 'application/pdf') {
            var reader = new FileReader();
            reader.onload = function (e) {
                var pdfData = new Uint8Array(e.target.result);
                pdfjsLib.getDocument({ data: pdfData }).promise.then(function (pdf) {
                    var totalPages = pdf.numPages;
                    var norma_textoCompleto = '';
    
                    // Leer cada página y extraer el texto
                    var pagePromises = [];
                    for (let i = 1; i <= totalPages; i++) {
                        pagePromises.push(
                            pdf.getPage(i).then(function (page) {
                                return page.getTextContent().then(function (textContent) {
                                    let pageText = textContent.items.map(item => item.str).join(' ');
                                    norma_textoCompleto += pageText + '\n\n';
                                });
                            })
                        );
                    }
    
                    // Una vez que todas las páginas están leídas
                    Promise.all(pagePromises).then(function () {
                        $('#norma_textoCompleto').val(norma_textoCompleto);
                    });
                });
            };
            reader.readAsArrayBuffer(file);
        } else {
            alert('Por favor, selecciona un archivo PDF.');
        }
    });
}
