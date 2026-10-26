# Regole di stile e trappole CSS

## Stile: sempre dalle opzioni

- **Testi**: mai `font-size` a mano. Classi `title-display`, `title-h1…h6`, `txt`, `eyebrow`, `has-large-font-size`… oppure `var(--wp--preset--font-size--small)` ecc. Gli elementi "Titolo grande hero" e "Pulsanti" sono in Stile testi.
- **Colori**: solo variabili `--color-*` / `--footer-*`. Per un hover che riempie di verde fluo usa `--color-accent-cta` e il **testo `--color-text`** (nero). Per testo chiaro su colore principale: `--color-primary-text`.
- **Larghezze**: `.starter-container` (contenuto), `.starter-wide` (largo), `.block-wrap` (blocchi), `--gutter`. I titoli di pagina e le sezioni usano le stesse.
- **Spaziature utili**: classi `mt1…mt8`, `mb…`, `pt…`, `pb…`, `p…` con varianti `-tab` e `-sma` (es. `mt4 mt2-tab mt1-sma`), da scrivere in "Classi CSS aggiuntive" del blocco.

## Animazioni

- Morbide, non dirette: `transition` 0.4–0.6 s con `ease` o `cubic-bezier(0.65, 0, 0.35, 1)`. Esiste una base `:where(a, button, input, textarea, select, summary)` con transizioni su colore/sfondo/bordo/opacità/ombra/trasformazione (a specificità zero: le regole specifiche vincono).
- Pulsanti: riempimento che sale dal basso (`::before` con `scaleY`) + testo "flip". Cambio logo → icona allo scroll: dissolvenza + restringimento (`max-width`/`opacity`), mai `display:none` secco.
- Rispetta `prefers-reduced-motion` per animazioni lunghe (slider, preloader).

## Responsive

Breakpoint usati: 1180 (menu ridotto: pannello a tendina, hamburger quadrato), 1099/1023, 899, 782 (footer e righe in colonna), 639/600 (griglie di campi a una colonna), 480 (prodotti e card a icone a una colonna), 420. Su mobile i pulsanti nelle righe vanno a capo, gli input a coppie diventano singoli, le voci del menu mobile occupano il 100% con area di tocco di almeno 48px.

Menu mobile: `ul`, `li`, `a` e sottomenu al 100% della larghezza, voci del sottomenu uguali (dimensione, peso, padding) a quelle del menu.

## Trappole già incontrate (da ricordare)

1. **Specificità e griglie dei prodotti**: le opzioni "colonne" mettono `body.shop-cols-3 ul.products` (specificità alta). Per sovrascrivere a un breakpoint usa `:is(body, body.shop-cols-2, body.shop-cols-3) ul.products`.
2. **Flex in colonna e `flex-basis`**: quando una riga flex diventa `flex-direction: column`, un `flex: 1 1 360px` diventa un'**altezza** e crea spazio vuoto. Metti `flex: 0 0 auto` nel breakpoint.
3. **Elementi con `min-width` in griglia a 2 colonne** (es. contatti del footer): sforano e si sovrappongono. Aggiungi `min-width: 0` e `overflow-wrap: anywhere`, o passa a una colonna sotto i 480px.
4. **`gap` + margini**: dentro `.section-head__text` il `gap` (14px) separa occhiello e titolo; per avvicinarli usa un margine negativo sul titolo, non `margin: 0`.
5. **Bucket del build**: una classe che inizia con un prefisso di bucket (`blog-`, `sidebar`, `breadcrumb`, `panel`, `split`, `hero-slider`…) finisce in un foglio non sempre caricato. Per le briciole in barra si usa `.crumbs-bar`.
6. **Hover su fondo verde fluo con testo bianco** = illeggibile: sempre `color: var(--color-text)` anche su `:focus-visible`.
7. **Il pulsante CTA dell'header** nelle pagine WooCommerce prende lo stile generico di `a.button` del plugin: serve una regola `html body .site-header a.button.button--cta` per riportarlo allo stile del tema.
8. **Selettori `a.wp-block-button__link`**: nell'editor i pulsanti sono `<div>`, quindi le regole del sito non si applicano; duplicale in `gutenberg.css` per `.editor-styles-wrapper .wp-block-button__link`.
9. **Spinner di Contact Form 7**: deve essere `position: absolute` e visibile solo mentre il modulo invia, altrimenti lascia uno spazio vuoto accanto al pulsante.
10. **Testi di accettazione privacy**: usano il preset `--wp--preset--font-size--small` (più piccoli del testo dei campi) sia nei moduli contatti sia nel footer.
11. **Pulsante Menu mobile**: quadrato (`width = height = --header-button-size`), solo icona.
12. **Pagina 404**: usa `starter_page_header()` e il bucket `blocks`; il form di ricerca ha input e pulsante alti `--header-button-size`.

