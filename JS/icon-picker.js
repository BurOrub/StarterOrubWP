/* Selettore di icone per il campo ACF "Icona" (solo backend). Una finestra con ricerca e griglia, condivisa da tutti i campi. */
( function () {
	'use strict';

	var cfg = window.StarterIconPicker;
	if ( ! cfg ) { return; }

	var T = cfg.i18n || {};
	var lib = null;       // { icons: { nome: markup }, keywords: { nome: "parole" } }
	var names = [];       // tutti i nomi, in ordine
	var modal, grid, search, countEl, nullBtn;
	var current = null;   // campo (.starter-icon-field) che sta scegliendo
	var filter = 'all';   // all | lines | brands
	var shown = 0;
	var results = [];
	var CHUNK = 150;

	// Disegna un'icona (della libreria o SVG caricato) come stringa SVG
	function svg( name, size ) {
		var inner = lib.icons[ name ] || '';
		var brand = name.indexOf( 'brand-' ) === 0;
		var custom = lib.svgs && lib.svgs[ name ];
		var vb = '0 0 24 24';
		var paint = brand ? 'fill="currentColor" stroke="none"' : 'fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"';
		if ( custom ) { // SVG caricato dall'utente: tiene il suo viewBox e i suoi colori
			vb = custom.vb;
			paint = Object.keys( custom.paint ).map( function ( k ) { return k + '="' + String( custom.paint[ k ] ).replace( /"/g, '&quot;' ) + '"'; } ).join( ' ' );
		}
		return '<svg width="' + size + '" height="' + size + '" viewBox="' + vb + '" ' + paint + ' aria-hidden="true">' + inner + '</svg>';
	}

	// Crea un elemento con classe e testo
	function el( tag, cls, text ) {
		var n = document.createElement( tag );
		if ( cls ) { n.className = cls; }
		if ( text ) { n.textContent = text; }
		return n;
	}

	// Costruisce la finestra del selettore (una volta sola)
	function build() {
		if ( modal ) { return; }

		modal = el( 'div', 'sip' );
		modal.hidden = true;
		modal.innerHTML =
			'<div class="sip__backdrop"></div>' +
			'<div class="sip__box" role="dialog" aria-modal="true" aria-label="' + T.title + '">' +
				'<div class="sip__head"><strong>' + T.title + '</strong><button type="button" class="sip__x" aria-label="' + T.close + '">×</button></div>' +
				'<div class="sip__tools">' +
					'<input type="search" class="sip__search" placeholder="' + T.search + '">' +
					'<div class="sip__tabs"><button type="button" data-f="all" class="is-on">' + T.all + '</button><button type="button" data-f="lines">' + T.lines + '</button><button type="button" data-f="brands">' + T.brands + '</button>' + ( cfg.canSvg ? '<button type="button" data-f="mine">' + T.mine + '</button>' : '' ) + '</div>' + ( cfg.canSvg ? '<button type="button" class="button sip__upload">' + T.upload + '</button>' : '' ) +
				'</div>' +
				'<div class="sip__grid"></div>' +
				'<div class="sip__foot"><span class="sip__count"></span><button type="button" class="button sip__none">' + T.none + '</button></div>' +
			'</div>';
		document.body.appendChild( modal );

		grid = modal.querySelector( '.sip__grid' );
		search = modal.querySelector( '.sip__search' );
		countEl = modal.querySelector( '.sip__count' );
		nullBtn = modal.querySelector( '.sip__none' );
		var uploadBtn = modal.querySelector( '.sip__upload' );
		if ( uploadBtn ) { uploadBtn.addEventListener( 'click', uploadSvg ); }

		modal.querySelector( '.sip__backdrop' ).addEventListener( 'click', close );
		modal.querySelector( '.sip__x' ).addEventListener( 'click', close );
		search.addEventListener( 'input', refresh );
		modal.querySelectorAll( '.sip__tabs button' ).forEach( function ( b ) {
			b.addEventListener( 'click', function () {
				filter = b.getAttribute( 'data-f' );
				modal.querySelectorAll( '.sip__tabs button' ).forEach( function ( o ) { o.classList.toggle( 'is-on', o === b ); } );
				refresh();
			} );
		} );
		grid.addEventListener( 'scroll', function () {
			if ( grid.scrollTop + grid.clientHeight >= grid.scrollHeight - 200 ) { more(); }
		} );
		grid.addEventListener( 'click', function ( e ) {
			var b = e.target.closest( '.sip__item' );
			if ( b ) { choose( b.getAttribute( 'data-name' ) ); }
		} );
		nullBtn.addEventListener( 'click', function () { choose( '' ); } );
		document.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && ! modal.hidden ) { close(); }
		} );
	}

	// Aggiunge un altro gruppo di icone alla griglia
	function more() {
		var end = Math.min( results.length, shown + CHUNK );
		var html = '';
		for ( var i = shown; i < end; i++ ) {
			var n = results[ i ];
			html += '<button type="button" class="sip__item" data-name="' + n + '" title="' + n + '">' + svg( n, 26 ) + '<span>' + n.replace( 'brand-', '' ) + '</span></button>';
		}
		grid.insertAdjacentHTML( 'beforeend', html );
		shown = end;
	}

	// Filtra per scheda e ricerca, ordina per pertinenza e ridisegna la griglia
	function refresh() {
		var terms = search.value.toLowerCase().trim().split( /\s+/ ).filter( Boolean );
		results = names.filter( function ( n ) {
			var brand = n.indexOf( 'brand-' ) === 0;
			var mine = n.indexOf( 'svg-' ) === 0;
			if ( ( filter === 'brands' && ! brand ) || ( filter === 'lines' && ( brand || mine ) ) || ( filter === 'mine' && ! mine ) ) { return false; }
			var hay = ( lib.keywords[ n ] || n ) + ' ' + n;
			return terms.every( function ( t ) { return hay.indexOf( t ) !== -1; } );
		} );
		// Prima le corrispondenze nel nome (esatta, poi che inizia con, poi che contiene), poi quelle nelle parole chiave.
		if ( terms.length ) {
			var q = terms.join( '-' );
			// Punteggio di pertinenza: nome esatto, poi inizia con, poi contiene
			var rank = function ( n ) { return n === q || n === 'brand-' + q ? 0 : ( n.indexOf( q ) === 0 || n.indexOf( 'brand-' + q ) === 0 ? 1 : ( n.indexOf( terms[ 0 ] ) !== -1 ? 2 : 3 ) ); };
			results = results.map( function ( n, i ) { return { n: n, r: rank( n ), i: i }; } )
				.sort( function ( a, b ) { return a.r - b.r || a.i - b.i; } )
				.map( function ( o ) { return o.n; } );
		}
		// "I miei SVG": i più recenti per primi.
		if ( filter === 'mine' && lib.custom ) {
			results.sort( function ( a, b ) { return lib.custom.indexOf( a ) - lib.custom.indexOf( b ); } );
		}
		grid.innerHTML = '';
		shown = 0;
		grid.scrollTop = 0;
		if ( ! results.length ) { grid.appendChild( el( 'p', 'sip__empty', T.empty ) ); }
		more();
		countEl.textContent = results.length + ' ' + T.count;
	}

	/* "Carica SVG": Libreria media filtrata sugli SVG (con la scheda di caricamento). L'SVG scelto diventa un'icona "svg-ID". */
	function uploadSvg() {
		if ( ! window.wp || ! wp.media ) { return; }
		var frame = wp.media( { title: T.uploadTitle, button: { text: T.useSvg }, multiple: false, library: { type: 'image/svg+xml' } } );
		frame.on( 'select', function () {
			var a = frame.state().get( 'selection' ).first().toJSON();
			fetch( cfg.restSvg + a.id, { credentials: 'same-origin', headers: { 'X-WP-Nonce': cfg.nonce } } )
				.then( function ( r ) { return r.json(); } )
				.then( function ( d ) {
					if ( ! d || ! d.name ) { window.alert( ( d && d.message ) || 'SVG non valido' ); return; }
					lib.icons[ d.name ] = d.inner;
					lib.svgs = lib.svgs || {};
					lib.svgs[ d.name ] = { vb: d.vb, paint: d.paint };
					lib.keywords[ d.name ] = d.keywords;
					if ( names.indexOf( d.name ) === -1 ) { names.unshift( d.name ); }
					choose( d.name );
				} );
		} );
		frame.open();
	}

	// Apre il selettore per un campo
	function open( field ) {
		current = field;
		build();
		nullBtn.hidden = field.getAttribute( 'data-allow-null' ) !== '1';
		modal.hidden = false;
		document.documentElement.classList.add( 'sip-open' );

		if ( lib ) { refresh(); search.focus(); return; }

		grid.innerHTML = '';
		grid.appendChild( el( 'p', 'sip__empty', T.loading ) );
		fetch( cfg.rest, { credentials: 'same-origin', headers: { 'X-WP-Nonce': cfg.nonce } } )
			.then( function ( r ) { return r.json(); } )
			.then( function ( d ) {
				lib = d;
				names = Object.keys( d.icons );
				refresh();
				search.focus();
			} );
	}

	// Chiude il selettore
	function close() {
		if ( modal ) { modal.hidden = true; }
		document.documentElement.classList.remove( 'sip-open' );
		current = null;
	}

	// Scrive l'icona scelta nel campo e nell'anteprima
	function choose( name ) {
		if ( ! current ) { return; }
		var input = current.querySelector( '.starter-icon-field__input' );
		input.value = name;
		current.querySelector( '.starter-icon-field__preview' ).innerHTML = name ? svg( name, 24 ) : '';
		current.querySelector( '.starter-icon-field__name' ).textContent = name || T.choose;
		var clr = current.querySelector( '.starter-icon-field__clear' );
		if ( clr ) { clr.hidden = ! name; }
		// ACF (e il blocco nell'editor) si accorgono della modifica dall'evento "change".
		input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
		close();
	}

	document.addEventListener( 'click', function ( e ) {
		var openBtn = e.target.closest && e.target.closest( '.starter-icon-field__open' );
		if ( openBtn ) {
			e.preventDefault();
			open( openBtn.closest( '.starter-icon-field' ) );
			return;
		}
		var clearBtn = e.target.closest && e.target.closest( '.starter-icon-field__clear' );
		if ( clearBtn ) {
			e.preventDefault();
			current = clearBtn.closest( '.starter-icon-field' );
			if ( ! lib ) {
				var input = current.querySelector( '.starter-icon-field__input' );
				input.value = '';
				current.querySelector( '.starter-icon-field__preview' ).innerHTML = '';
				current.querySelector( '.starter-icon-field__name' ).textContent = T.choose;
				clearBtn.hidden = true;
				input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
				current = null;
			} else { choose( '' ); }
		}
	} );
} )();
