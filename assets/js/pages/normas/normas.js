$(document).ready(function () {
    listenTipoNorma();
    listenModals();
    listenEliminarNorma();
    readPdf(IS_NORMA_EDIT);
    loadTrumbowygEditor();
    listenVerTextoModificado();

    // Configuración para select2 de normaOrigen (ya lo tenías)
    $('#norma_normaOrigen').select2({
        placeholder: "Seleccione una norma",
        allowClear: true,
        width: '100%',
        language: "es",
        dropdownParent: $('#modalAgregarNormaOrigen')
    });

    // 🟢 Configuración para select2 de temas (opción múltiple)
    $('#norma_temas').select2({
        placeholder: "Seleccione uno o varios temas",
        allowClear: true,
        width: '100%',
        language: "es",
        multiple: true
    });

    // Escucha cuando el modal se oculta
    $('#modalAgregarNormaOrigen, #modalAgregarNormaDestino').on('hidden.bs.modal', function () {
        resetModals();
    });

    // Escucha el botón "Agregar" en el modal de Origen
    $('#modalAgregarNormaOrigen .btn-primary').on('click', function () {
        agregarNorma();
    });

    deleteSpinner();
});


var tipoNormaId;
var selectNormaOrigen;
var normasOrigenFetched = [];
var normasSeleccionadasOrigen = [];


const deleteSpinner = () => {
    $('#loading-spinner').remove();
}
const loadTrumbowygEditor = () => {

    if ($('#norma_textoCompletoModificadoHtml').length) {
        $('#norma_textoCompletoModificadoHtml').trumbowyg({
            svgPath: ICONS_PATH,
            lang: 'es_ar',
            btns: [
                ['undo', 'redo'], // Deshacer y rehacer
                ['formatting'], // Formatos de párrafo
                ['strong', 'em', 'del'], // Negrita, cursiva y tachado
                ['superscript', 'subscript'], // Superíndice y subíndice
                ['link'], // Enlaces
                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'], // Alineaciones
                ['unorderedList', 'orderedList'], // Listas
                ['horizontalRule'], // Línea horizontal
                ['removeformat'], // Quitar formato
                ['foreColor', 'backColor'], // Colores de texto y fondo
                ['fullscreen']
            ],
            linkTargets: ['_blank', '_self'],
        });
    }

    $('#norma_textoCompletoHtml').trumbowyg({
        svgPath: ICONS_PATH,
        lang: 'es_ar',
        btns: [
            ['undo', 'redo'], // Deshacer y rehacer
            ['formatting'], // Formatos de párrafo
            ['strong', 'em', 'del'], // Negrita, cursiva y tachado
            ['superscript', 'subscript'], // Superíndice y subíndice
            ['link'], // Enlaces
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'], // Alineaciones
            ['unorderedList', 'orderedList'], // Listas
            ['horizontalRule'], // Línea horizontal
            ['removeformat'], // Quitar formato
            ['foreColor', 'backColor'], // Colores de texto y fondo
            ['fullscreen']
        ],
        linkTargets: ['_blank', '_self'],
    });

    $('#modal_textoCompletoModificadoHtml').trumbowyg({
        svgPath: ICONS_PATH,
        lang: 'es_ar',
        btns: [
            ['undo', 'redo'], // Deshacer y rehacer
            ['formatting'], // Formatos de párrafo
            ['strong', 'em', 'del'], // Negrita, cursiva y tachado
            ['superscript', 'subscript'], // Superíndice y subíndice
            ['link'], // Enlaces
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'], // Alineaciones
            ['unorderedList', 'orderedList'], // Listas
            ['horizontalRule'], // Línea horizontal
            ['removeformat'], // Quitar formato
            ['foreColor', 'backColor'], // Colores de texto y fondo
            ['fullscreen']
        ],
        linkTargets: ['_blank', '_self'],
    });
};

