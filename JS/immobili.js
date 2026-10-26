/**
 * Script del modulo Immobili (caricato solo sulle pagine con la scheda, l'elenco o i blocchi degli immobili).
 * Download delle planimetrie. I filtri dell'elenco usano il JS comune dei filtri (JS/scripts.js, initFilters).
 * Lightbox, frecce delle gallerie, trascinamento e comparsa allo scroll sono nel JS principale del tema.
 */
( function () {
	'use strict';

	/** Scarica tutte le planimetrie (un file per volta) quando sono più di una. */
	function initPlanDownload() {
		var link = document.querySelector( '[data-plan-files]' );

		if ( ! link ) {
			return;
		}

		var files = JSON.parse( link.getAttribute( 'data-plan-files' ) || '[]' );

		if ( files.length < 2 ) {
			return;
		}

		link.addEventListener( 'click', function ( event ) {
			event.preventDefault();

			files.forEach( function ( url, i ) {
				window.setTimeout( function () {
					var a = document.createElement( 'a' );
					a.href = url;
					a.download = '';
					document.body.appendChild( a );
					a.click();
					a.remove();
				}, i * 400 );
			} );
		} );
	}

	// Avvia le funzioni del modulo
	function init() {
		initPlanDownload();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
