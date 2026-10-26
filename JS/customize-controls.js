/**
 * Script solo-admin per il pannello Personalizza: rivela progressivamente
 * gruppi di controlli nascosti di default (icone social personalizzate,
 * font personalizzati, varianti di un font) ad ogni click sul relativo
 * bottone "+ Aggiungi...", e li nasconde di nuovo (svuotandoli) al click
 * su "Rimuovi...".
 *
 * Nota sul caching: questo file viene enqueued con una versione basata
 * su filemtime() (vedi starter_customize_controls_enqueue_scripts() in
 * functions.php) apposta perché, modificandolo spesso durante lo
 * sviluppo, una versione fissa farebbe servire al browser una copia in
 * cache anche dopo un salvataggio — è già successo una volta.
 */
( function ( $ ) {
	'use strict';

	/**
	 * Un "gruppo" è un bottone "+ Aggiungi..." più un elenco di "slot" da
	 * rivelare uno alla volta. Ogni slot ha:
	 * - ids: TUTTI gli ID controllo che appartengono allo slot (nascosti/
	 *   rivelati insieme).
	 * - valueIds: il sottoinsieme di "ids" da guardare per capire se lo
	 *   slot ha già un valore reale al caricamento della pagina (quindi
	 *   va mostrato subito, non nascosto) — VOLUTAMENTE più corto di
	 *   "ids": esclude i menu a tendina peso/stile, che hanno sempre un
	 *   valore di default non vuoto ("400"/"normal") anche quando nessun
	 *   file è stato caricato, e quindi darebbero un falso positivo.
	 * - removeButtonId: (facoltativo) l'ID del bottone "Rimuovi..." di
	 *   questo slot, se presente — al click svuota tutti gli "ids" (tranne
	 *   se stesso) e nasconde di nuovo lo slot.
	 *
	 * @param {string} buttonSelector Selettore CSS del bottone "+ Aggiungi...".
	 * @param {Array<{ids: string[], valueIds: string[], removeButtonId?: string}>} slots
	 */
	function initRevealGroup( buttonSelector, slots ) {
		try {
			var $button = $( buttonSelector );

			if ( ! $button.length ) {
				return;
			}

			// Elementi del pannello di uno slot di campi
			var slotElements = function ( ids ) {
				return $(
					ids
						.map( function ( id ) {
							return '#customize-control-' + id;
						} )
						.join( ', ' )
				);
			};

			// Vero se almeno un campo dello slot ha un valore
			var slotHasValue = function ( slot ) {
				return slot.valueIds.some( function ( id ) {
					try {
						var control = wp.customize.control( id );

						if ( ! control || typeof control.setting !== 'function' ) {
							return false;
						}

						return Boolean( control.setting() );
					} catch ( error ) {
						return false;
					}
				} );
			};

			// Primo slot ancora nascosto (da mostrare con "Aggiungi")
			var firstHiddenSlot = function () {
				for ( var i = 0; i < slots.length; i++ ) {
					if ( slotElements( slots[ i ].ids ).is( ':hidden' ) ) {
						return slots[ i ];
					}
				}

				return null;
			};

			// Mostra il pulsante "Aggiungi" solo se resta uno slot nascosto
			var updateButtonVisibility = function () {
				$button.closest( '.customize-control' ).toggle( firstHiddenSlot() !== null );
			};

			/**
			 * Riporta ogni ID di uno slot al proprio valore "vuoto"
			 * (dedotto dal suffisso del nome, per come sono nominati i
			 * campi in customize_register()) e nasconde di nuovo lo slot.
			 */
			var resetValueForId = function ( id ) {
				if ( /_(ttf|woff|woff2|eot|svg)$/.test( id ) ) {
					return 0;
				}

				if ( /_weight$/.test( id ) ) {
					return '400';
				}

				if ( /_style$/.test( id ) ) {
					return 'normal';
				}

				return '';
			};

			// Svuota e nasconde di nuovo uno slot
			var resetAndHideSlot = function ( slot ) {
				slot.ids.forEach( function ( id ) {
					if ( id === slot.removeButtonId ) {
						return;
					}

					try {
						var control = wp.customize.control( id );

						if ( ! control ) {
							return;
						}

						if ( control.setting && typeof control.setting.set === 'function' ) {
							control.setting.set( resetValueForId( id ) );
						} else if ( control.settings ) {
							// Controlli con più "settings" indipendenti (es. il
							// gruppo di checkbox "Applica a"): azzera ognuna.
							Object.keys( control.settings ).forEach( function ( key ) {
								control.settings[ key ].set( false );
							} );
						}
					} catch ( error ) {
						// Un singolo controllo che non risponde non deve
						// bloccare il reset degli altri.
					}
				} );

				slotElements( slot.ids ).hide();
			};

			slots.forEach( function ( slot ) {
				if ( ! slotHasValue( slot ) ) {
					slotElements( slot.ids ).hide();
				}

				if ( slot.removeButtonId ) {
					slotElements( [ slot.removeButtonId ] )
						.find( 'button' )
						.on( 'click', function ( event ) {
							event.preventDefault();
							resetAndHideSlot( slot );
							updateButtonVisibility();
						} );
				}
			} );

			updateButtonVisibility();

			$button.on( 'click', function ( event ) {
				event.preventDefault();

				var next = firstHiddenSlot();

				if ( next ) {
					slotElements( next.ids ).slideDown( 150 );
				}

				updateButtonVisibility();
			} );
		} catch ( error ) {
			// Un gruppo andato in errore non deve impedire agli altri
			// gruppi (chiamati dopo, nello stesso "ready") di funzionare.
			if ( window.console && window.console.error ) {
				window.console.error( 'starter-theme: initRevealGroup fallito per ' + buttonSelector, error );
			}
		}
	}

	/**
	 * Lo slot "sempre insieme" di un font personalizzato (Font 3 o Font
	 * 4), rivelato dal proprio bottone dedicato
	 * ".starter-add-custom-font-button-{n}" (non condiviso con l'altro
	 * font — ciascuno ha titolo + bottone sempre visibili, il resto
	 * nascosto finché non si preme il bottone): nome, la prima variante
	 * (peso/stile + 5 upload), il bottone "+ Aggiungi variante", il
	 * gruppo di checkbox "Applica a" e il bottone "Rimuovi questo font".
	 * Le varianti 2-4 NON sono incluse: restano gestite (e nascoste) dal
	 * proprio slot, vedi variantSlots() più sotto.
	 */
	function customFontSlot( fontNumber ) {
		var prefix = 'starter_font' + fontNumber;

		// Non include "starter_heading_font{n}": il titolo resta sempre
		// visibile, insieme al bottone "+ Aggiungi Font {n}" (quello è
		// l'elemento che triggera la rivelazione, gestito a parte da
		// initRevealGroup — non va incluso anche negli "ids" da nascondere).
		var ids = [
			prefix + '_name',
			prefix + '_v1_weight',
			prefix + '_v1_style',
			prefix + '_v1_ttf',
			prefix + '_v1_woff',
			prefix + '_v1_woff2',
			prefix + '_v1_eot',
			prefix + '_v1_svg',
			prefix + '_add_variant',
			prefix + '_elements',
			prefix + '_remove',
		];

		return {
			ids: ids,
			valueIds: [ prefix + '_name', prefix + '_v1_ttf', prefix + '_v1_woff', prefix + '_v1_woff2', prefix + '_v1_eot', prefix + '_v1_svg' ],
			removeButtonId: prefix + '_remove',
		};
	}

	/**
	 * Un font caricato (Font 3/Font 4) può avere fino a 4 varianti
	 * peso/stile: solo la prima è visibile di default (vedi
	 * customFontSlot() sopra), le altre 3 (v2..v4) sono rivelate una
	 * alla volta dal bottone ".starter-add-font-variant-button-{n}", e
	 * ciascuna ha il proprio bottone "Rimuovi questa variante".
	 */
	function variantSlots( fontNumber ) {
		var prefix = 'starter_font' + fontNumber;
		var uploadFormats = [ 'ttf', 'woff', 'woff2', 'eot', 'svg' ];
		var slots = [];

		[ 2, 3, 4 ].forEach( function ( variant ) {
			var variantPrefix = prefix + '_v' + variant;

			slots.push( {
				ids: [ variantPrefix + '_weight', variantPrefix + '_style' ]
					.concat( uploadFormats.map( function ( format ) { return variantPrefix + '_' + format; } ) )
					.concat( [ variantPrefix + '_remove' ] ),
				valueIds: uploadFormats.map( function ( format ) {
					return variantPrefix + '_' + format;
				} ),
				removeButtonId: variantPrefix + '_remove',
			} );
		} );

		return slots;
	}

	/**
	 * Sezione "Colori": 4 gruppi (Tavolozza, Sfondi/testi/link, Menu/footer/social, Tag) che si aprono e chiudono come una fisarmonica.
	 * I titoli dei gruppi sono i controlli "starter_heading_cgroup_*"; i controlli che li seguono (fino al titolo successivo) sono il gruppo.
	 * Si nasconde con una classe (non con style.display) per non disturbare gli slot che il pulsante "+ Aggiungi colore" rivela da sé.
	 */
	function initColorGroups() {
		var section = wp.customize.section( 'starter_options_colors' );

		if ( ! section ) {
			return;
		}

		var ids = [ 'palette', 'site', 'nav', 'tags' ];
		var built = false;

		// Crea i gruppi a fisarmonica dei colori
		function build() {
			var heads = ids.map( function ( id ) {
				return document.getElementById( 'customize-control-starter_heading_cgroup_' + id );
			} );

			if ( built || heads.some( function ( h ) { return ! h; } ) ) {
				return;
			}

			built = true;

			var groups = heads.map( function ( head, index ) {
				var items = [];
				var node = head.nextElementSibling;

				while ( node && heads.indexOf( node ) === -1 ) {
					items.push( node );
					node = node.nextElementSibling;
				}

				head.classList.add( 'starter-cgroup-head' );

				return { head: head, items: items, index: index };
			} );

			// Apre un gruppo e chiude gli altri
			function open( group ) {
				groups.forEach( function ( g ) {
					var isOpen = g === group;

					g.head.classList.toggle( 'is-open', isOpen );
					g.items.forEach( function ( item ) {
						item.classList.toggle( 'starter-cgroup-closed', ! isOpen );
					} );
				} );
			}

			groups.forEach( function ( group ) {
				group.head.addEventListener( 'click', function () {
					open( group.head.classList.contains( 'is-open' ) ? null : group );
				} );
			} );

			open( groups[ 0 ] );
		}

		section.container.on( 'expanded', build );

		if ( section.expanded() ) {
			build();
		}
	}

	wp.customize.bind( 'ready', function () {
		initColorGroups();

		// Icone social personalizzate: solo la prima visibile di default,
		// le altre 4 rivelate una alla volta.
		var iconSlots = [];

		for ( var i = 2; i <= 5; i++ ) {
			var iconIds = [ 'starter_social_custom_' + i + '_svg', 'starter_social_custom_' + i + '_url' ];
			iconSlots.push( { ids: iconIds, valueIds: iconIds } );
		}

		initRevealGroup( '.starter-add-icon-button', iconSlots );

		// Font 3 e Font 4: due sezioni indipendenti, ciascuna col proprio
		// bottone dedicato (non un bottone condiviso che le rivela in
		// sequenza) — così restano ben separate.
		initRevealGroup( '.starter-add-custom-font-button-3', [ customFontSlot( 3 ) ] );
		initRevealGroup( '.starter-add-custom-font-button-4', [ customFontSlot( 4 ) ] );

		// Varianti di Font 3 e di Font 4 (indipendenti tra loro).
		initRevealGroup( '.starter-add-font-variant-button-3', variantSlots( 3 ) );
		initRevealGroup( '.starter-add-font-variant-button-4', variantSlots( 4 ) );

		// Colori con nome: i primi 5 slot sono sempre visibili (registrati
		// così lato PHP, vedi customize_register()), solo dal 6° in poi (fino al 10°) sono
		// rivelati uno alla volta qui, ciascuno col proprio "Rimuovi
		// questo colore" (svuota nome+valore e lo nasconde di nuovo).
		var namedColorSlots = [];

		for ( var n = 6; n <= 10; n++ ) { // 5 sempre visibili + fino a 5 a comparsa (STARTER_COLOR_SLOTS in functions.php)
			var namedColorIds = [ 'starter_named_color_' + n + '_name', 'starter_named_color_' + n + '_value', 'starter_named_color_' + n + '_remove' ];
			namedColorSlots.push( { ids: namedColorIds, valueIds: [ 'starter_named_color_' + n + '_value' ], removeButtonId: 'starter_named_color_' + n + '_remove' } );
		}

		initRevealGroup( '.starter-add-named-color-button', namedColorSlots );
	} );
} )( jQuery );