// Función para agregar una norma 
const agregarNorma = () => {
    const normaId = $(`#norma_normaOrigen`).val();
    const tipoReferenciaId = $(`#norma_tipoReferenciaOrigen`).val();

    // Limpiar mensajes de error previos
    limpiarErrores();

    // Validar si ambos campos están seleccionados
    let errores = false;
    if (!normaId) {
        mostrarError(`#norma_normaOrigen`, 'Debe seleccionar una norma');
        errores = true;
    }
    if (!tipoReferenciaId) {
        mostrarError(`#norma_tipoReferenciaOrigen`, 'Debe seleccionar un tipo de referencia');
        errores = true;
    }

    // Si hay errores, detener la ejecución
    if (errores) {
        return;
    }

    // Verificar si la norma ya está agregada en la tabla correspondiente
    const normaYaAgregada = $(`#tablaNormasOrigen tbody tr[id="row-norma-${normaId}-origen"]`).length > 0;
    if (normaYaAgregada) {
        mostrarError(`#norma_normaOrigen`, 'La norma ya se encuenta agregada, si desea corregir, elimine primero la norma de la lista y luego vuelva a agregarla.');
        return;
    }

    // Obtener los datos de la norma seleccionada
    const norma = normasOrigenFetched.find(n => n.id == normaId);

    if (!norma) {
        mostrarError(`#norma_normaOrigen`, 'Norma no encontrada');
        return;
    }

    // Crear el objeto con los datos de la norma seleccionada
    const normaData = {
        id: norma.id,
        titulo: norma.titulo,
        tipoNorma: norma.tipoNorma,
        numero: norma.numero,
        fechaSancion: norma.fechaSancion,
        fechaPublicacion: norma.fechaPublicacion,
        tipoReferenciaId: tipoReferenciaId, // Guardar el ID de tipo de referencia
        textoCompletoModificadoHtml: $('.trumbowyg-editor').html(),
        tipoReferencia: $(`#norma_tipoReferenciaOrigen option:selected`).text()  // Usar el texto del select para mostrarlo
    };

    // Agregar inputs hidden directamente dentro de la fila
    agregarFilaConInputsHidden(normaData);

    // Cerrar el modal
    $(`#modalAgregarNormaOrigen`).modal('hide');
    resetModals();
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
const limpiarErrores = () => {
    $(`#norma_normaOrigen`).removeClass('is-invalid');
    $(`#norma_tipoReferenciaOrigen`).removeClass('is-invalid');

    // Eliminar los mensajes de error previos
    $(`#norma_normaOrigen`).parent().find('.invalid-feedback').remove();
    $(`#norma_tipoReferenciaOrigen`).parent().find('.invalid-feedback').remove();
};

// Función para agregar fila con inputs hidden
const agregarFilaConInputsHidden = (normaData) => {
    const tbody = $(`#tablaNormasOrigen tbody`);

    // Crear la fila con inputs hidden dentro de la estructura de "norma"
    tbody.append(`
        <tr id="row-norma-${normaData.id}-origen">
            <td>${normaData.tipoNorma}</td>
            <td>${normaData.titulo}</td>
            <td>${normaData.tipoReferencia}</td>
            <td>${normaData.numero}</td>
            <td>${normaData.fechaSancion}</td>
            <td>${normaData.fechaPublicacion}</td>
            <td class="text-center">
                <a href="javascript:void(0);" class="verTextoModificado text-secondary m-2" title="Ver texto modificado" data-id="${normaData.id}">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="javascript:void(0);" class="text-danger eliminarNorma m-2" data-id="${normaData.id}">
                    <i class="fas fa-trash"></i>
                </a>
                <input type="hidden" name="norma[normasAgregadasOrigen][${normaData.id}][norma]" value="${normaData.id}">
                <input type="hidden" name="norma[normasAgregadasOrigen][${normaData.id}][tipoReferencia]" value="${normaData.tipoReferenciaId}">
                <textarea style="display:none;" name="norma[normasAgregadasOrigen][${normaData.id}][textoCompletoModificadoHtml]">${normaData.textoCompletoModificadoHtml}</textarea>
            </td>
        </tr>
    `);

    listenEliminarNorma();
    listenVerTextoModificado();
};

const listenVerTextoModificado = () => {
    $('.verTextoModificado').on('click', function () {
        // Obtener el textarea en la misma fila
        const textareaContent = $(this).closest('tr').find('textarea').val();

        // Mostrar el contenido del textarea en SweetAlert2
        Swal.fire({
            title: 'Texto Modificado',
            html: `
                <div style="max-height: 300px; overflow-y: auto; text-align: left;">
                    ${textareaContent}
                </div>
            `,
            width: '80%', // Ajustar ancho del modal según necesidad
            showCloseButton: true,
            showCancelButton: false,
            confirmButtonText: 'Cerrar',
        });
    });
};

const listenEliminarNorma = () => {

    $('.eliminarNorma').on('click', function () {
        const normaId = $(this).data('id');
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
                eliminarFila(normaId);
            }
        });
    });
}
// Función para eliminar la fila y los inputs hidden
const eliminarFila = (normaId) => {
    // Eliminar la fila completa, incluyendo los inputs hidden
    $(`#row-norma-${normaId}-origen`).remove();
};


