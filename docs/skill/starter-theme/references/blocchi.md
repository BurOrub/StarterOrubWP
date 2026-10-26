# Blocchi ACF del tema (richiedono ACF Pro)

Categoria "Starter" nell'inserimento blocchi. Ogni blocco è una cartella in `template-parts/<nome>/` con `block.json` + `render.php`; i campi sono registrati da codice con `acf_add_local_field_group()` in `inc/acf-blocks.php` e `inc/acf-blocks-extra.php` (niente `acf-json/`). Il blocco compare nel contenuto come `<!-- wp:starter-theme/<nome> {"name":…,"data":{campo:valore,"_campo":"field_key"}} /-->`.

## Elenco e campi (`name` dei campi ACF)

| Blocco | Campi |
|---|---|
| `intestazione-pagina` | occhiello, titolo (H1; `<em>` per il corsivo colorato), testo, **posizione_testo** (`sotto` default / `destra`) |
| `hero-media` (Hero con foto e video) | occhiello, titolo, testo, cta_primaria, cta_secondaria, clienti (foto), clienti_testo, foto, etichetta_foto + colore, video, copertina_video, etichetta_video + colore |
| `hero-split` | occhiello, titolo, testo, cta_primaria, cta_secondaria, foto, lato_foto |
| `hero-slider` | immagini (1–6), occhiello, titolo, testo, link, **link_2** (secondo pulsante facoltativo), durata |
| `hero-video` | video (mp4/webm), copertina, occhiello, titolo, testo, link, **link_2** |
| `striscia-valori` | voci (icona, titolo) |
| `sezione-affiancata` | occhiello, foto (1–2), formato, lato, sfondo + **testo scritto con blocchi Gutenberg** (InnerBlocks) |
| `testo-affiancato` | lato_media, sfondo, livello, occhiello, titolo, testo, checklist, citazione, link, immagini |
| `sezione-voci` | layout (`numerato`/`card`/`icone`/`passi`), sfondo, livello, occhiello, titolo, testo, link, voci (titolo, testo, icona, link), cta |
| `elenco-servizi` | voci (ancora, icona, occhiello, titolo, etichetta menu, testo, foto, elenco con spunte, pulsante + stile): menu laterale che segue lo scroll, barra di icone su mobile |
| `slider` | Slider singolo o carosello: gallery di immagini e video mp4/webm (didascalia = quella del file), tipo, larghezza (misura del sito / tutto schermo), immagini visibili desktop/tablet(≤1100)/mobile(≤640), distanza in px, anteprima delle vicine (px), proporzione, frecce (sull'immagine / sopra / sotto) + stile (4 pulsanti), paginazione (puntini / contatore / barra), autoplay, ricomincia, didascalie, angoli. Classi `.slider-block*`, JS `initSliders()` |
| `missione-visione` (Due riquadri) | voci (occhiello, titolo, testo) |
| `team` | occhiello, titolo, testo, voci (foto, nome, ruolo, bio) |
| `progetti` | Elenco dei progetti del Portfolio, con schede **Layout** (classica/masonry/a blocchi/sfalsata, colonne, distanze, proporzione, quarto elemento, per pagina, video hover), **Titolo** (animazione logo, riassunto/cliente: tag, dimensione, posizione, colori), **Categorie** (testo/tab, forma, colori, margini), **Filtri** (linea/testo grande/pillole/frase, colori, stessa pagina o ricarica), **Animazioni** (passaggio verso il progetto: nessuna/copertina/tendina/dissolvenza). Dettagli in `portfolio.md` |
| `elenco-immobili` (Immobili — Elenco) | occhiello, titolo, testo (intestazione di pagina + filtri e griglia) — modulo Immobili |
| `immobili-in-evidenza` (Immobili — In evidenza) | occhiello, titolo, testo, link — 3 immobili con Evidenza "In evidenza home" — modulo Immobili |
| `immobili-hero-home` (Immobili — Hero home) | occhiello, titolo, testo, cta_primaria, cta_secondaria — immobile con Evidenza "Hero home" — modulo Immobili |
| `progetto-testo` | occhiello, titolo, testo (paragrafi separati da riga vuota), disposizione (`affiancato`/`centrato`) — schede del Portfolio |
| `progetto-media` | colonne (1/2/3), proporzione, elementi (tipo `immagine`/`video`, immagine, video, copertina, didascalia) — schede del Portfolio |
| `progetto-crediti` | titolo, voci (ruolo, nome, **link** facoltativo al sito del collaboratore) — schede del Portfolio |
| `progetto-testata` | Solo anteprima nell'editor (non inseribile, bloccato, non si salva): mostra copertina, titolo e dati del progetto presi dai dettagli — schede del Portfolio |
| `recensioni` | occhiello, titolo, link, voci (nome, testo, data), video |
| `ultimi-articoli` | occhiello, titolo, testo, link, categoria, numero |
| `prodotti-in-evidenza` | occhiello, titolo, testo, link, sorgente (`evidenza/venduti/recenti/categoria/scelti`), categoria, prodotti, numero |
| `instagram` | occhiello, titolo, account, link, foto |
| `contatti` | occhiello, titolo, shortcode_form (Contact Form 7) + recapiti dalle opzioni Footer |
| `mappa` | indirizzo, pulsante |

## Regole SEO dei titoli (importante)

- **Prima sezione di una pagina**: l'**occhiello** (con la parola chiave) è l'**H1** piccolo (`<h1 class="…__eyebrow eyebrow">`) e il titolo grande scende a **H2** (`starter_page_header_titles()`; hero-slider con occhiello). Senza occhiello il titolo è l'H1.
- **Sezioni successive**: con occhiello, l'occhiello è **H2** e il titolo **H3**; senza occhiello il titolo è H2. Usa sempre `starter_section_heading_tags( $has_eyebrow, $livello )` per ottenere i tag; per il blocco "intestazione di sezione" centrato usa `starter_block_head( $titolo, $testo, $occhiello )`.
- **`sezione-affiancata`**: il titolo lo scrive l'utente come blocco Intestazione di Gutenberg, quindi l'occhiello è un semplice `<p class="eyebrow">` e l'H2 resta quello scritto a mano.
- **Una sola H1 per pagina.** Un blocco che può comparire in mezzo alla pagina (hero-video) non usa H1.
- **Occhiello e titolo vicini**: nessuno spazio in mezzo (come nell'intestazione di pagina). Dentro `.section-head__text` la regola è `.eyebrow + h2/h3 { margin-top: -14px }` per annullare il `gap`.

## Creare un nuovo blocco

1. Duplica `template-parts/example-block` → `template-parts/mio-blocco`; in `block.json` cambia `name` (`starter-theme/mio-blocco`), titolo, icona, keywords.
2. In `render.php`: leggi i campi con `get_field()` (o `starter_field()`), esci con `starter_block_placeholder( ! empty( $is_preview ), 'messaggio' )` se mancano i dati, prendi i tag dei titoli da `starter_section_heading_tags()`, ottieni i pulsanti con `starter_link_attrs()` + `starter_flip_text()`.
3. Campi: nuovo `acf_add_local_field_group` in `inc/acf-blocks-extra.php` con `'location' => starter_extra_block_location( 'mio-blocco' )`. Per titolo/testo/link ripetuti usa `starter_extra_head_fields( 'prefisso' )` (include l'occhiello).
4. Mappa chiavi per le pagine di esempio: aggiungi il blocco a `starter_pg_keys()` in `inc/starter-pages.php` (nome campo → `field_…`), altrimenti i dati non vengono scritti nei blocchi generati.
5. CSS in `starter.css` con un prefisso di classe già previsto dai bucket (o aggiungi il prefisso in `tools/build.js`). `npm run build`.
6. Se il blocco è una hero che deve rendere trasparente l'header, aggiorna `starter_body_class_has_hero()` (nel tema: vale solo se il blocco è il primo della pagina).
7. **Controlla l'anteprima nell'editor** (`CSS/gutenberg.css` e variabili passate all'editor): se usa componenti dello shop serve `CSS/shop.css` (già in `add_editor_style`).

## Pulsanti dei blocchi

- Tutti i pulsanti sono `.button`: primario `button` (o `button button--light` nella hero), secondario `button button--outline`, su sfondo colorato `button button--on-accent`. Niente più `pill-button`. L'hover riempie dal basso con `--color-accent-cta` e il testo diventa **nero** (`--color-text`). Il testo del pulsante passa da `starter_flip_text()` (effetto "flip").
- Doppia CTA nelle hero: `link` + `link_2` dentro `.hero-slider__actions` (flex con gap, a capo su mobile). Il secondo compare solo se compilato; su fondo scuro è outline chiaro (`.hero-slider .button--outline`).

## Blocchi core di Gutenberg usati dal generatore

Il generatore scrive anche blocchi core (Gruppo, Intestazione, Paragrafo, Pulsanti, **Copertina con parallasse**). **Il markup salvato deve essere identico a quello che WordPress si aspetta**, altrimenti nell'editor il blocco risulta "contiene contenuto imprevisto o non valido". Per la Copertina con immagine di sfondo in parallasse l'ordine corretto è: `div.wp-block-cover` → `div.wp-block-cover__image-background` (senza `role`) → `span[aria-hidden].wp-block-cover__background` con `has-background-dim-70` (la dimRatio è a passi di 10) → `div.wp-block-cover__inner-container`. Se cambia la versione di WordPress, ricontrolla (vedi "validazione blocchi" in `test-build-deploy.md`).

## Tag HTML di occhiello e titolo (SEO)
Ogni modulo con "Occhiello" e/o "Titolo" ha, subito sotto, i selettori **Tag HTML dell'occhiello** e **Tag HTML del titolo**: Automatico (default), H1–H6, span, p. "Automatico" lascia la gerarchia calcolata dal tema (`starter_section_heading_tags()`), quindi i contenuti esistenti non cambiano. I selettori si aggiungono da soli a tutti i gruppi di campi registrati con `starter_add_block_group()` (vedi `inc/acf-blocks.php`): per un nuovo blocco basta usare quella funzione al posto di `acf_add_local_field_group()` e, nel render, `starter_heading_tag( 'occhiello'|'titolo', $tag_automatico )` o `starter_section_heading_tags()`.

## Contatti
Il blocco ha il suo elenco **Recapiti** (icona + testo; email e numeri diventano link da soli). Se è vuoto usa i recapiti di Opzioni → Footer.

## Testi dei blocchi: formattazione
I testi scritti nei blocchi si stampano con `starter_inline()` (non con `esc_html()`): ammette grassetto, corsivo, sottolineato, a capo e link, niente altro HTML. In un nuovo blocco usare sempre `starter_inline()` per titoli, occhielli, testi e voci. I pulsanti (`starter_flip_text()`) restano testo semplice.
