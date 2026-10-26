/**
 * Animazioni del tema: scroll fluido (Lenis).
 * Caricato solo se l'opzione "Scroll fluido" è attiva da Personalizza → Opzioni → Generale
 * (vedi starter_enqueue_animation_assets() in functions.php). Nessuna libreria di animazione: Lenis gira su requestAnimationFrame.
 */
( function () {
	'use strict';

	var settings = window.starterAnimations || {};
	var prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	if ( settings.smoothScroll && ! prefersReducedMotion ) {
		document.addEventListener( 'DOMContentLoaded', initSmoothScroll );
	}

	/** Scroll fluido con Lenis. */
	function initSmoothScroll() {
		if ( typeof Lenis === 'undefined' ) {
			return;
		}

		var lenis = new Lenis( {
			duration: 1.1,
			smoothWheel: true,
		} );

		// Serve a chi deve fermare la pagina (pannello filtri aperto): vedi lockPage() in JS/scripts.js.
		window.starterLenis = lenis;

		// Fa avanzare Lenis a ogni frame
		function raf( time ) {
			lenis.raf( time );
			window.requestAnimationFrame( raf );
		}

		window.requestAnimationFrame( raf );
	}
} )();