// Función para actualizar la tabla de normas seleccionadas
const actualizarTablaNormas = () => {
    const tbodyOrigen = $(`#tablaNormasOrigen tbody`);
    tbodyOrigen.empty();
    const tbodyDestino = $(`#tablaNormasDestino tbody`);
    tbodyDestino.empty();
};


const listenTipoNorma = () => {
    tipoNormaId = $('#norma_tipoNorma').val();
    var normaIdEdit = $('#normaIdEdit').length ? $('#normaIdEdit').data('id') : false;
    activeBotonesModal(tipoNormaId); // Activar o desactivar botones según el valor
    if (tipoNormaId) {
        getNormas(false, 'norma_normaOrigen', normaIdEdit);
    }
    $('#norma_tipoNorma').on('change', function () {
        actualizarTablaNormas();
        tipoNormaId = $(this).val();

        // Limpiar selects
        clearNormasOrigen();

        // Obtener nuevas normas según el nuevo tipo de norma
        getNormas(false, 'norma_normaOrigen');

        // Activar o desactivar botones modales según el nuevo tipo de norma
        activeBotonesModal(tipoNormaId);
    });
};

const clearNormasOrigen = () => {
    // Vaciar los selects para evitar residuos
    $('#norma_normaOrigen').empty().attr('disabled', 'disabled');
};

const activeBotonesModal = (status) => {
    if (status) {
        $('#botonModalAgregarNormaOrigen').removeClass('disabled');
    } else {
        $('#botonModalAgregarNormaOrigen').addClass('disabled');
    }
};

const listenModals = () => {
    $('#botonModalAgregarNormaOrigen').on('click', () => {
        showModal('modalAgregarNormaOrigen');
    });
};

const showModal = (idModal) => {
    $(`#${idModal}`).modal('show');
};

