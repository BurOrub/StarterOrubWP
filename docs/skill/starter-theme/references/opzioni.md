# Opzioni del tema (Customizer)

Pannello **Aspetto → Personalizza → Opzioni** (`starter_options`). Le opzioni sono `theme_mod` con prefisso `starter_`.

## Sezioni

- **Identità del sito** (nativa): logo, icona sito, logo chiaro per header trasparente e footer.
- **Generale**: commenti, larghezza contenuti, **Bordi del sito** (angoli di card, tab, immagini, riquadri e campi: tutto segue questa scelta, tranne i pulsanti; carrello/cassa fissi a 4px), animazioni (**caricamento delle pagine**: nessuno / dissolvenza / tenda dal basso con testo, percentuale, nome del sito o logo, colori; scroll fluido Lenis), SVG come vettori, **allineamento della paginazione** (vale per tutti i CPT), social (7 URL + 5 icone SVG personalizzate), briciole di pane (stile inline o barra), WhatsApp.
- **Tipografia**: Font 1 e 2 (di sistema o Google), Font 3 e 4 caricati dall'utente (varianti peso/stile, TTF/WOFF/WOFF2…), "Applica a" H1–H6/Body.
- **Colori**: sfondo e testo, colore principale e secondario (con "Applica a" titoli), pulsante principale e secondario, pulsante Menu mobile, form newsletter del footer, icone social, link, tag. I colori sono "Colori con nome" (slot) risolti da `starter_resolve_named_color()`.
- **Stile testi**: dimensioni di display, title-1…5, eyebrow, x-large…small, button per desktop/tablet/mobile (variabili `--wp--preset--font-size--*`).
- **Pulsanti**: arrotondamento, maiuscolo, **dimensione** (più piccola/standard…) e **padding** personalizzato, 4 varianti (Pieno 1/2, Contorno 1/2) con colori a riposo e hover.
- **Header**: versione (Standard/Centrato/Ridotto/**Pillole**: senza sfondo, menu e icone in pillole con gli angoli di "Bordi del sito", niente social, solo icone shop), flottante, tutta larghezza, CTA, stile icone (con bordo/semplici/strette), ordine social/icone, divisore, ricerca, preferiti, logo allo scroll (uguale/più piccolo/icona), sottomenu col click.
- **Footer**: stile 1–5 (**stile 5**: frase grande, sedi, elenco contatti), colonne (testo, contatti, menu), "Spazio libero" (titolo, sottotitolo, shortcode/HTML), copyright con `{anno}` e `{sito}`, link Privacy/Cookie/Termini/Credits, immagine pagamenti (stile 4).
- **Blog**: colonne 1–4, ultimo articolo in evidenza, tag, categorie, aside, autore, correlati.
- **Moduli (CPT)** (`inc/modules.php`): un interruttore per modulo (Portfolio, Immobili…), tutti spenti di partenza; spento = file non caricato, pagina del modulo in bozza. Nuovo modulo = una voce in `starter_modules()`.
- **Immobili** (`inc/modules/immobili/`, solo con il modulo acceso): shortcode del modulo "Richiedi informazioni".
- **Portfolio** (`inc/portfolio.php`, solo con il modulo acceso): dati nella testata del progetto, "Altri progetti" (categorie, riassunto e cliente, proporzione, animazione logo, video hover: prefisso `starter_portfolio_rel_*`) e **Passaggio tra i progetti** (`starter_portfolio_single_*`). L'elenco non ha opzioni qui: è il blocco `progetti` in una pagina. Nessun archivio del CPT. Vedi `portfolio.md`.
- **Shop** (solo con WooCommerce): filtri, colonne, card, avviso negozio in test, preferiti.

## Aggiungere un'opzione

1. Registrala in `inc/customizer-extra.php` (o nella sezione giusta di `functions.php`) con `add_setting` + `add_control`; sanitizzazione adeguata (`sanitize_text_field`, `esc_url_raw`, checkbox → bool, select → whitelist).
2. **Default** accanto all'uso (`get_theme_mod( 'starter_x', default )`) o in una funzione `*_defaults()`; mai due default diversi per la stessa opzione.
3. **Descrizione di una riga** che dice a cosa serve e cosa cambia, scritta per un non sviluppatore.
4. Se produce CSS, usa `body_class` (es. `icons-boxed`, `logo-scroll-icon`) o una variabile CSS inline; niente stile scritto nell'opzione.
5. Se influenza il layout, controlla header/footer in tutte le versioni e a 390px.

## Valori che spesso servono nei test

`starter_header_style`, `starter_footer_style` (`style-1`…`style-5`), `starter_preloader` (`none|dissolvenza|tendina`), `starter_portfolio_single_transition` (`nessuna|cover|tendina|dissolvenza`), `starter_header_scroll_logo` (`none|small|icon`), `starter_header_show_search`, `starter_header_show_wishlist`, `starter_content_width`, `starter_footer_newsletter_shortcode`, `starter_shop_wishlist_page`.

Per cambiare un'opzione da riga di comando (test): `set_theme_mod( 'starter_footer_style', 'style-2' );` via `wp eval` o script PHP che carica `wp-load.php`.
