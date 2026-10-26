/** Script frontend del tema. */
( function () {
	'use strict';

	// Subito, non in DOMContentLoaded: "pagereveal" arriva prima del primo disegno della pagina.
	initProjectTransition();

	document.addEventListener( 'DOMContentLoaded', function () {
		initStickyHeader();
		initHamburgerMenu();
		initBlogSidebarToggle();
		initFooterSocialMagnetic();
		initReveal();
		initScrollTracks();
		initDragScroll();
		initReviewsLoop();
		initLightbox();
		initWhatsappCompact();
		initSubmenus();
		initWishlist();
		initWishlistTip();
		initWishlistPage();
		initCartBadge();
		initFilters();
		initProjectVideos();
		initFooterFit();
		initProjectFilters();
		centerActiveFilter( document );
		initQuantityButtons();
		initServicesSpy();
		initServicesDock();
		initServicesLabelScroll();
		initMenuLabelScroll();
		initFlipButtons();
		initProductWish();
		initHeroSlider();
		initSliders();
		initSearchPanel();
		initLangSwitcher();
		initTopBar();
		initAutoplayVideos();
	} );

	/** Nascondersi/riapparire su scroll solo se l'header non è flottante. */
	function initStickyHeader() {
		var header = document.getElementById( 'site-header' );

		if ( ! header ) {
			return;
		}

		var isFloating = document.body.classList.contains( 'header-floating' ) || document.body.classList.contains( 'header-pills' ); // Floating e Pillole non si nascondono scorrendo.
		var lastScrollY = window.scrollY;
		var scrolledThreshold = 10;
		var hideAfter = header.offsetHeight;
		var ticking = false;

		// Aggiunge o toglie is-scrolled in base alla posizione di scroll
		function updateHeader() {
			var currentScrollY = window.scrollY;

			header.classList.toggle( 'is-scrolled', currentScrollY > scrolledThreshold );

			// "is-scrolling-up": true solo mentre si risale, per l'effetto vetro dell'header non flottante (vedi CSS).
			header.classList.toggle( 'is-scrolling-up', currentScrollY < lastScrollY && currentScrollY > scrolledThreshold );

			if ( ! isFloating ) {
				if ( currentScrollY > lastScrollY && currentScrollY > hideAfter ) {
					header.classList.add( 'is-hidden' );
				} else if ( currentScrollY < lastScrollY ) {
					header.classList.remove( 'is-hidden' );
				}
			}

			lastScrollY = currentScrollY;
			ticking = false;
		}

		window.addEventListener(
			'scroll',
			function () {
				if ( ! ticking ) {
					window.requestAnimationFrame( updateHeader );
					ticking = true;
				}
			},
			{ passive: true }
		);
	}

	/** Toggle hamburger: pannello mobile è ancora una base da rifinire, qui solo la logica. */
	function initHamburgerMenu() {
		var toggle = document.getElementById( 'hamburger-toggle' );

		if ( ! toggle ) {
			return;
		}

		// Aggiorna entrambi gli span del testo (originale + duplicato hover), non l'intero bottone.
		var textEls = toggle.querySelectorAll( '.button__text' );
		var openText = textEls.length ? textEls[ 0 ].textContent : '';
		var closeText = toggle.getAttribute( 'data-close-text' ) || 'Chiudi';

		// Apre/chiude il menu mobile (classe menu-open e aria-expanded)
		function setOpen( isOpen ) {
			document.body.classList.toggle( 'menu-open', isOpen );
			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );

			textEls.forEach( function ( el ) {
				el.textContent = isOpen ? closeText : openText;
			} );
		}

		toggle.addEventListener( 'click', function () {
			setOpen( ! document.body.classList.contains( 'menu-open' ) );
		} );

		document.querySelectorAll( '.main-navigation a, .site-header__panel-links a' ).forEach( function ( link ) {
			link.addEventListener( 'click', function () {
				setOpen( false );
			} );
		} );

		// Clic fuori dal pannello e dal bottone: chiude il menu.
		document.addEventListener( 'click', function ( event ) {
			if ( document.body.classList.contains( 'menu-open' ) && ! event.target.closest( '#site-header-nav-wrap, #hamburger-toggle' ) ) {
				setOpen( false );
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && document.body.classList.contains( 'menu-open' ) ) {
				setOpen( false );
			}
		} );

		// Tornando a una finestra larga il pannello si richiude da solo.
		window.matchMedia( '(min-width: 1181px)' ).addEventListener( 'change', function ( query ) {
			if ( query.matches && ! document.body.classList.contains( 'header-minimal' ) && document.body.classList.contains( 'menu-open' ) ) {
				setOpen( false );
			}
		} );
	}

	/**
	 * Ricerca: la lente dell'header apre un pannello a tutta larghezza; mentre si scrive mostra l'anteprima dei
	 * risultati (prodotti, pagine, articoli) dall'endpoint REST del tema (inc/search.php). Si chiude con Esc, click
	 * sullo sfondo o la X, e il focus torna alla lente. Invio porta alla pagina dei risultati: il form funziona anche senza JS.
	 */
	function initSearchPanel() {
		var panel = document.getElementById( 'search-panel' );
		var openers = document.querySelectorAll( '[data-search-open]' );

		if ( ! panel || ! openers.length ) {
			return;
		}

		var cfg = window.starterSearch || {};
		var t = cfg.i18n || {};
		var input = panel.querySelector( 'input[type="search"]' );
		var form = panel.querySelector( 'form' );
		var clearBtn = panel.querySelector( '[data-search-clear]' );
		var results = document.getElementById( 'search-results' );
		var lastOpener = null;
		var timer = null;
		var controller = null;
		var cache = {};

		// Crea un elemento con classe e testo
		function el( tag, className, text ) {
			var node = document.createElement( tag );

			if ( className ) {
				node.className = className;
			}

			if ( text ) {
				node.textContent = text;
			}

			return node;
		}

		// Costruisce un gruppo di risultati (titolo + elenco)
		function renderGroup( group ) {
			var section = el( 'section', 'search-results__group search-results__group--' + group.type );
			var list = el( 'ul', 'search-results__list search-results__list--' + group.type );

			section.appendChild( el( 'h3', 'search-results__title', group.label || '' ) );

			group.items.forEach( function ( item ) {
				var li = document.createElement( 'li' );
				var a = el( 'a', 'search-results__item' );
				var body = el( 'span', 'search-results__body' );

				a.href = item.url;

				// Le pagine senza immagine non hanno miniatura; gli altri tipi tengono lo spazio.
				if ( item.image || 'page' !== group.type ) {
					var thumb = el( 'span', 'search-results__thumb' );

					if ( item.image ) {
						var img = document.createElement( 'img' );
						img.src = item.image;
						img.alt = '';
						img.loading = 'lazy';
						thumb.appendChild( img );
					}

					a.appendChild( thumb );
				}

				body.appendChild( el( 'span', 'search-results__name', item.title ) );

				if ( item.sub ) {
					body.appendChild( el( 'span', 'search-results__price', item.sub ) );
				}

				a.appendChild( body );
				li.appendChild( a );
				list.appendChild( li );
			} );

			section.appendChild( list );

			return section;
		}

		// Disegna i risultati nel pannello
		function render( data, query ) {
			results.textContent = '';
			panel.classList.remove( 'is-loading' );

			if ( ! data || ! data.groups || ! data.groups.length ) {
				results.appendChild( el( 'p', 'search-results__empty', ( t.empty || '' ) + ' “' + query + '”' ) );
				return;
			}

			var wrap = el( 'div', 'search-results__wrap' );

			data.groups.forEach( function ( group ) {
				wrap.appendChild( renderGroup( group ) );
			} );

			results.appendChild( wrap );

			var more = el( 'a', 'button button--outline search-results__all' );
			more.href = ( cfg.homeUrl || '/' ) + '?s=' + encodeURIComponent( query );
			more.innerHTML = '<span class="button__text-wrap"><span class="button__text"></span><span class="button__text button__text--clone" aria-hidden="true"></span></span>';
			more.querySelectorAll( '.button__text' ).forEach( function ( node ) {
				node.textContent = t.all || '';
			} );
			results.appendChild( more );
		}

		// Cerca via API annullando la richiesta precedente
		function search( query ) {
			if ( controller ) {
				controller.abort();
			}

			if ( query.length < 2 ) {
				results.textContent = '';
				panel.classList.remove( 'is-loading' );
				return;
			}

			if ( cache[ query ] ) {
				render( cache[ query ], query );
				return;
			}

			if ( ! cfg.endpoint || ! window.fetch ) {
				return;
			}

			panel.classList.add( 'is-loading' );
			controller = 'AbortController' in window ? new AbortController() : null;

			window.fetch( cfg.endpoint + ( cfg.endpoint.indexOf( '?' ) > -1 ? '&' : '?' ) + 'q=' + encodeURIComponent( query ), controller ? { signal: controller.signal } : {} )
				.then( function ( response ) {
					if ( ! response.ok ) {
						throw new Error( 'search' );
					}

					return response.json();
				} )
				.then( function ( data ) {
					cache[ query ] = data;

					if ( input.value.trim() === query ) {
						render( data, query );
					}
				} )
				.catch( function ( error ) {
					if ( error && 'AbortError' === error.name ) {
						return;
					}

					panel.classList.remove( 'is-loading' );
					results.textContent = '';
					results.appendChild( el( 'p', 'search-results__empty', t.error || '' ) );
				} );
		}

		// Apre/chiude il pannello di ricerca
		function setOpen( isOpen ) {
			panel.hidden = ! isOpen;
			document.body.classList.toggle( 'search-open', isOpen );
			openers.forEach( function ( button ) {
				button.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
			} );

			if ( isOpen ) {
				document.body.classList.remove( 'menu-open' );
				window.setTimeout( function () {
					input.focus();
					input.select();
				}, 30 );
			} else if ( lastOpener ) {
				lastOpener.focus();
			}
		}

		openers.forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				lastOpener = button;
				setOpen( panel.hidden );
			} );
		} );

		panel.querySelectorAll( '[data-search-close]' ).forEach( function ( node ) {
			node.addEventListener( 'click', function () {
				setOpen( false );
			} );
		} );

		input.addEventListener( 'input', function () {
			var query = input.value.trim();

			clearBtn.hidden = ! input.value;
			window.clearTimeout( timer );
			timer = window.setTimeout( function () {
				search( query );
			}, 220 );
		} );

		clearBtn.addEventListener( 'click', function () {
			input.value = '';
			input.dispatchEvent( new Event( 'input' ) );
			input.focus();
		} );

		form.addEventListener( 'submit', function ( event ) {
			if ( ! input.value.trim() ) {
				event.preventDefault();
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && ! panel.hidden ) {
				setOpen( false );
			}
		} );
	}

	/** Selettore di lingua (WPML): sul touch il tooltip si apre col tocco; si chiude toccando fuori o con Esc. */
	function initLangSwitcher() {
		var box = document.querySelector( '.header-tip--lang' );

		if ( ! box ) {
			return;
		}

		var button = box.querySelector( '.icon-btn' );

		// Apre/chiude il tooltip della lingua
		function setOpen( isOpen ) {
			box.classList.toggle( 'is-open', isOpen );
			button.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		}

		button.addEventListener( 'click', function () {
			setOpen( ! box.classList.contains( 'is-open' ) );
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! box.contains( event.target ) ) {
				setOpen( false );
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key ) {
				setOpen( false );
			}
		} );
	}

	/**
	 * Barra annunci (inc/topbar.php): completa i gruppi di testi fino a coprire lo schermo, imposta la durata del giro in base alla
	 * velocità scelta, e tiene l'header (fisso) sotto la barra finché è visibile.
	 */
	function initTopBar() {
		var bar = document.querySelector( '.topbar' );

		if ( ! bar ) {
			return;
		}

		var root = document.documentElement.style;
		var body = document.body.style;
		var track = bar.querySelector( '.topbar__track' );
		var groups = bar.querySelectorAll( '.topbar__group' );
		var speed = parseFloat( bar.getAttribute( 'data-speed' ) ) || 60;
		var height = 0;

		// Duplica gli annunci finché ogni gruppo copre la larghezza (nastro scorrevole)
		function fill() {
			var first = groups[ 0 ];
			var guard = 0;

			if ( ! first || ! first.children.length ) {
				return;
			}

			var unit = Array.prototype.slice.call( first.children );

			// Ogni gruppo deve essere largo almeno quanto lo schermo, altrimenti si vedrebbe un vuoto prima che il giro ricominci.
			while ( first.offsetWidth < window.innerWidth && guard < 12 ) {
				unit.forEach( function ( node ) {
					groups.forEach( function ( group ) {
						var copy = node.cloneNode( true );
						copy.classList.add( 'is-dup' );
						group.appendChild( copy );
					} );
				} );
				guard++;
			}

			track.style.setProperty( '--topbar-dur', Math.max( 8, first.offsetWidth / speed ) + 's' );
		}

		// Misura l'altezza della barra e la espone come variabile CSS
		function measure() {
			height = bar.offsetHeight;
			body.setProperty( '--topbar-h', height + 'px' );
			update();
		}

		// Aggiorna lo scarto della barra mentre si scorre la pagina
		function update() {
			var offset = Math.max( 0, height - window.scrollY ) + 'px';
			root.setProperty( '--topbar-offset', offset );
			body.setProperty( '--topbar-offset', offset );
		}

		window.addEventListener( 'scroll', update, { passive: true } );
		window.addEventListener( 'resize', measure );
		fill();
		measure();
	}

	/** Sidebar blog: overlay a tutto schermo dal pulsante "Filtri e ricerca". */
	function initBlogSidebarToggle() {
		var toggle = document.getElementById( 'sidebar-toggle' );
		var closeBtn = document.getElementById( 'sidebar-close' );

		if ( ! toggle ) {
			return;
		}

		// Apre/chiude la sidebar del blog
		function setOpen( isOpen ) {
			document.body.classList.toggle( 'sidebar-open', isOpen );
			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		}

		toggle.addEventListener( 'click', function () {
			setOpen( true );
		} );

		if ( closeBtn ) {
			closeBtn.addEventListener( 'click', function () {
				setOpen( false );
			} );
		}

		document.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && document.body.classList.contains( 'sidebar-open' ) ) {
				setOpen( false );
			}
		} );
	}

	/** Icona+etichetta footer si muovono opposte al mouse (magnetico); disattivato con reduced-motion. */
	function initFooterSocialMagnetic() {
		var items = document.querySelectorAll( '.footer-social__item' );

		if ( ! items.length || window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			return;
		}

		var strength = 8; // Spostamento massimo, in pixel.

		items.forEach( function ( item ) {
			var icon = item.querySelector( '.footer-social__icon' );
			var label = item.querySelector( '.footer-social__label' );

			// Sposta icona ed etichetta in senso opposto al mouse
			function moveBy( x, y ) {
				var value = 'translate(' + x + 'px, ' + y + 'px)';

				if ( icon ) {
					icon.style.transform = value;
				}

				if ( label ) {
					label.style.transform = value;
				}
			}

			item.addEventListener( 'mousemove', function ( event ) {
				var rect = item.getBoundingClientRect();
				var relX = ( event.clientX - rect.left ) / rect.width - 0.5;
				var relY = ( event.clientY - rect.top ) / rect.height - 0.5;

				moveBy( -relX * strength, -relY * strength );
			} );

			item.addEventListener( 'mouseleave', function () {
				moveBy( 0, 0 );
			} );
		} );
	}

	/** Comparsa allo scroll di qualunque elemento con attributo data-reveal (blocchi ACF in template-parts/). */
	function initReveal() {
		var items = document.querySelectorAll( '[data-reveal]' );

		if ( ! items.length || ! ( 'IntersectionObserver' in window ) || window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			return;
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						var target = entry.target;
						var done = false;

						// A comparsa finita si tolgono le classi: restano a ogni elemento le sue transizioni (hover di colore, ombre…)
						// che altrimenti verrebbero sostituite da quella di comparsa e scatterebbero di colpo.
						var finish = function () {
							if ( done ) {
								return;
							}

							done = true;
							target.classList.remove( 'reveal-pending', 'is-revealing' );
						};

						target.addEventListener( 'transitionend', function ( event ) {
							if ( event.target === target && ( 'opacity' === event.propertyName || 'transform' === event.propertyName ) ) {
								finish();
							}
						} );
						window.setTimeout( finish, 1400 );

						target.classList.add( 'is-revealing' );
						observer.unobserve( target );
					}
				} );
			},
			{ threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
		);

		items.forEach( function ( item ) {
			if ( item.getBoundingClientRect().top < window.innerHeight ) {
				return;
			}

			item.classList.add( 'reveal-pending' );
			observer.observe( item );
		} );
	}

	/** Frecce di un carosello con scroll-snap: qualunque bottone con data-scroll-for="id-del-track". */
	function initScrollTracks() {
		document.querySelectorAll( '[data-scroll-for]' ).forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				var track = document.getElementById( button.getAttribute( 'data-scroll-for' ) );

				if ( ! track || ! track.firstElementChild ) {
					return;
				}

				var gap = parseFloat( window.getComputedStyle( track ).columnGap ) || 0;
				var step = track.firstElementChild.getBoundingClientRect().width + gap;

				track.scrollBy( { left: step * Number( button.getAttribute( 'data-scroll-dir' ) ), behavior: 'smooth' } );
			} );
		} );

		// Frecce disattivate quando si e' alla prima o all'ultima card.
		var tracks = {};

		document.querySelectorAll( '[data-scroll-for]' ).forEach( function ( button ) {
			var id = button.getAttribute( 'data-scroll-for' );
			( tracks[ id ] = tracks[ id ] || [] ).push( button );
		} );

		Object.keys( tracks ).forEach( function ( id ) {
			var track = document.getElementById( id );

			if ( ! track ) {
				return;
			}

			// Mostra/nasconde le frecce agli estremi del carosello
			function update() {
				var atStart = track.scrollLeft <= 1;
				var atEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 1;

				tracks[ id ].forEach( function ( button ) {
					button.disabled = Number( button.getAttribute( 'data-scroll-dir' ) ) < 0 ? atStart : atEnd;
				} );
			}

			track.addEventListener( 'scroll', update, { passive: true } );
			window.addEventListener( 'resize', update );
			update();
		} );
	}

	/** Caroselli trascinabili col mouse: qualunque contenitore con attributo data-drag-scroll (touch/trackpad scorrono già in modo nativo). */
	function initDragScroll() {
		document.querySelectorAll( '[data-drag-scroll]' ).forEach( function ( track ) {
			var down = false;
			var moved = false;
			var startX = 0;
			var startScroll = 0;

			track.addEventListener( 'mousedown', function ( event ) {
				down = true;
				moved = false;
				startX = event.pageX;
				startScroll = track.scrollLeft;
				track.classList.add( 'is-dragging' );
			} );

			window.addEventListener( 'mouseup', function () {
				down = false;
				track.classList.remove( 'is-dragging' );
			} );

			window.addEventListener( 'mousemove', function ( event ) {
				if ( ! down ) {
					return;
				}

				var delta = event.pageX - startX;

				if ( Math.abs( delta ) > 4 ) {
					moved = true;
				}

				track.scrollLeft = startScroll - delta;
			} );

			// Non seguire il link/bottone cliccato se si stava trascinando.
			track.addEventListener(
				'click',
				function ( event ) {
					if ( moved ) {
						event.preventDefault();
						event.stopPropagation();
					}
				},
				true
			);
		} );
	}

	/** Carosello recensioni in loop (clona le card ai due estremi per far sembrare lo scroll infinito). */
	function initReviewsLoop() {
		document.querySelectorAll( '.reviews__track' ).forEach( function ( track ) {
			var originals = Array.prototype.slice.call( track.children );

			if ( originals.length < 2 ) {
				return;
			}

			// Clona le card ai due estremi del carosello
			function clones() {
				var fragment = document.createDocumentFragment();

				originals.forEach( function ( card ) {
					var copy = card.cloneNode( true );

					copy.setAttribute( 'aria-hidden', 'true' );
					copy.removeAttribute( 'id' );
					fragment.appendChild( copy );
				} );

				return fragment;
			}

			track.insertBefore( clones(), originals[ 0 ] );
			track.appendChild( clones() );

			// Larghezza di un giro completo di card
			function setWidth() {
				return originals[ 0 ].offsetLeft - track.firstElementChild.offsetLeft;
			}

			// Riporta lo scroll sul giro centrale senza animazione
			function recentre() {
				track.style.scrollSnapType = 'none';
				track.scrollLeft = setWidth();
				track.style.scrollSnapType = '';
			}

			var timer = null;

			track.addEventListener( 'scroll', function () {
				window.clearTimeout( timer );
				timer = window.setTimeout( function () {
					var width = setWidth();
					var left = track.scrollLeft;

					if ( left < width * 0.5 || left > width * 1.5 ) {
						track.style.scrollSnapType = 'none';
						track.scrollLeft = left < width * 0.5 ? left + width : left - width;
						track.style.scrollSnapType = '';
					}
				}, 120 );
			}, { passive: true } );

			window.addEventListener( 'resize', recentre );
			recentre();

			// Il primo caricamento dei font può cambiare le larghezze.
			window.addEventListener( 'load', recentre );
		} );
	}

	/**
	 * Lightbox generica: qualunque galleria può usarla, basta che ogni miniatura sia un elemento
	 * con attributo data-lightbox-index (l'indice nella sequenza) e data-full/data-alt/data-caption.
	 * Il markup della finestra (#lightbox) è in footer.php, stampato una sola volta per pagina.
	 */
	function initLightbox() {
		var box = document.getElementById( 'lightbox' );
		// Le foto sono gli elementi con data-full; altri pulsanti con data-lightbox-index (es. "Vedi tutte le foto") aprono la foto indicata ma non ne fanno parte.
		var triggers = document.querySelectorAll( '[data-lightbox-index]' );
		var slides = document.querySelectorAll( '[data-lightbox-index][data-full]' );

		if ( ! box || ! slides.length ) {
			return;
		}

		var img = document.getElementById( 'lightbox-img' );
		var caption = document.getElementById( 'lightbox-caption' );
		var closeButton = box.querySelector( '[data-lightbox-close]' );
		var current = 0;
		var opener = null;

		// Mostra la foto numero index nella lightbox
		function show( index ) {
			current = ( index + slides.length ) % slides.length;
			img.src = slides[ current ].getAttribute( 'data-full' );
			img.alt = slides[ current ].getAttribute( 'data-alt' ) || '';
			caption.textContent = ( current + 1 ) + ' / ' + slides.length + ' · ' + ( slides[ current ].getAttribute( 'data-caption' ) || img.alt );
		}

		// Apre la lightbox sulla foto index
		function open( index, trigger ) {
			opener = trigger;
			show( index );
			box.hidden = false;
			document.body.classList.add( 'lightbox-open' );
			closeButton.focus();
		}

		// Chiude la lightbox e restituisce il focus
		function close() {
			box.hidden = true;
			document.body.classList.remove( 'lightbox-open' );
			img.src = '';

			if ( opener ) {
				opener.focus();
			}
		}

		triggers.forEach( function ( trigger, position ) {
			trigger.addEventListener( 'click', function () {
				var index = parseInt( trigger.getAttribute( 'data-lightbox-index' ), 10 );
				open( isNaN( index ) ? position : index, trigger );
			} );
		} );

		box.querySelectorAll( '[data-lightbox-step]' ).forEach( function ( button ) {
			button.addEventListener( 'click', function () {
				show( current + Number( button.getAttribute( 'data-lightbox-step' ) ) );
			} );
		} );

		closeButton.addEventListener( 'click', close );

		box.addEventListener( 'click', function ( event ) {
			if ( event.target === box || event.target.classList.contains( 'lightbox__stage' ) ) {
				close();
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( box.hidden ) {
				return;
			}

			if ( 'Escape' === event.key ) {
				close();
			} else if ( 'ArrowRight' === event.key ) {
				show( current + 1 );
			} else if ( 'ArrowLeft' === event.key ) {
				show( current - 1 );
			}
		} );
	}

	/** Pulsante WhatsApp: diventa rotondo (solo icona) quando il footer entra in vista. */
	function initWhatsappCompact() {
		var button = document.querySelector( '.whatsapp-fab' );
		var footer = document.querySelector( '.site-footer' );

		if ( ! button || ! footer || ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		new IntersectionObserver( function ( entries ) {
			button.classList.toggle( 'is-compact', entries[ 0 ].isIntersecting );
		} ).observe( footer );
	}

	/* Menu desktop (barra orizzontale) e non pannello: sopra 1180px e senza l'header "Ridotto". */
	function isDesktopNav() {
		return window.matchMedia( '(min-width: 1181px)' ).matches && ! document.body.classList.contains( 'header-minimal' );
	}

	/* Desktop con mouse: i sottomenu si aprono anche all'hover. */
	function isHoverNav() {
		return isDesktopNav() && window.matchMedia( '(hover: hover)' ).matches;
	}

	/**
	 * Cuore "preferiti" delle card: per ora solo visivo, ricordato in questo
	 * browser (localStorage). La lista dei desideri vera è da decidere.
	 */
	function initWishlist() {
		var KEY = 'starter-wishlist';
		var badges = document.querySelectorAll( '.header-wish__count' );

		// Legge i preferiti salvati nel browser (localStorage)
		function read() {
			try {
				var list = JSON.parse( window.localStorage.getItem( KEY ) || '[]' );

				return Array.isArray( list ) ? list.map( String ) : [];
			} catch ( e ) {
				return [];
			}
		}

		// Cuori accesi/spenti e numero nell'header.
		function paint( root ) {
			var saved = read();

			( root || document ).querySelectorAll( '[data-wishlist]' ).forEach( function ( button ) {
				button.setAttribute( 'aria-pressed', saved.indexOf( button.getAttribute( 'data-wishlist' ) ) > -1 ? 'true' : 'false' );
			} );

			badges.forEach( function ( badge ) {
				badge.textContent = saved.length ? saved.length : '';
				badge.classList.toggle( 'is-empty', ! saved.length );
			} );
		}

		// Aggiunge o toglie un prodotto dai preferiti
		function toggle( id ) {
			var saved = read();
			var index = saved.indexOf( id );
			var added = index < 0;

			if ( added ) {
				saved.push( id );
			} else {
				saved.splice( index, 1 );
			}

			try {
				window.localStorage.setItem( KEY, JSON.stringify( saved ) );
			} catch ( e ) {}

			paint();

			if ( added ) {
				badges.forEach( function ( badge ) {
					badge.classList.remove( 'is-bumped' );
					void badge.offsetWidth; // Riavvia l'animazione.
					badge.classList.add( 'is-bumped' );
				} );
			}

			document.dispatchEvent( new CustomEvent( 'starter:wishlist', { detail: saved } ) );
		}

		// Click delegato: funziona anche sulle card inserite dopo (pagina Preferiti).
		document.addEventListener( 'click', function ( event ) {
			var button = event.target.closest( '[data-wishlist]' );

			if ( button ) {
				toggle( button.getAttribute( 'data-wishlist' ) );
			}
		} );

		// Un'altra scheda del browser ha cambiato i preferiti.
		window.addEventListener( 'storage', function ( event ) {
			if ( event.key === KEY ) {
				paint();
				document.dispatchEvent( new CustomEvent( 'starter:wishlist', { detail: read(), bubbles: false } ) );
			}
		} );

		window.starterWishlist = { read: read, paint: paint };
		paint();
	}

	/**
	 * Tooltip del cuore nell'header: i prodotti salvati (l'elenco sta nel browser, il server dà foto, nome e prezzo).
	 * Si riscrive solo se l'elenco è cambiato dall'ultima volta.
	 */
	function initWishlistTip() {
		var panel = document.querySelector( '[data-wish-tip]' );

		if ( ! panel || ! window.starterWishlist || ! window.fetch ) {
			return;
		}

		var wrap = panel.parentNode;
		var last = null;

		// Scarica la griglia dei preferiti (più recenti in alto) solo se la lista è cambiata
		function load() {
			var ids = window.starterWishlist.read().slice().reverse().join( ',' ); // I più recenti in alto.

			if ( ids === last ) {
				return;
			}

			last = ids;

			window.fetch( panel.getAttribute( 'data-endpoint' ) + '?action=starter_wishlist_tip&ids=' + encodeURIComponent( ids ) )
				.then( function ( response ) {
					return response.json();
				} )
				.then( function ( data ) {
					panel.innerHTML = data.html || '';
				} )
				.catch( function () {
					last = null;
				} );
		}

		wrap.addEventListener( 'mouseenter', load );
		wrap.addEventListener( 'focusin', load );
	}

	/**
	 * Pagina Preferiti ([starter_preferiti]): chiede al server le card dei prodotti salvati (le stesse del
	 * negozio) e le toglie quando si spegne il cuore; senza preferiti mostra l'invito a scoprire i prodotti.
	 */
	function initWishlistPage() {
		var box = document.querySelector( '[data-wishlist-page]' );

		if ( ! box || ! window.starterWishlist || ! window.fetch ) {
			return;
		}

		var grid = box.querySelector( '[data-wishlist-grid]' );
		var empty = box.querySelector( '[data-wishlist-empty]' );

		// Mostra il messaggio "nessun preferito" se la griglia è vuota
		function showEmptyIfNone() {
			empty.hidden = grid.querySelectorAll( '[data-wishlist]' ).length > 0;
		}

		// Scarica i prodotti preferiti, più recenti in alto
		function load() {
			var ids = window.starterWishlist.read().slice().reverse(); // I più recenti in alto.

			if ( ! ids.length ) {
				grid.textContent = '';
				showEmptyIfNone();
				return;
			}

			box.classList.add( 'is-loading' );

			window.fetch( box.getAttribute( 'data-endpoint' ) + '?action=starter_wishlist&ids=' + encodeURIComponent( ids.join( ',' ) ) )
				.then( function ( response ) {
					return response.json();
				} )
				.then( function ( data ) {
					grid.innerHTML = data.html || '';
					window.starterWishlist.paint( grid );
					showEmptyIfNone();
				} )
				.catch( function () {
					grid.textContent = '';
					showEmptyIfNone();
				} )
				.then( function () {
					box.classList.remove( 'is-loading' );
				} );
		}

		// Cuore spento nella pagina: la card sparisce subito, senza ricaricare.
		document.addEventListener( 'starter:wishlist', function ( event ) {
			var saved = event.detail || [];
			var cards = grid.querySelectorAll( '[data-wishlist]' );

			if ( cards.length && saved.length > cards.length ) {
				load(); // Aggiunto da un'altra scheda: si ricarica l'elenco.
				return;
			}

			cards.forEach( function ( button ) {
				if ( saved.indexOf( button.getAttribute( 'data-wishlist' ) ) < 0 ) {
					var item = button.closest( 'li' );

					if ( item ) {
						item.remove();
					}
				}
			} );

			showEmptyIfNone();
		} );

		load();
	}

	/**
	 * Carrello nell'header: WooCommerce aggiorna il numero con i "fragments"
	 * dopo ogni aggiunta; qui il badge fa un piccolo "salto" e il pulsante
	 * della card mostra la conferma per un paio di secondi.
	 */
	function initCartBadge() {
		if ( ! window.jQuery ) {
			return;
		}

		window.jQuery( document.body ).on( 'added_to_cart', function ( event, fragments, hash, button ) {
			window.requestAnimationFrame( function () {
				document.querySelectorAll( '.header-cart__count' ).forEach( function ( badge ) {
					badge.classList.remove( 'is-bumped' );
					void badge.offsetWidth; // Riavvia l'animazione.
					badge.classList.add( 'is-bumped' );
				} );
			} );

			if ( button && button[ 0 ] ) {
				var el = button[ 0 ];
				el.classList.add( 'is-added-now' );
				window.setTimeout( function () {
					el.classList.remove( 'is-added-now' );
				}, 2200 );
			}
		} );
	}

	/**
	 * Voci con sottomenu (pulsante .submenu-toggle): il click apre e richiude il
	 * sottomenu (classe is-open sul <li>). Su desktop si apre anche all'hover:
	 * se lo chiudi col click mentre il mouse è sopra, resta chiuso finché il
	 * mouse non esce (classe is-closed). Esc o click fuori chiudono tutto.
	 */
	function initSubmenus() {
		var toggles = document.querySelectorAll( '.main-navigation .submenu-toggle' );

		if ( ! toggles.length ) {
			return;
		}

		// Apre/chiude un sottomenu
		function setOpen( toggle, isOpen ) {
			var item = toggle.parentElement;

			item.classList.toggle( 'is-open', isOpen );
			item.classList.toggle( 'is-closed', ! isOpen && item.matches( ':hover' ) );
			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );
		}

		// Chiude tutti i sottomenu tranne quello indicato
		function closeAll( except ) {
			toggles.forEach( function ( toggle ) {
				// Un sottomenu di un sottomenu: aprendolo non si chiude il menu che lo contiene.
				if ( toggle !== except && ! ( except && toggle.parentElement.contains( except ) ) && toggle.parentElement.classList.contains( 'is-open' ) ) {
					setOpen( toggle, false );
				}
			} );
		}

		toggles.forEach( function ( toggle ) {
			var item = toggle.parentElement;

			toggle.addEventListener( 'click', function () {
				// Aperto via hover (desktop) ma senza click: il primo click lo chiude.
				var isOpen = item.classList.contains( 'is-open' ) || ( isHoverNav() && item.matches( ':hover' ) && ! item.classList.contains( 'is-closed' ) );

				closeAll( toggle );
				setOpen( toggle, ! isOpen );
			} );

			item.addEventListener( 'mouseleave', function () {
				item.classList.remove( 'is-closed' );

				if ( isDesktopNav() ) {
					setOpen( toggle, false );
					item.classList.remove( 'is-closed' );
				}
			} );
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! event.target.closest( '.main-navigation .menu-item-has-children' ) ) {
				closeAll();
			}
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key ) {
				closeAll();
			}
		} );
	}

	/** Elenco servizi: voce attiva nel menu laterale (segue lo scroll) */
	function initServicesSpy() {
		var nav = document.querySelector( '.servizi__nav' );

		if ( ! nav || ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		var links = nav.querySelectorAll( 'a[data-servizio]' );

		// Evidenzia la voce di menu del servizio visibile
		function activate( id ) {
			links.forEach( function ( link ) {
				var on = link.getAttribute( 'data-servizio' ) === id;

				link.classList.toggle( 'is-active', on );

				if ( on && nav.scrollWidth > nav.clientWidth ) {
					nav.scrollLeft = link.offsetLeft - ( nav.clientWidth - link.offsetWidth ) / 2;
				}
			} );
		}

		var observer = new IntersectionObserver(
			function ( entries ) {
				entries.forEach( function ( entry ) {
					if ( entry.isIntersecting ) {
						activate( entry.target.id );
					}
				} );
			},
			{ rootMargin: '-45% 0px -50% 0px' }
		);

		links.forEach( function ( link ) {
			var target = document.getElementById( link.getAttribute( 'data-servizio' ) );

			if ( target ) {
				observer.observe( target );
			}
		} );

		if ( links.length ) {
			activate( links[ 0 ].getAttribute( 'data-servizio' ) );
		}
	}

	/** Menu servizi: se il titolo non entra nel pulsante, al passaggio del mouse scorre fino alla fine. */
	function initServicesLabelScroll() {
		document.querySelectorAll( '.servizi__nav a' ).forEach( function ( link ) {
			var wrap = link.querySelector( '.servizi__label-wrap' );
			var label = link.querySelector( '.servizi__label' );

			if ( ! wrap || ! label ) {
				return;
			}

			// Scorre il titolo fino in fondo se non entra nel pulsante
			function start() {
				var shift = label.scrollWidth - wrap.clientWidth;

				if ( shift > 1 ) {
					label.style.transitionDuration = Math.max( 0.8, shift / 60 ) + 's';
					label.style.transform = 'translateX(-' + shift + 'px)';
				}
			}

			// Riporta il titolo alla posizione iniziale
			function stop() {
				label.style.transitionDuration = '0.4s';
				label.style.transform = '';
			}

			link.addEventListener( 'mouseenter', start );
			link.addEventListener( 'mouseleave', stop );
			link.addEventListener( 'focus', start );
			link.addEventListener( 'blur', stop );
		} );
	}

	/**
	 * Voci dei sottomenu (anche sottomenu di sottomenu): il testo va in un riquadro che non lo lascia uscire. Se non entra, scorre fino alla fine
	 * (classe is-overflowing): con il mouse al passaggio sopra, su touch in automatico. Se entra, resta fermo. Stile in CSS/starter.css ("Voci lunghe dei sottomenu").
	 */
	function initMenuLabelScroll() {
		var labels = [];

		document.querySelectorAll( '.main-navigation .sub-menu a, .main-navigation .sub-menu .submenu-toggle' ).forEach( function ( link ) {
			var texts = Array.prototype.filter.call( link.childNodes, function ( node ) {
				return 3 === node.nodeType && node.nodeValue.trim();
			} );

			if ( ! texts.length ) {
				return;
			}

			var label = document.createElement( 'span' );
			var text = document.createElement( 'span' );

			label.className = 'menu-label';
			text.className = 'menu-label__text';
			link.insertBefore( label, texts[ 0 ] );
			label.appendChild( text );

			texts.forEach( function ( node ) {
				text.appendChild( node );
			} );

			labels.push( label );
		} );

		if ( ! labels.length ) {
			return;
		}

		// Misura la larghezza di un'etichetta di sottomenu
		function measure( label ) {
			var width = label.clientWidth;

			// Sottomenu chiuso (display: none): niente da misurare, lo farà l'osservatore all'apertura.
			if ( ! width ) {
				return;
			}

			var shift = label.firstChild.scrollWidth - width;

			label.classList.toggle( 'is-overflowing', shift > 1 );

			if ( shift > 1 ) {
				label.style.setProperty( '--shift', shift + 'px' );
				label.style.setProperty( '--hover-dur', Math.max( 0.8, shift / 60 ) + 's' ); // Stessa velocità delle tab dei servizi.
				label.style.setProperty( '--auto-dur', Math.max( 6, ( shift / 35 ) * 2 / 0.7 ) + 's' ); // Andata e ritorno con una pausa a ogni estremo (il 70% del tempo è movimento).
			}
		}

		// Misura tutte le etichette
		function measureAll() {
			labels.forEach( measure );
		}

		if ( 'ResizeObserver' in window ) {
			// Scatta anche quando il sottomenu si apre (da 0 alla larghezza reale) e quando la finestra cambia misura.
			var observer = new ResizeObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					measure( entry.target );
				} );
			} );

			labels.forEach( function ( label ) {
				observer.observe( label );
			} );
		} else {
			window.addEventListener( 'resize', measureAll );
		}

		if ( document.fonts && document.fonts.ready ) {
			document.fonts.ready.then( measureAll );
		}

		measureAll();
	}

	/** Barra di icone dei servizi (mobile/tablet): fissa in basso finché si scorre l'elenco, poi si ferma prima della sezione successiva */
	function initServicesDock() {
		var nav = document.querySelector( '.servizi__nav' );
		var container = document.querySelector( '.servizi__grid' );

		if ( ! nav || ! container ) {
			return;
		}

		var mobileQuery = window.matchMedia( '(max-width: 1099px)' );
		var stopGap = -76;
		var docked = false;
		var ticking = false;

		// Fissa o rilascia la barra di icone in base allo scroll
		function update() {
			ticking = false;

			if ( ! mobileQuery.matches ) {
				if ( docked ) {
					nav.classList.remove( 'is-docked' );
					nav.style.top = '';
					docked = false;
				}

				return;
			}

			var navHeight = nav.offsetHeight;
			var navBottomOffset = parseFloat( getComputedStyle( nav ).bottom ) || 0;
			var containerBottom = container.getBoundingClientRect().bottom;
			var shouldDock = containerBottom < window.innerHeight - navBottomOffset + stopGap;

			if ( shouldDock !== docked ) {
				nav.classList.toggle( 'is-docked', shouldDock );
				docked = shouldDock;
			}

			nav.style.top = docked ? ( container.offsetHeight - stopGap - navHeight ) + 'px' : '';
		}

		// Pianifica un solo aggiornamento per frame
		function requestUpdate() {
			if ( ! ticking ) {
				window.requestAnimationFrame( update );
				ticking = true;
			}
		}

		window.addEventListener( 'scroll', requestUpdate, { passive: true } );
		window.addEventListener( 'resize', requestUpdate );
		update();
	}

	/** Pulsanti − e + del campo quantità (markup da inc/woocommerce.php): cambiano il valore nei limiti min/max e avvisano gli altri script con "change". */
	function initQuantityButtons() {
		document.addEventListener( 'click', function ( event ) {
			var button = event.target.closest( '[data-qty-step]' );

			if ( ! button ) {
				return;
			}

			var input = button.closest( '.quantity' ) && button.closest( '.quantity' ).querySelector( 'input.qty' );

			if ( ! input || input.disabled || input.readOnly ) {
				return;
			}

			var step = parseFloat( input.step ) || 1;
			var min = '' !== input.min ? parseFloat( input.min ) : 0;
			var max = '' !== input.max ? parseFloat( input.max ) : Infinity;
			var value = ( parseFloat( input.value ) || 0 ) + step * Number( button.getAttribute( 'data-qty-step' ) );

			input.value = Math.min( max, Math.max( min, value ) );
			input.dispatchEvent( new Event( 'change', { bubbles: true } ) );
		} );
	}

	/** Filtri degli elenchi (Shop, Immobili e ogni altro tipo di contenuto con lo stesso markup .filters-wrap): vedi CSS/filters.css. */
	function initFilters() {
		var wrap = document.querySelector( '.filters-wrap' );
		var form = document.querySelector( '.filters-wrap .filters' );

		if ( ! wrap || ! form ) {
			return;
		}

		var toggle = wrap.querySelector( '.filters-toggle' );
		var lists = form.querySelectorAll( '.multiselect' );
		var desktop = window.matchMedia( '(min-width: 1025px)' );

		form.addEventListener( 'submit', function () {
			form.querySelectorAll( 'input[name], select[name]' ).forEach( function ( field ) {
				if ( '' === field.value || ( field.hasAttribute( 'data-default' ) && field.value === field.getAttribute( 'data-default' ) ) ) {
					field.disabled = true;
				}
			} );
		} );

		// Ordinamento dentro ai filtri (desktop): si applica subito.
		var orderby = form.querySelector( 'select[name="orderby"]' );

		if ( orderby ) {
			orderby.addEventListener( 'change', function () {
				form.requestSubmit ? form.requestSubmit() : form.submit();
			} );
		}

		// "Azzera i filtri": attivo appena c'è qualcosa di scelto nel modulo (anche non ancora applicato), spento se è tutto vuoto.
		var reset = form.querySelector( '[data-filters-reset]' );

		// Mostra "Azzera" solo se qualche filtro è attivo
		function syncReset() {
			var any = false;

			form.querySelectorAll( 'input[name], select[name]' ).forEach( function ( field ) {
				if ( 'orderby' === field.name ) {
					return;
				}

				if ( 'checkbox' === field.type ? field.checked : '' !== field.value ) {
					any = true;
				}
			} );

			reset.classList.toggle( 'is-disabled', ! any );

			if ( any ) {
				reset.removeAttribute( 'aria-disabled' );
				reset.removeAttribute( 'tabindex' );
			} else {
				reset.setAttribute( 'aria-disabled', 'true' );
				reset.setAttribute( 'tabindex', '-1' );
			}
		}

		if ( reset ) {
			form.addEventListener( 'input', syncReset );
			form.addEventListener( 'change', syncReset );
			syncReset();
		}

		form.querySelectorAll( '[data-live-search]' ).forEach( initLiveSearch );

		lists.forEach( function ( list ) {
			var value = list.querySelector( '.multiselect__value' );

			list.addEventListener( 'keydown', function ( event ) {
				if ( 'Escape' === event.key && list.open ) {
					list.open = false;
					list.querySelector( 'summary' ).focus();
				}
			} );

			list.addEventListener( 'change', function () {
				var names = [];

				list.querySelectorAll( 'input:checked' ).forEach( function ( input ) {
					names.push( input.nextElementSibling.textContent.trim() );
				} );

				if ( ! names.length ) {
					value.textContent = list.getAttribute( 'data-any' );
				} else if ( names.length > 2 ) {
					value.textContent = list.getAttribute( 'data-many' ).replace( '%d', names.length );
				} else {
					value.textContent = names.join( ', ' );
				}
			} );
		} );

		document.addEventListener( 'click', function ( event ) {
			lists.forEach( function ( list ) {
				if ( list.open && ! list.contains( event.target ) ) {
					list.open = false;
				}
			} );
		} );

		if ( ! toggle ) {
			return;
		}

		wrap.classList.add( 'is-drawer' );

		// Lo scroll fluido (Lenis) intercetta rotella e trackpad: senza questo il pannello non scorre e si muove la pagina dietro.
		form.setAttribute( 'data-lenis-prevent', '' );

		// Con il pannello aperto la pagina dietro resta ferma (scroll fluido fermo + body bloccato), il pannello scorre da solo.
		function lockPage( locked ) {
			document.body.classList.toggle( 'filters-open', locked );

			if ( window.starterLenis ) {
				window.starterLenis[ locked ? 'stop' : 'start' ]();
			}
		}

		// Apre/chiude il cassetto dei filtri
		function setOpen( isOpen ) {
			wrap.classList.toggle( 'is-open', isOpen );
			lockPage( isOpen );
			toggle.setAttribute( 'aria-expanded', isOpen ? 'true' : 'false' );

			if ( isOpen ) {
				form.querySelector( '[data-filters-close]' ).focus();
			} else {
				toggle.focus();
			}
		}

		toggle.addEventListener( 'click', function () {
			setOpen( true );
		} );

		wrap.querySelectorAll( '[data-filters-close]' ).forEach( function ( closer ) {
			closer.addEventListener( 'click', function () {
				setOpen( false );
			} );
		} );

		document.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && wrap.classList.contains( 'is-open' ) ) {
				setOpen( false );
			}
		} );

		desktop.addEventListener( 'change', function ( query ) {
			if ( query.matches && wrap.classList.contains( 'is-open' ) ) {
				wrap.classList.remove( 'is-open' );
				lockPage( false );
				toggle.setAttribute( 'aria-expanded', 'false' );
			}
		} );
	}

	/**
	 * Ricerca con suggerimenti: mentre scrivi (da 2 lettere) mostra fino a 6
	 * prodotti sotto al campo, presi dalla Store API di WooCommerce (pubblica,
	 * già nel plugin). Frecce su/giù per scegliere, Invio apre il prodotto o
	 * cerca, Esc chiude. Senza JS il campo resta una normale ricerca.
	 */
	function initLiveSearch( box ) {
		var input = box.querySelector( 'input[type="search"]' );
		var list = box.querySelector( '.live-search__results' );
		var endpoint = box.getAttribute( 'data-live-search' );
		var timer = null;
		var controller = null;
		var active = -1;

		if ( ! input || ! list || ! endpoint || ! window.fetch ) {
			return;
		}

		// Rende il testo sicuro da inserire come HTML
		function escapeHtml( text ) {
			var el = document.createElement( 'span' );
			el.textContent = text;
			return el.innerHTML;
		}

		// Formatta il prezzo restituito dall'API del negozio
		function price( prices ) {
			if ( ! prices || '' === prices.price ) {
				return '';
			}

			var value = ( parseInt( prices.price, 10 ) / Math.pow( 10, prices.currency_minor_unit ) ).toFixed( prices.currency_minor_unit );
			var parts = value.split( '.' );

			parts[ 0 ] = parts[ 0 ].replace( /\B(?=(\d{3})+(?!\d))/g, prices.currency_thousand_separator );

			return prices.currency_prefix + parts.join( prices.currency_decimal_separator ) + prices.currency_suffix;
		}

		// Chiude l'elenco dei suggerimenti
		function close() {
			list.hidden = true;
			input.setAttribute( 'aria-expanded', 'false' );
			active = -1;
		}

		// Voci dell'elenco dei suggerimenti
		function items() {
			return list.querySelectorAll( '.live-search__item' );
		}

		// Evidenzia la voce numero index (frecce da tastiera)
		function highlight( index ) {
			var all = items();

			all.forEach( function ( item, i ) {
				item.classList.toggle( 'is-active', i === index );
				item.setAttribute( 'aria-selected', i === index ? 'true' : 'false' );
			} );

			active = index;
		}

		// Disegna i suggerimenti dei prodotti
		function render( products, term ) {
			var html = '';

			products.forEach( function ( product, i ) {
				var image = product.images && product.images[ 0 ] ? product.images[ 0 ].thumbnail : '';

				html += '<li class="live-search__item" role="option" id="' + list.id + '-' + i + '" aria-selected="false"><a href="' + encodeURI( product.permalink ) + '">' +
					( image ? '<img src="' + encodeURI( image ) + '" alt="" loading="lazy" width="52" height="52">' : '<span></span>' ) +
					'<span class="live-search__name">' + product.name + '</span>' +
					'<span class="live-search__price">' + escapeHtml( price( product.prices ) ) + '</span></a></li>';
			} );

			if ( products.length ) {
				html += '<li class="live-search__all"><a href="#" data-submit>' + escapeHtml( 'Vedi tutti i risultati per “' + term + '”' ) + '</a></li>';
			} else {
				html += '<li class="live-search__empty">' + escapeHtml( 'Nessun prodotto trovato per “' + term + '”' ) + '</li>';
			}

			list.innerHTML = html;
			list.hidden = false;
			input.setAttribute( 'aria-expanded', 'true' );
			active = -1;
		}

		// Cerca prodotti annullando la richiesta precedente
		function search( term ) {
			if ( controller ) {
				controller.abort();
			}

			controller = window.AbortController ? new AbortController() : null;

			window.fetch( endpoint + ( endpoint.indexOf( '?' ) > -1 ? '&' : '?' ) + 'per_page=6&search=' + encodeURIComponent( term ), controller ? { signal: controller.signal } : {} )
				.then( function ( response ) {
					return response.ok ? response.json() : [];
				} )
				.then( function ( products ) {
					if ( input.value.trim() === term ) {
						render( Array.isArray( products ) ? products : [], term );
					}
				} )
				.catch( function () {} );
		}

		input.addEventListener( 'input', function () {
			var term = input.value.trim();

			window.clearTimeout( timer );

			if ( term.length < 2 ) {
				close();
				return;
			}

			timer = window.setTimeout( function () {
				search( term );
			}, 250 );
		} );

		input.addEventListener( 'keydown', function ( event ) {
			var all = items();

			if ( 'ArrowDown' === event.key && ! list.hidden && all.length ) {
				event.preventDefault();
				highlight( ( active + 1 ) % all.length );
			} else if ( 'ArrowUp' === event.key && ! list.hidden && all.length ) {
				event.preventDefault();
				highlight( active <= 0 ? all.length - 1 : active - 1 );
			} else if ( 'Enter' === event.key && active > -1 && all[ active ] ) {
				event.preventDefault();
				window.location.href = all[ active ].querySelector( 'a' ).href;
			} else if ( 'Escape' === event.key && ! list.hidden ) {
				event.stopPropagation();
				close();
			}
		} );

		list.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( '[data-submit]' ) ) {
				event.preventDefault();
				var form = input.form;
				form.requestSubmit ? form.requestSubmit() : form.submit();
			}
		} );

		document.addEventListener( 'click', function ( event ) {
			if ( ! box.contains( event.target ) ) {
				close();
			}
		} );
	}

	/* Pulsanti di WooCommerce stampati senza l'animazione del tema (es. "Aggiungi al carrello" della scheda prodotto): stesso testo che ruota delle card. */
	function initFlipButtons() {
		document.querySelectorAll( '.single_add_to_cart_button, .woocommerce-cart-form .button, .checkout-button' ).forEach( function ( button ) {
			var text = button.textContent.trim();

			if ( ! text || button.querySelector( '.button__text-wrap' ) || 'INPUT' === button.tagName ) {
				return;
			}

			button.textContent = '';

			var wrap = document.createElement( 'span' );
			wrap.className = 'button__text-wrap';

			[ 'button__text', 'button__text button__text--clone' ].forEach( function ( className ) {
				var span = document.createElement( 'span' );
				span.className = className;
				span.textContent = text;

				if ( -1 !== className.indexOf( 'clone' ) ) {
					span.setAttribute( 'aria-hidden', 'true' );
				}

				wrap.appendChild( span );
			} );

			button.appendChild( wrap );
		} );
	}

	/* Scheda prodotto: il cuore dei preferiti va dentro la galleria, accanto alla lente dello zoom. */
	function initProductWish() {
		var wish = document.querySelector( '.product-card__wish--single' );
		var gallery = document.querySelector( '.woocommerce-product-gallery' );

		if ( ! wish || ! gallery ) {
			return;
		}

		gallery.appendChild( wish );
		wish.hidden = false;
	}

	/** Hero con slider: dissolvenza tra le foto ([data-hero-slider] = millisecondi per foto); ferma con "riduci animazioni" e a scheda nascosta. */
	function initHeroSlider() {
		if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			return;
		}

		document.querySelectorAll( '[data-hero-slider]' ).forEach( function ( slider ) {
			var slides = slider.querySelectorAll( 'img' );
			var delay = parseInt( slider.getAttribute( 'data-hero-slider' ), 10 ) || 6000;
			var index = 0;

			if ( slides.length < 2 ) {
				return;
			}

			window.setInterval( function () {
				if ( document.hidden ) {
					return;
				}

				var next = slides[ ( index + 1 ) % slides.length ];

				// Le foto successive sono in lazy: si forza il caricamento prima di mostrarle.
				next.loading = 'eager';
				slides[ index ].classList.remove( 'is-active' );
				index = ( index + 1 ) % slides.length;
				next.classList.add( 'is-active' );
			}, delay );
		} );
	}

	/**
	 * Blocco "Slider": frecce, paginazione (puntini, contatore, barra), autoplay, trascinamento col mouse.
	 * La traccia scorre da sola (scroll-snap): qui si calcola solo il passo (larghezza di una slide + distanza) e si aggiornano i comandi.
	 */
	function initSliders() {
		var reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		document.querySelectorAll( '[data-slider]' ).forEach( function ( slider ) {
			var track = slider.querySelector( '[data-slider-track]' );
			var slides = track ? track.querySelectorAll( '.slider-block__slide' ) : [];
			var prev = slider.querySelectorAll( '[data-slider-prev]' );
			var next = slider.querySelectorAll( '[data-slider-next]' );
			var dotsBox = slider.querySelector( '[data-slider-dots]' );
			var counter = slider.querySelector( '[data-slider-counter]' );
			var progress = slider.querySelector( '[data-slider-progress]' );
			var loop = '1' === slider.getAttribute( 'data-loop' );
			var autoplay = parseInt( slider.getAttribute( 'data-autoplay' ), 10 ) || 0;
			var pages = 1;
			var timer = 0;
			var paused = false;

			if ( ! track || ! slides.length ) {
				return;
			}

			// Larghezza di una slide più lo spazio tra le slide
			function step() {
				var gap = parseFloat( getComputedStyle( track ).columnGap ) || 0;

				return slides[ 0 ].getBoundingClientRect().width + gap;
			}

			// Quante slide entrano nella vista
			function visible() {
				var width = track.clientWidth - 2 * ( parseFloat( getComputedStyle( track ).paddingLeft ) || 0 );
				var s = step();

				return Math.max( 1, Math.round( ( width + ( s - slides[ 0 ].getBoundingClientRect().width ) ) / s ) );
			}

			// Indice della slide attuale
			function current() {
				var max = track.scrollWidth - track.clientWidth;

				if ( track.scrollLeft >= max - 2 ) {
					return pages - 1;
				}

				return Math.max( 0, Math.min( pages - 1, Math.round( track.scrollLeft / step() ) ) );
			}

			// Scorre alla slide index
			function goTo( index ) {
				track.scrollTo( { left: index * step(), behavior: reduce ? 'auto' : 'smooth' } );
			}

			// Crea i puntini (uno per pagina)
			function buildDots() {
				pages = Math.max( 1, slides.length - visible() + 1 );
				slider.classList.toggle( 'is-static', pages < 2 );

				if ( dotsBox ) {
					dotsBox.innerHTML = '';

					for ( var i = 0; i < pages; i++ ) {
						var dot = document.createElement( 'button' );

						dot.type = 'button';
						dot.className = 'slider-block__dot';
						dot.setAttribute( 'aria-label', ( i + 1 ) + ' / ' + pages );
						dot.addEventListener( 'click', goTo.bind( null, i ) );
						dotsBox.appendChild( dot );
					}
				}

				update();
			}

			// Aggiorna puntini e frecce
			function update() {
				var index = current();

				if ( dotsBox ) {
					Array.prototype.forEach.call( dotsBox.children, function ( dot, i ) {
						dot.classList.toggle( 'is-active', i === index );
					} );
				}

				if ( counter ) {
					counter.textContent = ( index + 1 ) + ' / ' + pages;
				}

				if ( progress ) {
					progress.style.width = ( ( index + 1 ) / pages * 100 ) + '%';
				}

				prev.forEach( function ( button ) {
					button.disabled = ! loop && 0 === index;
				} );
				next.forEach( function ( button ) {
					button.disabled = ! loop && index === pages - 1;
				} );
			}

			// Va avanti o indietro di una slide
			function move( direction ) {
				var index = current() + direction;

				if ( index < 0 ) {
					index = loop ? pages - 1 : 0;
				} else if ( index > pages - 1 ) {
					index = loop ? 0 : pages - 1;
				}

				goTo( index );
			}

			prev.forEach( function ( button ) {
				button.addEventListener( 'click', function () {
					move( -1 );
				} );
			} );
			next.forEach( function ( button ) {
				button.addEventListener( 'click', function () {
					move( 1 );
				} );
			} );
			track.addEventListener( 'scroll', function () {
				window.requestAnimationFrame( update );
			}, { passive: true } );
			track.addEventListener( 'keydown', function ( event ) {
				if ( 'ArrowLeft' === event.key || 'ArrowRight' === event.key ) {
					event.preventDefault();
					move( 'ArrowRight' === event.key ? 1 : -1 );
				}
			} );
			window.addEventListener( 'resize', buildDots );

			// Trascinamento col mouse (col dito scorre già da sola).
			var startX = 0;
			var startLeft = 0;
			var down = false;
			var moved = false;

			track.addEventListener( 'mousedown', function ( event ) {
				down = true;
				moved = false;
				startX = event.pageX;
				startLeft = track.scrollLeft;
			} );
			window.addEventListener( 'mousemove', function ( event ) {
				if ( ! down ) {
					return;
				}

				if ( Math.abs( event.pageX - startX ) > 5 ) {
					moved = true;
					track.classList.add( 'is-dragging' );
				}

				if ( moved ) {
					track.scrollLeft = startLeft - ( event.pageX - startX );
				}
			} );
			window.addEventListener( 'mouseup', function () {
				if ( ! down ) {
					return;
				}

				down = false;

				if ( moved ) {
					// Basta trascinare per un sesto di slide per passare alla successiva (o alla precedente). Lo scarto si legge prima di togliere "is-dragging": lo snap riallinea subito.
					var delta = track.scrollLeft - startLeft;

					track.classList.remove( 'is-dragging' );
					var base = Math.round( startLeft / step() );

					var target = Math.max( 0, Math.min( pages - 1, Math.abs( delta ) > step() / 6 ? base + ( delta > 0 ? 1 : -1 ) : base ) );

					// Con lo snap riattivato il browser riallinea da solo: si parte dal frame successivo.
					window.requestAnimationFrame( function () {
						goTo( target );
					} );
				}
			} );
			track.addEventListener( 'click', function ( event ) {
				if ( moved ) {
					event.preventDefault();
					event.stopPropagation();
					moved = false;
				}
			}, true );

			// Autoplay: si ferma col mouse sopra, col focus dentro, a scheda nascosta e con "riduci animazioni".
			if ( autoplay && ! reduce ) {
				slider.addEventListener( 'mouseenter', function () {
					paused = true;
				} );
				slider.addEventListener( 'mouseleave', function () {
					paused = false;
				} );
				slider.addEventListener( 'focusin', function () {
					paused = true;
				} );
				slider.addEventListener( 'focusout', function () {
					paused = false;
				} );
				slider.addEventListener( 'touchstart', function () {
					paused = true;
				}, { passive: true } );
				slider.addEventListener( 'touchend', function () {
					window.setTimeout( function () {
						paused = false;
					}, 4000 );
				}, { passive: true } );

				timer = window.setInterval( function () {
					if ( paused || document.hidden || pages < 2 ) {
						return;
					}

					if ( ! loop && current() >= pages - 1 ) {
						window.clearInterval( timer );
						return;
					}

					move( 1 );
				}, autoplay );
			}

			buildDots();
			window.addEventListener( 'load', buildDots );
		} );
	}

	/** Video decorativi (hero, slider, testata e media dei progetti: ogni <video autoplay>): senza controlli e con avvio automatico anche su mobile (muto + playsinline; se il browser lo blocca si riprova al primo tocco). */
	function initAutoplayVideos() {
		var videos = document.querySelectorAll( 'video[autoplay], .hero-video video, .hero-media video, .slider-block video' );

		videos.forEach( function ( video ) {
			video.muted = true;
			video.defaultMuted = true;
			video.loop = true;
			video.controls = false;
			video.setAttribute( 'playsinline', '' );
			video.setAttribute( 'webkit-playsinline', '' );
			video.setAttribute( 'disablepictureinpicture', '' );
			video.setAttribute( 'controlslist', 'nodownload nofullscreen noremoteplayback' );

			// Avvia il video, ritentando al primo tocco se il browser lo blocca
			var play = function () {
				var promise = video.play();

				if ( promise && promise.catch ) {
					promise.catch( function () {} );
				}
			};

			play();
			video.addEventListener( 'loadeddata', play );

			if ( 'IntersectionObserver' in window ) {
				new IntersectionObserver( function ( entries ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting && video.paused ) {
							play();
						}
					} );
				} ).observe( video );
			}

			[ 'touchstart', 'click', 'scroll' ].forEach( function ( type ) {
				window.addEventListener( type, function () {
					if ( video.paused ) {
						play();
					}
				}, { once: true, passive: true } );
			} );
		} );
	}

	/** Card del Portfolio: il video parte al passaggio del mouse (o con la tastiera) e si ferma uscendo; su touch parte da solo quando la card è in vista e si ferma uscendo dallo schermo. Mai con "riduci animazioni". */
	function initProjectVideos( root ) {
		if ( ! initProjectVideos.restoreBound ) {
			initProjectVideos.restoreBound = true;

			// Al clic su una card (clic normale, nella stessa scheda) si fermano e si liberano TUTTI i video della pagina: anche solo in download
			// (il video all'hover, la copertina, i video dei contenuti) tengono occupata la connessione e la pagina successiva arriva dopo decine di secondi.
			document.addEventListener( 'click', function ( event ) {
				var link = event.target.closest ? event.target.closest( '.project-card__link' ) : null;

				if ( ! link || event.button || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || link.target ) {
					return;
				}

				document.querySelectorAll( 'video' ).forEach( function ( video ) {
					video.pause();
					video.querySelectorAll( 'source' ).forEach( function ( source ) {
						if ( source.getAttribute( 'src' ) ) {
							source.setAttribute( 'data-src', source.getAttribute( 'src' ) );
							source.removeAttribute( 'src' );
						}
					} );

					if ( video.getAttribute( 'src' ) ) {
						video.setAttribute( 'data-src', video.getAttribute( 'src' ) );
						video.removeAttribute( 'src' );
					}

					video.load();
				} );
			}, true );

			// Tornando indietro (pagina rimessa dal browser) i video liberati riprendono la loro sorgente.
			window.addEventListener( 'pageshow', function ( event ) {
				if ( ! event.persisted ) {
					return;
				}

				document.querySelectorAll( 'video[data-src], video source[data-src]' ).forEach( function ( node ) {
					node.setAttribute( 'src', node.getAttribute( 'data-src' ) );
					node.removeAttribute( 'data-src' );
				} );

				document.querySelectorAll( 'video' ).forEach( function ( video ) {
					video.load();

					if ( video.hasAttribute( 'autoplay' ) ) {
						var playing = video.play();

						if ( playing && playing.catch ) {
							playing.catch( function () {} );
						}
					}
				} );
			} );
		}

		if ( window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			return;
		}

		if ( ! window.matchMedia( '(hover: hover)' ).matches ) {
			initProjectVideosInView( root || document );
			return;
		}

		( root || document ).querySelectorAll( '.project-card__link' ).forEach( function ( link ) {
			var video = link.querySelector( '.project-card__video' );

			if ( ! video || video.hasAttribute( 'data-bound' ) ) {
				return;
			}

			video.setAttribute( 'data-bound', '' );

			// Fa partire il video della card
			function start() {
				var promise = video.play();

				if ( promise && promise.then ) {
					promise.then( function () {
						video.classList.add( 'is-playing' );
					} ).catch( function () {} );
				}
			}

			// Ferma e riavvolge il video
			function stop() {
				video.classList.remove( 'is-playing' );
				video.pause();
				video.currentTime = 0;
			}

			link.addEventListener( 'mouseenter', start );
			link.addEventListener( 'mouseleave', stop );
			link.addEventListener( 'focus', function () {
				if ( link.matches( ':focus-visible' ) ) {
					start();
				}
			} );
			link.addEventListener( 'blur', stop );
		} );
	}

	/** Touch (nessun hover): il video di ogni card parte quando la card è per metà in vista e si ferma quando esce, così sul telefono ne gira solo qualcuno. */
	function initProjectVideosInView( root ) {
		if ( ! ( 'IntersectionObserver' in window ) ) {
			return;
		}

		if ( ! initProjectVideosInView.observer ) {
			initProjectVideosInView.observer = new IntersectionObserver( function ( entries ) {
				entries.forEach( function ( entry ) {
					var video = entry.target;

					if ( entry.isIntersecting ) {
						var promise = video.play();

						if ( promise && promise.then ) {
							promise.then( function () {
								video.classList.add( 'is-playing' );
							} ).catch( function () {} );
						}
					} else {
						video.classList.remove( 'is-playing' );
						video.pause();
					}
				} );
			}, { threshold: 0.5 } );
		}

		root.querySelectorAll( '.project-card__video' ).forEach( function ( video ) {
			if ( video.hasAttribute( 'data-bound' ) ) {
				return;
			}

			video.setAttribute( 'data-bound', '' );
			video.muted = true;
			initProjectVideosInView.observer.observe( video );
		} );
	}

	/** Filtri dell'elenco progetti su telefono: la riga scorre di lato, quindi il filtro attivo si porta al centro (senza muovere la pagina). */
	function centerActiveFilter( root ) {
		root.querySelectorAll( '.project-tabs__list' ).forEach( function ( list ) {
			var active = list.querySelector( '.is-active' );

			if ( active && list.scrollWidth > list.clientWidth ) {
				list.scrollLeft = active.offsetLeft - ( list.clientWidth - active.offsetWidth ) / 2;
			}
		} );
	}

	/** Footer "Stile 5": il nome grande riempie la larghezza del footer, qualunque sia la lunghezza del nome o il font. */
	function initFooterFit() {
		var name = document.querySelector( '.footer-mega__name' );
		var text = name ? name.firstElementChild : null;

		if ( ! text ) {
			return;
		}

		// Adatta la dimensione del nome alla larghezza del footer
		function fit() {
			name.style.fontSize = '100px';

			var natural = text.getBoundingClientRect().width;
			var box = name.clientWidth;

			if ( natural > 0 && box > 0 ) {
				name.style.fontSize = Math.max( 24, Math.floor( ( 100 * box ) / natural ) ) + 'px';
			}
		}

		fit();

		if ( document.fonts && document.fonts.ready ) {
			document.fonts.ready.then( fit );
		}

		window.addEventListener( 'resize', fit );
	}

	/**
	 * Transizione tra le pagine dei progetti (scheda Animazioni del blocco Elenco progetti; tra un progetto e l'altro, Opzioni → Portfolio):
	 * cover | tendina | dissolvenza. Niente View Transitions: al clic si disegna un velo sulla pagina (classi "pt-…" su <html>, vedi CSS),
	 * si salva cosa mostrare in sessionStorage e si apre il progetto; la pagina di arrivo ha già il velo (script in <head>, vedi inc/portfolio.php)
	 * e lo toglie quando è pronta. L'animazione non dipende dalla velocità di caricamento.
	 */
	function initProjectTransition() {
		var root = document.documentElement;
		var meta = document.querySelector( 'meta[name="starter-project-transition"]' );
		var mode = meta ? meta.getAttribute( 'content' ) : '';
		var KEY = 'starterPt';
		var VARS = [ '--pt-img', '--pt-name', '--pt-text', '--pt-bg', '--pt-fg', '--pt-n', '--pt-t', '--pt-r', '--pt-b', '--pt-l', '--pt-rad', '--pt-bs0', '--pt-bs1', '--pt-bs2', '--pt-lt', '--pt-lr', '--pt-lb', '--pt-ll', '--pt-lrad' ];

		// Toglie le classi e le variabili della transizione
		function clear() {
			root.className = root.className.replace( /\bpt-[\w-]+/g, '' ).replace( /\s+/g, ' ' ).trim();
			VARS.forEach( function ( name ) {
				root.style.removeProperty( name );
			} );

			try {
				window.sessionStorage.removeItem( KEY );
			} catch ( error ) {}
		}

		// Dimensione "cover" in pixel di un'immagine di proporzione ar dentro un riquadro: serve per animare riquadro e immagine insieme, senza scatti.
		function coverSize( width, height, ar ) {
			var w = width;
			var h = width / ar;

			if ( h < height ) {
				h = height;
				w = height * ar;
			}

			return w + 'px ' + h + 'px';
		}

		// Tornando indietro con il tasto del browser la pagina può riapparire con il velo ancora acceso.
		window.addEventListener( 'pageshow', function ( event ) {
			if ( event.persisted ) {
				clear();
			}
		} );

		// Pagina di arrivo: il velo c'è già; lo si toglie a pagina pronta. Il tipo e le opzioni si leggono dal velo, non dalla pagina.
		if ( root.classList.contains( 'pt-in' ) ) {
			var data = {};

			try {
				data = JSON.parse( window.sessionStorage.getItem( KEY ) || '{}' ) || {};
			} catch ( error ) {}

			var landMode = ( root.className.match( /\bpt-(cover|tendina|dissolvenza)\b/ ) || [ '', '' ] )[ 1 ];

			var counter = root.classList.contains( 'pt-counter' );
			var started = window.performance.now();
			var minimum = 'dissolvenza' === landMode ? 900 : 450;
			var landed = false;
			var shown = 0;

			// Fa atterrare la transizione quando la pagina è pronta
			var land = function () {
				if ( landed ) {
					return;
				}

				landed = true;

				var cover = document.querySelector( '.project-cover' );
				var media = cover ? ( cover.querySelector( 'img, video' ) || cover ) : null;
				var rect = media ? media.getBoundingClientRect() : null; // L'immagine vera, non la fascia che la contiene: può essere più stretta.
				var video = media && 'VIDEO' === media.tagName ? media : null;
				// Rivela la copertina e chiude la transizione
				var done = function () {
					if ( cover ) {
						cover.style.visibility = '';
					}

					// Con il video sotto il velo l'immagine svanisce in dissolvenza: il video sta già andando e non c'è lo scatto della partenza.
					if ( video && root.classList.contains( 'pt-land' ) ) {
						root.classList.add( 'pt-fade' );
						window.setTimeout( clear, 650 );

						return;
					}

					clear();
				};

				// Copertina: il velo a tutto schermo si ridimensiona fino a combaciare con la copertina vera (se è visibile), poi sparisce.
				// Riquadro e immagine cambiano insieme con la stessa curva, quindi il movimento è uniforme.
				var landCover = function () {
					if ( ! video ) {
						cover.style.visibility = 'hidden';
					} else if ( video.paused ) {
						var playing = video.play();

						if ( playing && playing.catch ) {
							playing.catch( function () {} );
						}
					}

					root.style.setProperty( '--pt-lt', rect.top + 'px' );
					root.style.setProperty( '--pt-ll', rect.left + 'px' );
					root.style.setProperty( '--pt-lr', ( root.clientWidth - rect.right ) + 'px' );
					root.style.setProperty( '--pt-lb', ( window.innerHeight - rect.bottom ) + 'px' );
					root.style.setProperty( '--pt-lrad', window.getComputedStyle( media ).borderRadius );

					if ( data.ar > 0 ) {
						root.style.setProperty( '--pt-bs2', coverSize( rect.width, rect.height, data.ar ) );
					}

					root.classList.add( 'pt-land' );
					window.setTimeout( done, 1050 );
				};

				if ( 'cover' === landMode && rect && rect.height > 0 && rect.top < window.innerHeight * 0.7 ) {
					// Video: prima che il velo atterri serve un fotogramma pronto, altrimenti sotto c'è il vuoto e il video "scatta" alla partenza.
					if ( video && video.readyState < 2 ) {
						var begun = false;
						// Parte quando c'è un fotogramma del video (o dopo 2 secondi)
						var go = function () {
							if ( ! begun ) {
								begun = true;
								landCover();
							}
						};

						video.addEventListener( 'loadeddata', go, { once: true } );
						window.setTimeout( go, 2000 );
					} else {
						landCover();
					}

					return;
				}

				root.classList.add( 'pt-leave' );
				window.setTimeout( done, 1100 );
			};

			var loaded = 'complete' === document.readyState;

			if ( ! loaded ) {
				window.addEventListener( 'load', function () {
					loaded = true;
				}, { once: true } );
			}

			// Aggiorna il contatore e decide quando far atterrare la transizione
			var tick = function () {
				if ( landed ) {
					return;
				}

				var elapsed = window.performance.now() - started;

				if ( counter ) {
					// Sale con la pagina: fino al 90% a tempo, poi (a pagina pronta) arriva a 100. L'animazione finisce solo dopo il 100%.
					var target = loaded && elapsed >= minimum ? 100 : Math.min( 90, ( elapsed / 1400 ) * 90 );

					shown += ( target - shown ) * 0.12 + ( target > shown ? 0.25 : 0 );
					shown = Math.min( 100, shown );
					root.style.setProperty( '--pt-n', String( Math.round( shown ) ) );

					if ( shown >= 99.6 ) {
						root.style.setProperty( '--pt-n', '100' );
						window.setTimeout( land, 250 );

						return;
					}
				} else if ( loaded && elapsed >= minimum ) {
					land();

					return;
				}

				window.requestAnimationFrame( tick );
			};

			window.requestAnimationFrame( tick );
			window.setTimeout( land, 6000 );

			// Niente "return": anche da questa pagina (arrivata con un'animazione) le card di "Altri progetti" devono animare al clic.
		}

		if ( ! mode || window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
			return;
		}

		document.addEventListener( 'click', function ( event ) {
			var link = event.target.closest ? event.target.closest( '.project-card__link' ) : null;

			if ( ! link || event.defaultPrevented || event.button || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || link.target || root.classList.contains( 'pt-go' ) ) {
				return;
			}

			event.preventDefault();

			var media = link.querySelector( '.project-card__media' );
			var image = media ? media.querySelector( 'img' ) : null;
			var rect = media ? media.getBoundingClientRect() : { top: 0, left: 0, right: window.innerWidth, bottom: window.innerHeight, width: window.innerWidth, height: window.innerHeight };
			var ar = image && image.naturalWidth ? image.naturalWidth / image.naturalHeight : 0;
			var data = {
				mode: mode,
				src: image ? ( image.currentSrc || image.src ) : '',
				name: link.getAttribute( 'data-name' ) || '',
				text: meta.getAttribute( 'data-text' ) || '',
				counter: '1' === meta.getAttribute( 'data-counter' ),
				bg: meta.getAttribute( 'data-bg' ) || '',
				fg: meta.getAttribute( 'data-fg' ) || '',
				ar: ar,
				t: Date.now()
			};
			// Rende un valore sicuro da scrivere in una stringa di stile
			var quote = function ( value ) {
				return JSON.stringify( String( value ).replace( /[\\\n\r]/g, ' ' ) );
			};

			root.style.setProperty( '--pt-img', 'url(' + quote( data.src ) + ')' );
			root.style.setProperty( '--pt-name', quote( data.name ) );
			root.style.setProperty( '--pt-text', quote( data.text ) );
			root.style.setProperty( '--pt-n', '0' );

			if ( data.bg ) {
				root.style.setProperty( '--pt-bg', data.bg );
			}

			if ( data.fg ) {
				root.style.setProperty( '--pt-fg', data.fg );
			}

			root.style.setProperty( '--pt-t', rect.top + 'px' );
			root.style.setProperty( '--pt-l', rect.left + 'px' );
			root.style.setProperty( '--pt-r', ( root.clientWidth - rect.right ) + 'px' );
			root.style.setProperty( '--pt-b', ( window.innerHeight - rect.bottom ) + 'px' );
			root.style.setProperty( '--pt-rad', media ? window.getComputedStyle( media ).borderRadius : '0px' );

			if ( ar > 0 ) {
				root.style.setProperty( '--pt-bs0', coverSize( rect.width, rect.height, ar ) );
				root.style.setProperty( '--pt-bs1', coverSize( root.clientWidth, window.innerHeight, ar ) );
			}

			try {
				window.sessionStorage.setItem( KEY, JSON.stringify( data ) );
			} catch ( error ) {}

			root.classList.add( 'pt-' + mode );

			if ( data.counter ) {
				root.classList.add( 'pt-counter' );
			}

			void root.offsetWidth; // Il velo parte da dove sta la card, poi cresce.
			root.classList.add( 'pt-go' );

			window.setTimeout( function () {
				window.location.href = link.href;
			}, { cover: 900, tendina: 800, dissolvenza: 600 }[ mode ] );
		} );
	}

	/**
	 * Elenco progetti con "Resta nella stessa pagina": schede delle categorie e paginazione scaricano la stessa pagina e sostituiscono solo il
	 * contenuto del blocco, senza ricaricare. L'indirizzo si aggiorna (indietro/avanti funzionano); se qualcosa non va si apre il link normale.
	 */
	function initProjectFilters() {
		var selector = '.project-list[data-filter-mode="stessa"]';

		if ( ! document.querySelector( selector ) || ! window.fetch || ! window.DOMParser || ! window.history.pushState ) {
			return;
		}

		window.history.replaceState( { projectFilter: true }, '', window.location.href );

		// Scarica la pagina e ne sostituisce il contenuto dell'elenco
		function load( url, push ) {
			var lists = document.querySelectorAll( selector );

			lists.forEach( function ( list ) {
				list.classList.add( 'is-loading' );
			} );

			fetch( url, { credentials: 'same-origin' } ).then( function ( response ) {
				if ( ! response.ok ) {
					throw new Error( 'http' );
				}

				return response.text();
			} ).then( function ( html ) {
				var fresh = new DOMParser().parseFromString( html, 'text/html' ).querySelectorAll( selector );

				lists.forEach( function ( list, index ) {
					var current = list.querySelector( '.block-wrap' );
					var next = fresh[ index ] ? fresh[ index ].querySelector( '.block-wrap' ) : null;

					if ( current && next ) {
						current.innerHTML = next.innerHTML;
						initProjectVideos( current );
						centerActiveFilter( current );
					}
				} );

				if ( push ) {
					window.history.pushState( { projectFilter: true }, '', url );
				}
			} ).catch( function () {
				window.location.href = url;
			} ).then( function () {
				lists.forEach( function ( list ) {
					list.classList.remove( 'is-loading' );
				} );
			} );
		}

		document.addEventListener( 'click', function ( event ) {
			var link = event.target.closest ? event.target.closest( selector + ' .project-tabs__link, ' + selector + ' .pagination a' ) : null;

			if ( ! link || event.defaultPrevented || event.button || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || link.target ) {
				return;
			}

			event.preventDefault();
			load( link.href, true );
		} );

		window.addEventListener( 'popstate', function ( event ) {
			if ( event.state && event.state.projectFilter ) {
				load( window.location.href, false );
			}
		} );
	}

} )();