const getNormas = (menor, idSelectNormas, normaIdEdit) => {
    // Mostrar spinner y ocultar el contenido mientras se cargan las normas
    $('#spinnerNormaOrigen').removeClass('d-none'); // Mostrar spinner
    $('#contentNormaOrigen').addClass('d-none'); // Ocultar contenido del modal

    $.ajax({
        url: BASE_URL + 'secure/norma/getNormas',
        type: 'POST',
        contentType: 'application/json',
        data: JSON.stringify({ tipoNormaId: parseInt(tipoNormaId), menor: menor, normaId: normaIdEdit }),
        success: async (res) => {
            let normasFetched = [];

            if (res.success) {
                normasFetched = res.normas;
                normasOrigenFetched = normasFetched;

                const selectElement = document.getElementById(`norma_normaOrigen`);

                // Si hay normas, habilita los selectores
                if (normasFetched.length === 0) {
                    $(selectElement).attr('disabled', 'disabled');
                    $(`#norma_tipoReferenciaOrigen`).attr('disabled', 'disabled');
                } else {
                    $(selectElement).removeAttr('disabled');
                    $(`#norma_tipoReferenciaOrigen`).removeAttr('disabled');
                }

                await updateNormas(selectElement);
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
            $('#spinnerNormaOrigen').addClass('d-none'); // Ocultar spinner
            $('#contentNormaOrigen').removeClass('d-none'); // Mostrar contenido del modal
        }
    });
};


const updateNormas = (selectElement) => {
    // Rehabilitar los selects
    $('#norma_normaOrigen').removeAttr('disabled');
    $('#norma_tipoReferenciaOrigen').removeAttr('disabled');

    selectNormaOrigen = $("#norma_normaOrigen");
    // Inicializar el plugin Choices.js para el select correspondiente
    selectNormaOrigen.empty(); // Limpiar opciones previas

    // Agregar opción por defecto
    selectNormaOrigen.append(new Option("Seleccione una norma", "", false, false));

    // Agregar normas dinámicamente
    normasOrigenFetched.forEach(norma => {
        selectNormaOrigen.append(new Option(`${norma.titulo} - ${norma.numero} - ${norma.fechaSancion}`, norma.id));
    });

    // Inicializar Select2
    selectNormaOrigen.select2({
        placeholder: "Seleccione una norma",
        allowClear: true,
        width: '100%',
        language: "es",
        dropdownParent: $('#modalAgregarNormaOrigen') // Evita problemas en modales
    });

    // Refrescar Select2
    selectNormaOrigen.trigger('change');

    // Agregar un evento para cuando se selecciona un valor en el combo
    $(selectNormaOrigen).on('change', function () {
        const selectedValue = this.value; // Valor seleccionado
        let selectedNorma;

        selectedNorma = normasOrigenFetched.find(norma => norma.id == selectedValue);


        // Llenar los spans del origen
        if (selectedNorma) {
            $('#spanNormaOrigen_titulo').text(selectedNorma.titulo || '');
            $('#spanNormaOrigen_numero').text(selectedNorma.numero || '');
            $('#spanNormaOrigen_fechaSancion').text(selectedNorma.fechaSancion || '');
            $('#spanNormaOrigen_fechaPublicacion').text(selectedNorma.fechaPublicacion || '');
            // Establece el texto limitado en el span
            $(' #spanNormaOrigen_tipoNorma').text(selectedNorma.tipoNorma || '');
            $('#spanNormaOrigen_urlPdf').text(selectedNorma.urlPdf || '');
            if (selectedNorma.urlPdf) {
                $('#aNormaOrigen_urlPdf').attr('href', window.location.origin + '/uploads/normas/' + selectedNorma.urlPdf)
            } else {
                $('#aNormaOrigen_urlPdf').attr('href', '#');
            }
            $('#modal_textoCompletoModificadoHtml').trumbowyg('html', selectedNorma.textoCompletoModificadoHtml);
        }
    });
};

// Resetear los spans y selects cuando el modal se cierra
const resetModals = () => {
    // Limpiar todos los spans de Norma Origen
    $('#spanNormaOrigen_titulo, #spanNormaOrigen_tipoNorma, #spanNormaOrigen_numero, #spanNormaOrigen_fechaSancion, #spanNormaOrigen_fechaPublicacion, #modal_textoCompletoModificadoHtml, #spanNormaOrigen_urlPdf').text('');

    selectNormaOrigen.val(null).trigger('change');
    $('#norma_tipoReferenciaOrigen').val('');
    $('#modal_textoCompletoModificadoHtml').html('');
    $('#modal_textoCompletoModificadoHtml').trumbowyg('empty');
    $('#modal_textoCompletoModificadoHtml').trumbowyg('html', '');

    limpiarErrores();
};
const readPdf = (isEdit = false) => {
    pdfjsLib.GlobalWorkerOptions.workerSrc = PDF_WORKER;

    $('#norma_urlPdf').on('change', function (event) {
        const file = event.target.files[0];
        if (!file) return;

        // Limpiar el contenido actual del texto
        if (isEdit) {
            // Mostrar SweetAlert2 para preguntar al usuario en modo EDIT
            Swal.fire({
                title: '¿Desea leer el documento PDF?',
                text: 'Esto puede demorar dependiendo del tamaño del archivo.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, leer',
                cancelButtonText: 'No, solo subir archivo',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario elige "Sí, leer", comienza la lectura del PDF
                    leerPdf(file);
                } else {
                    // Si elige "No, solo subir archivo"
                    Swal.fire({
                        title: 'Archivo subido',
                        text: 'El archivo se ha subido correctamente sin ser leído.',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false,
                    });
                }
            });
        } else {
            // Si es una acción NEW, lee el archivo automáticamente
            leerPdf(file);
        }
    });
};

