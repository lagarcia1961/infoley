/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');
		// Obtener el valor almacenado en localStorage

let themeDark = localStorage.getItem('themeDark');

		// Aplicar el tema si está almacenado
		if (themeDark === 'dark') {
			$('body').attr('data-layout-mode', 'dark');
			$('body').attr('data-topbar', 'dark');
			$('body').attr('data-sidebar', 'dark');
		}

		// Manejar el botón de cambio de modo
		$('#mode-setting-btn').on('click', function () {
			if (themeDark === 'dark') {
				// Eliminar el tema oscuro
				localStorage.removeItem('themeDark');
				themeDark = null;
				// Puedes también revertir los estilos aquí si es necesario
			} else {
				// Establecer el tema oscuro
				localStorage.setItem('themeDark', 'dark');
				themeDark = 'dark';
				// Aplicar los estilos para el tema oscuro aquí si es necesario
			}
		});
