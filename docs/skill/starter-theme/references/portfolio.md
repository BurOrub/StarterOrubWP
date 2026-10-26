# Portfolio (modulo CPT)

Modulo acceso da **Opzioni → Moduli (CPT) → Portfolio** (`inc/modules.php`; spento = `inc/portfolio.php` non viene caricato). CPT `portfolio` **senza archivio**, tassonomia `portfolio_category` (le categorie sono anche i "Servizi" mostrati nella scheda). L'elenco è il blocco `progetti` messo in una pagina normale. File principali: `inc/portfolio.php` (tutto: CPT, campi ACF, helper, Customizer, demo), `single-portfolio.php`, `template-parts/portfolio/project-head.php`, `template-parts/portfolio/card.php`, `template-parts/progetti/`, `template-parts/progetto-{testo,media,crediti,testata}/`.

## Scheda del progetto

`single-portfolio.php` → `template-parts/portfolio/project-head.php` (stessa testata usata dall'anteprima nell'editor) → `the_content()` (blocchi `progetto-*`) → `starter_portfolio_related()`.

**Testata** (`project-head.php`, argomenti `post_id`, `preview`):
- copertina a tutto schermo in cima se attiva (campo + immagine in evidenza; con video di copertina usa il video);
- **H1 = riassunto** (`post_excerpt`, classe `page-header__summary title-h5`: formattazione H5, maiuscolo, colore primario); **H2 = titolo del progetto** (misura grande, `page-header__title`). Senza riassunto il titolo è l'H1;
- testo editoriale (`progetto_testo`, classe `.project-intro`, `white-space: pre-line`, **mai margin-bottom**: la regola è `.page-header__main .project-intro` perché `.txt` ha `margin-bottom: 1rem` inline);
- colonna dati (`.project-meta`, griglia a 2 colonne, **1 colonna sotto i 640px**): *Servizi* (= categorie del progetto, tab `.project-meta__chips`, riga intera), *Agenzia/Agenzie* e *Partner* affiancati, *Visita il progetto* (link testuale con il **dominio** senza www, stesso stile dei nomi agenzia). Nomi in `li.title-h6.project-meta__name` (misura "small" 15px, 4px tra i nomi, nessun sottolineato).
- **Il nome del cliente è il titolo del progetto** (non esiste più un campo "Cliente"): `starter_portfolio_client_name()`.

**Campi ACF** (gruppo "Progetto", `group_starter_portfolio_details`, due schede):
- *Dettagli del progetto*: riga con logo del cliente · copertina a tutto schermo · link al progetto online · video di copertina; poi *Testo sotto il titolo*; poi ripetitore *Agenzia di comunicazione o partner* (tipo Agenzia/Partner + campo link "nome e link"; le righe senza tipo sono agenzie).
- *Progetti correlati*: campo `progetto_correlati` (max 3). Il resto fino a 3 = gli ultimi per data, mai il progetto stesso, mai doppioni (`starter_portfolio_related()`).
- Nomi meta: `progetto_logo`, `progetto_copertina_full`, `progetto_link`, `progetto_video`, `progetto_testo`, `progetto_agenzie` (sub: `tipo`, `link`), `progetto_correlati`. I vecchi `progetto_cliente`, `progetto_anno`, `progetto_ruoli`, `progetto_output` non sono più letti.

