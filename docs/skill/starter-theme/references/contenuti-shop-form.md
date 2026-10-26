# Pagine di esempio, menu, shop, moduli

## Contenuti di prova (Strumenti → Contenuti di prova)

`inc/demo-content.php` crea Privacy e Cookie (bozze con tabella CookieYes), Preferiti, Blog, un menu con sottomenu, 7 articoli e, con WooCommerce, 12 prodotti finti con categorie e attributi. Si può rilanciare senza duplicare; "Elimina i contenuti di prova" toglie ciò che è marcato come prova.

`inc/starter-pages.php` genera le **pagine standard** con i blocchi: Home, Chi siamo, Servizi, Contatti, Credits (`starter_pages_create( $force )`; con `$force` rigenera anche se esistono) più Preferiti (`starter_wishlist_page_create()`). Ogni pagina è una funzione `starter_pg_*()`; i blocchi si scrivono con `starter_pg_block( 'nome', array( campo => valore ) )` (usa la mappa `starter_pg_keys()`), i blocchi con testo Gutenberg dentro con `starter_pg_block_inner()`. Immagini segnaposto: `starter_pg_images()`; video demo: `starter_pg_video( 'file.mp4', 'Titolo' )` (importa in libreria da `images/`, Mixkit, licenza gratuita anche commerciale).

Home di base: hero-slider → striscia valori → sezione affiancata → servizi (sezione-voci) → hero-video → prodotti in evidenza → testo affiancato → ultimi articoli → recensioni → CTA con Copertina in parallasse.

**Dopo aver cambiato un blocco o un generatore**: rigenera le pagine in locale e riverifica nell'editor che i blocchi siano validi.

## Menu

Posizioni: **Header** (principale, anche colonna menu del footer senza sottomenu), **Mobile** (facoltativa), **Privacy**, **footer-link** e **footer-riferimenti** (colonne del footer 4; il titolo della colonna è il nome del menu). Il menu del sito di esempio si chiama "MENU PRINCIPALE" (Chi siamo, Servizi, Shop con sottomenu Casa/Accessori/Ufficio/Tutti i prodotti, Blog, Contatti). Il link "Shop" deve puntare alla **pagina Shop reale** (`wc_get_page_permalink('shop')`, es. `/negozio/`), mai a un indirizzo scritto a mano.

## WooCommerce

- Supporto in `inc/woocommerce.php` (CSS Woo solo dove serve, icone shop, card, scheda prodotto, campi fattura P.IVA/CF/SDI), preferiti in `inc/wishlist.php` (localStorage + endpoint admin-ajax; pagina con shortcode `[starter_preferiti]`; opzioni "Mostra preferiti nell'header" e pagina preferiti), filtri in `inc/shop-filters.php` (GET con prefisso `f_`), card in `woocommerce/content-product.php`.
- **Permalink**: Impostazioni → Permalink → base prodotto e base categoria (es. `prodotto`, `categoria-prodotto`). Devono essere **uguali tra locale e online**, altrimenti le categorie danno 404 (es. `/product-category/…` vs `/categoria-prodotto/…`).
- Griglia prodotti: 4 colonne desktop, 3 sotto 1100px, 2 sotto 780px, **1 sotto 480px** (anche con colonne 2/3 scelte nelle opzioni).
- Il pulsante "Aggiungi" sulle card è sempre visibile su touch.

## Contact Form 7 + Flamingo (`inc/cf7.php`)

- Ogni modulo riceve da solo un **honeypot** (campo `starter_hp_check` + orario firmato `starter_ts`, minimo 3 s) e i campi nascosti `[hidden pagina]`, `[hidden pagina-url]`; la mail riporta "Pagina: …". Auto-paragrafo di CF7 disattivato.
- **Oggetto nascosto**: i moduli del tema non mostrano un campo "Oggetto": hanno `[hidden your-subject "Richiesta dal modulo contatti"]` (newsletter: `"Iscrizione alla newsletter"`) che Flamingo legge come oggetto.
- **Modulo Contatti** (creato da `starter_contact_form_create()`): griglia `.form-grid` con Nome e Cognome affiancati, Telefono ed Email affiancati, "Come posso aiutarti?" a scelta multipla (checkbox a pillole), Note, privacy, "Invia la richiesta". Sotto i 600px una colonna sola. Campi a tutta larghezza: classe `form-grid__full`.
- **Newsletter** (creata da `starter_newsletter_form_create()`): nome, email, privacy; il footer la mostra con lo shortcode in Opzioni → Footer → Spazio libero. Se l'ID cambia (locale → online) aggiorna l'opzione `starter_footer_newsletter_shortcode`.
- **Accettazione privacy**: `starter_cf7_privacy_line()` genera `[acceptance privacy] Ho letto la <Privacy Policy> e accetto il trattamento dei dati [/acceptance]` con link alla pagina Privacy.
- Spinner `.wpcf7-spinner` assoluto e visibile solo durante l'invio (vedi `regole-e-css.md`). Nell'editor `.hidden-fields-container` va nascosto (CF7 non carica il suo CSS lì).

## CookieYes

`inc/cookieyes.php` imposta banner, categorie e pulsanti in italiano (anche dal pulsante in Contenuti di prova).

## Immobiliare

Il modulo immobiliare non è nello starter. Vedi `docs/RICETTA-IMMOBILIARE.md` del tema (se presente) per cosa riprendere dal progetto Di Guida Real Estate.

## Portfolio (contenuti di prova)

`starter_portfolio_demo()` crea 9 progetti (titolo = nome del cliente, riassunto, testo sotto il titolo, categorie, copertine segnaposto, 2 con video) e le pagine dell'elenco: Classica (`/portfolio/`), Masonry, A blocchi, Sfalsata, con filtri e transizioni diversi in ciascuna. Menu: voce **Portfolio** non cliccabile con le 4 sottovoci. Dettagli in `portfolio.md`.
