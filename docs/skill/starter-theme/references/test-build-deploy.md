# Test, build e messa online

## Build

```bash
npm install        # una volta (esbuild, lightningcss, postcss)
npm run build      # divide CSS/starter.css in bucket, minifica CSS/JS in CSS/dist e JS/dist
```

Da lanciare **dopo ogni modifica** a CSS/JS. `starter_dist_ready()` usa i file in `dist/` solo se più recenti dei sorgenti. **Prima di caricare online si ricompila sempre**: sul server le date dei file sono quelle del caricamento, quindi un `dist` vecchio sembrerebbe "più recente" e verrebbe servito al posto delle novità. Dopo la build ricontrolla un paio di pagine: in locale il `dist` diventa quello attivo.

## Eseguire PHP/WordPress in locale

Con Local by Flywheel `php` e `wp` non sono nel PATH. Ricava: il PHP di Local (cartella `lightning-services`), il socket MySQL del sito (`~/Library/Application Support/Local/run/<id>/mysql/mysqld.sock`, l'id è in `sites.json`) e `wp-cli.phar` dentro l'app di Local.

Metodo semplice per uno script di prova: un file PHP che imposta `$_SERVER['HTTP_HOST']` e `REQUEST_URI`, definisce `WP_USE_THEMES=false`, fa `require wp-load.php` ed esegue il codice, lanciato con `php -d mysqli.default_socket=<socket> script.php`. Utile per `set_theme_mod()`, rigenerare pagine (`starter_pages_create( true )`), creare moduli, leggere opzioni.

Per controllare un file PHP: `php -l file.php`.

## Screenshot per verificare davvero

Chrome headless via DevTools Protocol (Node, senza librerie): apri l'URL, imposta le dimensioni (`Emulation.setDeviceMetricsOverride`), esegui un po' di JS (scroll, aprire il menu, leggere misure con `getBoundingClientRect`/`getComputedStyle`), `Page.captureScreenshot`. Controlla sempre:

- **1280px** e **390px**;
- menu mobile aperto e sottomenu aperto;
- footer in **tutti e 4** gli stili (cambia `starter_footer_style`);
- 404, ricerca, shop, scheda prodotto, carrello;
- `document.documentElement.scrollWidth` = larghezza viewport (niente scroll orizzontale).

Per misurare distanze (es. occhiello → titolo = 0) usa JS e stampa il numero invece di fidarti dell'occhio.

## Controllo dell'editor a blocchi (fondamentale)

I blocchi si rompono nell'editor prima che sul sito. Per vederlo senza aprire il browser:

1. Genera i cookie di login in uno script PHP (`wp_generate_auth_cookie( $user_id, $exp, 'auth' )` e `'logged_in'`) e impostali nella sessione Chrome con `Network.setCookie` (domain del sito, path `/`).
2. Apri `wp-admin/post.php?post=ID&action=edit`; chiudi la guida di benvenuto con `wp.data.dispatch('core/preferences').set('core/edit-post','welcomeGuide',false)`.
3. Il contenuto è in `iframe[name=editor-canvas]`: scorri e fotografa da lì.
4. **Validazione dei blocchi**: con `wp.data.select('core/block-editor').getBlocks()` scorri ricorsivamente e per ogni blocco con `isValid === false` chiama `wp.blocks.validateBlock( block )`: il secondo elemento contiene i messaggi ("Expected tag name…", "Expected attribute…"). Correggi il markup del generatore e ripara i contenuti già salvati.

Cose che l'editor non ha e che il tema deve portare: i CSS di shop (`add_editor_style`), le **variabili delle opzioni** (colori, font, dimensioni, larghezza) con `block_editor_settings_all`, i Google Fonts con `enqueue_block_assets`, il CSS dei pulsanti (div, non a), il nascondere i campi nascosti di CF7.

## Messa online (Hostinger o simili)

**Il tema online deve essere uguale al locale, ma i contenuti stanno nel database**: caricare i file non basta.

1. **File**: carica tutto il tema (escludi `node_modules`, `docs`, `tools`, `package*.json`, `.DS_Store`). Con Hostinger si può usare l'upload TUS (un `curl POST` + `PATCH` per file verso `<url>/<percorso>?override=true` con gli header di autenticazione). Controlla che ogni risposta sia 201/204. Ricarica anche `CSS/dist/*`.
2. **Database** (pagine, menu, opzioni, permalink): non si copia con i file. Opzioni:
   - dal pannello WordPress: Strumenti → Contenuti di prova (rigenera le pagine, **sovrascrive** quelle modificate a mano), Aspetto → Menu, Impostazioni → Permalink;
   - oppure uno **script PHP temporaneo** caricato nella root del sito, protetto da una chiave casuale, eseguito una volta con `curl` e che **si cancella da solo** (`unlink(__FILE__)`). Lo script carica `wp-load.php`, fa `wp_set_current_user(1)` e chiama le funzioni del tema (`starter_pages_create( true )`, `starter_newsletter_form_create()`, `starter_contact_form_create()`), aggiorna menu e opzioni. Prima fai un giro **di sola lettura** (elenco pagine/articoli/menu/opzioni) e confrontalo con il locale; poi agisci. Chiedi conferma prima di cancellare qualcosa che l'utente non ha nominato e dì sempre cosa hai tolto.
3. **Cosa allineare sempre**: permalink WooCommerce (base prodotto e categoria), pagina Shop e link "Shop" nel menu (mai `/shop/` scritto a mano), menu e posizioni, ID del modulo newsletter nello Spazio libero del footer, pagina preferiti e opzione "Mostra preferiti", pagine vecchie non presenti in locale (es. la bozza "Refund and Returns Policy" creata da WooCommerce), `home`/`blog` come pagina iniziale e pagina articoli.
4. **Dopo**: controlla con `curl -s -o /dev/null -w '%{http_code}'` gli indirizzi principali (shop, categorie, contatti, preferiti) e fai uno screenshot di Home, Shop e Contatti online. Verifica che gli script temporanei diano 404.
5. **Permessi dell'agente**: la modifica diretta al database di produzione può essere bloccata dalla modalità automatica di Claude Code; in quel caso o l'utente aggiunge una regola di permesso (es. `/permissions` → Allow → `Bash(curl:*)`) o esce dalla modalità auto. Non aggirare il blocco: spiega e aspetta.

### Procedura provata (zip + script temporanei) e cosa preservare online

1. **Backup prima di tutto**: database (dump via script temporaneo, `gzopen` su file temporaneo, non su `php://output`) e tema online, scaricati in una cartella fuori dal tema.
2. **Pacchetti**: tema (`rsync` con l'elenco di `docs/FILE-DA-NON-CARICARE.md`, **più** `--include` del PDF in `docs/` e `--exclude='*.md'`), plugin Starter, uploads (senza `schede-immobili`, `starter-demo`, `wc-logs`). Controllare che nel tema non siano finiti file di prova (es. `distready.php`).
3. **Upload TUS** degli zip e di uno script che li estrae (chiave casuale nel nome e nel controllo, `?m=fine` lo cancella); il tema nuovo si estrae in una cartella a parte, si controlla che ci siano `style.css`, `functions.php` e i file nuovi, poi si scambia con quello vecchio.
4. **Database**: costruire in locale un SQL (tabelle dei contenuti sostituite per intero, opzioni aggiunte/aggiornate) con sostituzione degli indirizzi e dei percorsi che rispetta i dati serializzati; **provarlo prima** su un database di prova ripristinato dal backup online (adattando le collation); poi eseguirlo online. **Si preservano**: utenti, `siteurl`/`home`, `active_plugins` (online + plugin nuovi), licenza ACF Pro, opzioni di Yoast/CookieYes/Hostinger/Jetpack, cron, ruoli, ordini e sessioni. Dopo: cancellare `rewrite_rules`.
5. **Controlli**: stato/titolo/H1 di ogni pagina, progetto, immobile, prodotto e articolo (locale vs online), immagini raggiungibili, console senza errori, animazioni nel browser, cartella principale senza script o zip. Il CDN risponde spesso 307 verso lo stesso indirizzo prima del 200 (normale); subito dopo l'importazione il sito è lento per qualche minuto.

## Dopo una modifica: checklist veloce

- [ ] `php -l` sui file PHP toccati (o su tutto il tema), `npm run build`, `node tools/smoke-test.js https://sito.local`
- [ ] Transizioni e caricamento (se toccati): prova automatica con Chrome via CDP su tutte le pagine dell'elenco, sui correlati (aprendo il progetto direttamente e arrivandoci da un'animazione), sulle combinazioni del caricamento e a 390px; errori JS/console e richieste 404 per ogni pagina chiave
- [ ] Screenshot 1280 e 390 della pagina toccata (e delle altre che usano lo stesso componente)
- [ ] Editor: blocchi validi, anteprima fedele
- [ ] Footer 1–4 se hai toccato footer o stile globale
- [ ] Nessun `font-size`/colore a mano; descrizione di una riga per ogni nuova opzione
- [ ] Se serve online: file + database allineati, risposte HTTP controllate

## Prestazioni (inc/performance.php) e come verificarle

Il tema comprime e velocizza da solo; le opzioni sono in **Personalizza → Opzioni → Prestazioni** (`starter_perf_htaccess`, `starter_perf_preload_hero`, `starter_perf_cf7_lazy`, `starter_perf_no_migrate`, tutte `true` di base).

- **Immagini**: WebP (qualità 80) per le misure ridotte **e per l'originale caricato** (`wp_handle_upload`); restano nel formato originale favicon, logo email WooCommerce, file con `email`/`nowebp` nel nome (`starter_keep_original_format()`). Le esistenti: *Media → Ottimizza immagini* o `wp starter media avif|webp`. Accanto a ogni file c'è l'`.avif` (`inc/media-modern.php`, qualità `starter_perf_avif_quality`), servito con `<picture>` dai filtri `wp_get_attachment_image`/`the_content`; `.avif` creato da `wp_update_attachment_metadata`, cancellato da `wp_delete_file`. Video: `.webm` con lo stesso nome dell'mp4 (`starter_video_sources()`). Favicon: PNG/ICO generati da `starter_generate_icons()` in `uploads/starter-icons`. Bundle e HTML minify: opzioni `starter_perf_bundle`/`starter_perf_minify_html` (spente di base), prova con `?starter-raw=1`.
- **Script**: `defer` su script del tema, gsap, lenis, animations (filtro `script_loader_tag`); (CSS/immobili.css e JS/immobili.js sono file a sé del modulo Immobili, compilati in dist da `npm run build` e caricati solo dove servono; gsap e lenis sono locali in `JS/vendor/`, aggiornarli con `npm run vendor`, che legge `JS/vendor/vendor.json`); `preconnect` ai servizi esterni davvero usati (`wp_resource_hints`).
- **Contact Form 7**: `wpcf7_load_js/css` solo se la pagina ha `[contact-form-7`, il blocco `contatti` o il footer ha il form (`starter_page_has_form()`).
- **Hero**: `starter_preload_hero_image()` stampa `<link rel="preload" as="image" … fetchpriority="high">` per la prima foto del primo blocco hero (slider, video-copertina, media, split).
- **.htaccess**: `starter_htaccess_rules()` + `starter_apply_htaccess()` scrivono con `insert_with_markers()` il blocco `STARTER_PERFORMANCE` (Brotli/Gzip, tipi file, Expires e Cache-Control di un anno per statici, HTML non in cache). Solo su Apache/LiteSpeed con file scrivibile; si applica a `customize_save_after`, `after_switch_theme` e una volta in `admin_init` (firma in `starter_htaccess_signature`). Su Local (nginx) non fa nulla.
- **Verifica**: `curl -sI -H 'Accept-Encoding: br,gzip' <url>` → `content-encoding`; `curl -sI <css>` → `cache-control: public, max-age=31536000`; nel `<head>`: preload dell'immagine, `preconnect`, `defer` sugli script; PageSpeed Insights su Home e una pagina interna. Controlla sempre che CF7 funzioni ancora nelle pagine con un modulo.
- **Google Fonts in locale** (`inc/fonts-local.php`, opzione `starter_perf_local_fonts`): `starter_local_fonts_download()` scarica da `fonts.googleapis.com` (User-Agent moderno → WOFF2) i font attivi di Font 1/Font 2 (+ Fraunces se `starter_footer_wordmark_font_needed()`), tiene solo `latin` e `latin-ext`, salva in `uploads/starter-fonts`, conserva CSS e file da precaricare nell'opzione `starter_local_fonts`. `starter_local_fonts_ready()` dice se sono pronti per le famiglie attive; in quel caso il link Google viene tolto, il CSS è in linea e c'è il preload. Parte da `customize_save_after` e da `admin_init` (retry orario). Se cambi i font o lo stile del footer, **ricorda di salvare il Personalizza** (o aprire l'admin online) perché i file vengano scaricati; sul sito online serve che il server raggiunga fonts.googleapis.com.
