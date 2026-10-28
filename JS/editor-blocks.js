/**
 * Editor di blocchi: mostra sui blocchi del tema lo "Sfondo della sezione" e le "Classi CSS personalizzate" (scheda Avanzate),
 * come sul sito. Il frontend le applica in PHP (inc/block-options.php, filtro render_block); qui si replica sul contenitore del blocco.
 * window.starterEditorBg = { slots: { slot_N: { value, dark } }, legacy: { vecchiaChiave: 'slot_N' } }.
 */
( function ( wp, config ) {
	if ( ! wp || ! wp.hooks || ! config ) {
		return;
	}

	var el = wp.element.createElement;

	wp.hooks.addFilter(
		'editor.BlockListBlock',
		'starter/section-advanced',
		wp.compose.createHigherOrderComponent( function ( BlockListBlock ) {
			return function ( props ) {
				var data = props.attributes && props.attributes.data;

				if ( ! data || 0 !== String( props.name ).indexOf( 'starter-theme/' ) ) {
					return el( BlockListBlock, props );
				}

				var classes = [];
				var style = {};
				var bg = data.sfondo_sezione ? String( data.sfondo_sezione ) : '';

				bg = config.legacy[ bg ] || bg;

				if ( 'chiaro' === bg ) {
					classes.push( 'bg-sezione', 'bg-sezione--chiaro' );
				} else if ( config.slots[ bg ] ) {
					classes.push( 'bg-sezione', config.slots[ bg ].dark ? 'bg-sezione--scuro' : 'bg-sezione--chiaro-testo' );
					style.backgroundColor = config.slots[ bg ].value;
				}

				if ( data.classi_custom && 'string' === typeof data.classi_custom ) {
					data.classi_custom.trim().split( /\s+/ ).forEach( function ( name ) {
						name = name.replace( /[^A-Za-z0-9_:\-]/g, '' );

						if ( name ) {
							classes.push( name );
						}
					} );
				}

				if ( ! classes.length ) {
					return el( BlockListBlock, props );
				}

				var next = Object.assign( {}, props, {
					className: [ props.className ].concat( classes ).filter( Boolean ).join( ' ' ),
					wrapperProps: Object.assign( {}, props.wrapperProps, {
						style: Object.assign( {}, props.wrapperProps && props.wrapperProps.style, style )
					} )
				} );

				return el( BlockListBlock, next );
			};
		}, 'withStarterSectionAdvanced' )
	);
}( window.wp, window.starterEditorBg ) );