const leerPdf = (file) => {
    limpiarTexto();
    // Mostrar spinner
    $('#pdf-loading-spinner').removeClass('d-none');
    $('#current-page').text('0'); // Resetea la página actual
    $('#total-pages').text('0'); // Resetea el total de páginas

    const reader = new FileReader();
    reader.onload = function (e) {
        const pdfData = new Uint8Array(e.target.result);

        // Utilizamos PDF.js para extraer las páginas
        pdfjsLib.getDocument({ data: pdfData }).promise.then(function (pdf) {
            const totalPages = pdf.numPages;
            let currentPage = 1;

            // Actualizar el total de páginas en el spinner
            $('#total-pages').text(totalPages);

            const processPage = () => {
                if (currentPage > totalPages) {
                    // Ocultar spinner cuando termina
                    $('#pdf-loading-spinner').addClass('d-none');
                    return;
                }

                // Actualizar la página actual en el spinner
                $('#current-page').text(currentPage);

                pdf.getPage(currentPage).then(function (page) {
                    const viewport = page.getViewport({ scale: 2.0 });
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    return page.render({ canvasContext: context, viewport }).promise.then(() => {
                        const imgData = context.getImageData(0, 0, canvas.width, canvas.height);

                        // Usar Tesseract.js para extraer texto
                        return Tesseract.recognize(canvas, 'spa', {
                            tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzÁÉÍÓÚÑáéíóúñ ',
                            preserve_interword_spaces: '1',
                            logger: info => console.log(info), // Progreso del OCR
                        }).then(({ data: { text } }) => {
                            // Agregar texto extraído al textarea
                            const textarea = $('#norma_textoCompleto');
                            textarea.val((textarea.val() || '') + `\n\n${text}`);

                            // Actualizar Trumbowyg
                            $('#norma_textoCompletoHtml').trumbowyg('html', textarea.val());

                            currentPage++;
                            processPage(); // Procesar la siguiente página
                        });
                    });
                }).catch((err) => {
                    console.error(`Error procesando la página ${currentPage}:`, err);
                    currentPage++;
                    processPage(); // Continuar con la siguiente página en caso de error
                });
            };

            // Iniciar procesamiento incremental
            processPage();
        }).catch((error) => {
            console.error('Error al leer el archivo PDF:', error);
            alert('Ocurrió un error al leer el archivo PDF.');
            $('#pdf-loading-spinner').addClass('d-none');
        });
    };

    reader.readAsArrayBuffer(file);
};

const limpiarTexto = () => {
    // Limpiar el contenido del textarea y del editor Trumbowyg
    $('#norma_textoCompleto').val('');
    $('#norma_textoCompletoHtml').trumbowyg('empty');
};