## Commenti nel codice

In italiano, brevi, che spiegano il *perché*. Nei CSS un commento sopra ogni gruppo di regole di un componente.

## Filtri degli elenchi
Un solo stile per tutti: `CSS/filters.css` + `initFilters()` in `JS/scripts.js` (classi `filters-wrap`, `filters`, `filters__grid`, `filters__field`, `filters__actions`, `filters-toolbar`, `filters-toggle`, `multiselect`). Shop e Immobili lo usano già; ogni nuovo tipo di contenuto con filtri riusa questo markup e non scrive CSS dei filtri propri. Il foglio si carica con il filtro `starter_needs_filters_css`.

## Pulsanti
Le varianti di pulsante (Pieno 1/2, Contorno 1/2) si scelgono in Opzioni → Pulsanti e arrivano al CSS come variabili `--b-*` (vedi CSS/starter.css vicino a `.button`). Nel markup si usano le classi `.button`, `.button--outline`, `.button--fill-2`, `.button--outline-2` (in Gutenberg gli stili del blocco Pulsante): mai colori scritti a mano sul singolo pulsante.

Pulsanti nei blocchi ACF: aggiungere il link in `starter_button_link_fields()` (inc/acf-blocks.php), così compare il menu "Stile del pulsante", e nel render usare `starter_button_class( get_field( 'nome_stile' ), 'classi predefinite' )`. Fuori dai blocchi: principale = `.button` (Pieno 1), secondario = `.button--outline` (Contorno 1). Angoli: Opzioni → Generale → Bordi del sito cambia le variabili `--radius-*`; mai raggi fissi nei blocchi.

## Bordi e angoli (regola del tema)

Tutto ciò che ha gli angoli arrotondati segue **Opzioni → Generale → Bordi del sito** (`starter_corner_radius()` → `--radius-button` nella variabile `--radius-*`); i **pulsanti** hanno una scelta a parte (`--radius-btn`). Gli elementi piccoli (tab, chip, icone) usano `--radius-s` / `--radius-xs`, derivati e limitati in proporzione, così non diventano cerchi. **Carrello e cassa** restano a 4px fissi (variabili scritte su `.woocommerce-cart/.woocommerce-checkout .site-content`). Non scrivere mai un `border-radius` a mano nei componenti.

## Altre trappole incontrate (Portfolio e testate)

13. **`.txt` ha `margin-bottom: 1rem` scritto nel CSS inline**: una regola con la stessa specificità scritta nel foglio del tema perde. Per togliere il margine sotto un testo `.txt` usa un selettore più specifico (`.page-header__main .project-intro`).
14. **`.project-card__media img`** (riempire, ritagliare, ingrandire all'hover, posizione assoluta nelle viste a blocchi/sfalsata) colpisce anche un logo `<img>` messo dentro la card: va protetto con una regola più specifica (`.project-card__media .project-card__reveal img.project-card__logo`).
15. **SVG nella Libreria media senza dimensioni** (`width/height` = 0): non si può calcolare la proporzione dal metadato; leggere `width/height` o `viewBox` dal file (`starter_portfolio_logo_ratio()`).
16. **Il velo di transizione sta su `<html>`** (classi `pt-*`): ogni script che esce presto nella pagina di arrivo salta anche la registrazione del clic. Mai `return` prima di aver registrato gli eventi.
17. **Tabelle di dati su telefono**: una griglia a 2 colonne (`.project-meta`) va portata a 1 colonna sotto i 640px, altrimenti il testo si schiaccia.
18. **ACF in tabella**: dentro un ripetitore con `layout: table` le larghezze `wrapper.width` dei sotto-campi non valgono.
19. **Un video che scarica in background blocca la navigazione (solo online)**: il video all'hover di una card, ancora in download al clic, può far arrivare la pagina successiva dopo decine di secondi quando il sito è dietro un CDN. In locale non succede e quindi sfugge: i test di navigazione vanno rifatti anche sul sito online e le sorgenti dei video liberate al clic.