**Blocchi della scheda**: `progetto-testo` (occhiello, titolo, testo, affiancato/centrato), `progetto-media` (1–3 colonne, immagine o video per elemento, proporzione), `progetto-crediti` (ruolo + nome + **link facoltativo** `voci_link`: il nome diventa un link al sito del collaboratore), `progetto-testata` (solo anteprima nell'editor, vedi sotto).

**Anteprima nell'editor**: `rest_prepare_portfolio` antepone al contenuto (contesto `edit`) il blocco bloccato `starter-theme/progetto-testata`; `wp_insert_post_data` lo toglie al salvataggio, quindi il database resta pulito e il blocco non stampa nulla sul sito. Stili: `CSS/gutenberg.css` (copertina a tutto schermo alta 70vh, spazio sotto la copertina come sul sito, titolo di pagina largo come il contenuto con margine 4rem, bordo sotto, font ridotto).

## Altri progetti (fondo scheda)

Opzioni in **Personalizza → Opzioni → Portfolio** con prefisso `starter_portfolio_rel_*` (stessi nomi e valori dei campi del blocco, tranne il layout, sempre 3 colonne): categorie, riassunto/cliente (tag, dimensione, colori, hover, posizione), proporzione immagini (16:9, 3:2, 4:3, 1:1, 4:5), animazione logo, logo colore sfondo, video all'hover. Predefiniti: categorie tab sopra, riassunto H3, cliente H4, 4:3. Il riassunto non sta mai sopra l'immagine in questa sezione.

**Un'unica funzione legge le opzioni delle card**: `starter_portfolio_card_config( $get )` (`$get` = `'get_field'` per il blocco, `'starter_portfolio_related_get'` per i correlati) restituisce `cats`, `cats_opts`, `titles`, `video`, `reveal`, `style`. Una nuova opzione delle card va aggiunta lì, nel campo ACF del blocco e nel Customizer (`starter_portfolio_related_defaults()`).

## Blocco "Elenco progetti" (`progetti`)

Schede: **Layout** (visualizzazione *classica* / *masonry* / *a blocchi* / *sfalsata*, colonne 2–5 con tablet e mobile, distanza orizzontale/verticale in px, proporzione, quarto elemento, per pagina, video all'hover), **Titolo** (animazione logo, ordine riassunto/cliente, tag HTML, dimensione, posizione, colori, hover), **Categorie** (testo con simbolo / tab sotto / tab sopra / nessuna, forma, piene/vuote, simbolo e colore, posizione, margini `mt*`/`mb*`, maiuscolo), **Filtri** (stili *linea* [Classica], *testo grande*, *pillole*, *frase*; colori; conteggi; stessa pagina con fetch-and-swap `initProjectFilters()` oppure ricarica), **Animazioni** (passaggio verso il progetto). Griglie: `starter_portfolio_grid_html()`. Il numero progressivo sulle card non esiste più.

## Animazione logo sulle card

`starter_portfolio_reveal_html()`: al centro dell'immagine il logo del cliente; al passaggio del mouse sparisce e il **titolo del progetto scorre da destra a sinistra (senso di lettura), sempre in MAIUSCOLO** (nessuna opzione per il minuscolo). Il logo è un `<span>` con maschera CSS nel colore di sfondo del sito (o un colore scelto); **funziona anche con gli SVG**: se la Libreria media non ha le dimensioni, `starter_portfolio_logo_ratio()` le legge dal file (width/height o viewBox) e le salva in `_starter_logo_ratio`. Se il logo non è tinto è un `<img class="project-card__logo">`: la regola `.project-card__media .project-card__reveal img.project-card__logo` lo protegge da `.project-card__media img` (riempire, ritagliare, ingrandire, posizionarsi) così si vede sempre intero. Marquee: `@keyframes project-marquee` da `translateX(0)` a `-50%`.

## Passaggi tra pagine (transizione) e caricamento

Un velo disegnato su `<html>` (`html[class*="pt-"]::before/::after`, in `CSS/starter.css`), guidato da `initProjectTransition()` in `JS/scripts.js`. Classi: `pt-cover | pt-tendina | pt-dissolvenza` (tipo), `pt-counter` (percentuale), `pt-go` (velo acceso), `pt-in` (pagina di arrivo), `pt-land` / `pt-leave` / `pt-fade` (uscita). Lo stato passa tra le pagine in `sessionStorage` (`starterPt`, valido 10 s); gli script nell'`<head>` (`starter-project-transition-in` priorità 1, `starter-preloader-in` priorità 2) accendono il velo prima del primo disegno. Configurazione: dall'elenco = campi del blocco; dalla scheda ai correlati = `starter_portfolio_single_*` (`starter_portfolio_transition_config()`); `<meta name="starter-project-transition">`.
- **Copertina**: l'immagine della card cresce a tutto schermo e atterra sulla copertina vera (misura l'`img`/`video`, non il contenitore). Con un **video** di copertina il velo non nasconde il video: aspetta un fotogramma pronto (max 2 s), lo fa partire sotto e svanisce in dissolvenza (`pt-fade`) — niente scatto alla partenza.
- **Video all'hover**: al clic su una card il video (anche 4 MB) va fermato e liberato (`release()` in `initProjectVideos()`: toglie le sorgenti, `load()`, e le rimette con `pageshow` se si torna indietro). Altrimenti, su un sito online dietro CDN, il download tiene occupata la connessione e la pagina del progetto arriva dopo 30 secondi o più (in locale non si vede). Pesare i video all'hover: meglio sotto 1 MB.
- **Regola importante**: nella pagina di arrivo (`pt-in`) il codice NON deve fare `return` prima di registrare il clic sulle card: altrimenti le card "Altri progetti" di quella pagina non animano (bug trovato e risolto). Il tipo di arrivo si legge dal velo (`landMode`), il tipo del clic dalla `meta` della pagina (`mode`).
- **Caricamento delle pagine** (Opzioni → Generale → Animazioni): `starter_preloader_mode()` = `none | dissolvenza | tendina`, contenuto testo / percentuale / nome del sito / logo, colori sfondo e testo. Stesso velo e stesse classi. Il vecchio "logo a tutto schermo" con GSAP non esiste più (`JS/vendor/gsap.min.js` resta nel tema ma non viene caricato).

## Starter Live (plugin) sulla scheda

`starter_live_acf_post_types` include `portfolio`; la testata passa da `apply_filters( 'starter_live_post_html', … )` in `single-portfolio.php`. Modificabili dal frontend: titolo, riassunto (`post_excerpt`), testo sotto il titolo (marcatore `acfnum`), link e nomi delle agenzie, link al progetto, e i blocchi `progetto-*` (compresi i crediti). Non si aggiungono/tolgono righe del ripetitore e non si cambia il Tipo Agenzia/Partner (si usa l'amministrazione).

## Clona (`inc/duplicate.php`)

Voce **Clona** nelle azioni di riga (dopo Visualizza) di pagine, articoli e CPT: crea una bozza identica (titolo, contenuto, estratto, campi ACF, immagine in evidenza, categorie, genitore), senza slug. Esclusi allegati e prodotti WooCommerce. Nonce + `edit_post` + `create_posts`. Filtro `starter_clone_post_types`.

## Menu e pagine di prova

Menu principale: voce **Portfolio** (link `#`, non cliccabile) con sottovoci Classica (la pagina Portfolio), Masonry, A blocchi, Sfalsata, ciascuna con più pagine figlie che combinano layout, filtri e transizioni diverse (le pagine figlie hanno slug `portfolio/<nome>`; il filtro `request` gestisce pagina `/portfolio/` e figli insieme al CPT). Contenuti di prova: `starter_portfolio_demo()` (Strumenti → Contenuti di prova): 9 progetti con titolo = nome del cliente, riassunto, testo sotto il titolo e categorie.

## Come si verifica (fatto prima di ogni messa online)

Chrome headless via CDP: per ogni pagina dell'elenco clic su una card e misura velo (≈100 ms), navigazione, rimozione del velo (2,5–3,5 s), `sessionStorage` pulito, nessun errore JS; poi i correlati aprendo il progetto direttamente **e** arrivandoci da un'animazione; poi le combinazioni del caricamento (dissolvenza/tendina × testo/percentuale/nome/logo); poi telefono (390 px). Per cambiare opzioni nei test usare `set_theme_mod()` da uno script PHP e **ripristinare** i valori originali.
