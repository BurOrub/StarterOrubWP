---
name: starter-theme
description: Lavorare sul tema WordPress classico "Starter Theme" (prefisso starter_, text domain slug-theme): modificare CSS/PHP/JS, creare o cambiare blocchi ACF, opzioni del Customizer, header/footer/blog/shop WooCommerce, pagine di esempio, moduli Contact Form 7, build, test e messa online. Usala ogni volta che si apre un progetto basato su questo tema, quando si nominano Starter Theme, starter.css, template-parts, inc/starter-pages.php, "Opzioni" del tema o blocchi come hero-slider, hero-video, sezione-voci, testo-affiancato, oppure il Portfolio (progetti, transizioni tra pagine, preloader, Altri progetti, Clona).
---

# Starter Theme: come lavorarci

Tema WordPress **classico** (non FSE), leggero, Gutenberg-first, con un pannello **Aspetto → Personalizza → Opzioni** che comanda header, footer, blog, shop, colori, font e dimensioni dei testi senza toccare il codice. È la base riutilizzabile per nuovi siti: tutto ciò che è generico va qui; ciò che è specifico di un cliente va nel progetto del cliente.

Questa skill dice **come è fatto**, **dove si lavora** e **le regole da non rompere**. Per i dettagli leggi i file in `references/` quando servono:

| File | Quando leggerlo |
|---|---|
| `references/architettura.md` | Mappa dei file del tema, funzioni chiave, bucket CSS, variabili CSS |
| `references/opzioni.md` | Struttura del Customizer e come aggiungere un'opzione |
| `references/portfolio.md` | Modulo Portfolio: scheda progetto, correlati, blocco Elenco progetti, animazione logo, transizioni e caricamento, Clona, Starter Live |
| `references/blocchi.md` | Blocchi ACF disponibili, campi, come crearne uno nuovo, regole SEO dei titoli |
| `references/regole-e-css.md` | Regole di stile (font, colori, animazioni, responsive), trappole CSS già incontrate |
| `references/contenuti-shop-form.md` | Pagine di esempio, menu, WooCommerce, Contact Form 7, Flamingo, CookieYes |
| `references/test-build-deploy.md` | Build, test in locale, controllo dell'editor, messa online e sincronizzazione |

## Prima di toccare qualunque cosa

1. **Capisci dove sei.** Individua la cartella del tema (`wp-content/themes/<nome>`), l'URL locale e come lanciare PHP/WP-CLI su quel sito (vedi `references/test-build-deploy.md`). Non assumere percorsi: chiedili o ricavali.
2. **Un tema, una cartella di lavoro.** Se esistono più copie del tema (sorgente + copia attiva), lavora su una sola e dì all'utente quale. Non modificare mai progetti reali di cui il tema è solo "fonte di ispirazione": si leggono e basta.
3. **Leggi prima di scrivere.** Cerca in `CSS/starter.css`, `inc/` e `template-parts/` se esiste già una classe, una funzione o un blocco che fa quello che serve. Il tema ha già helper per quasi tutto (titoli di sezione, pulsanti con animazione "flip", link ACF, icone, breadcrumb).
4. **Ogni cosa nuova rispetta le regole** qui sotto e in `references/regole-e-css.md`.

## Le regole che non si rompono

1. **Font, colori e dimensioni vengono solo dalle opzioni del tema.**
   - Nessun `font-size`, `font-family` o colore esadecimale scritto a mano nei CSS dei componenti. Si usano le variabili `--wp--preset--font-size--{display,title-1..5,eyebrow,x-large,large,medium,small,button}` e le classi `title-h1..6`, `title-display`, `txt`, `eyebrow`, `has-*-font-size`; i colori sono `--color-*` (e `--footer-*`).
   - Le uniche eccezioni sono colori di stato fissi (es. `--shop-ok`, `--shop-error`) e il bianco su overlay scuro dove non esiste una variabile adatta.
2. **Ogni opzione ha una descrizione di una riga** che spiega a cosa serve, scritta per chi non è sviluppatore.
3. **Tutto è allineato e coordinato tra le pagine**: stessa larghezza (`--content-width`, `.starter-container`, `.starter-wide`, `--gutter`), stessi titoli di pagina (`starter_page_header()` / blocco Intestazione pagina), stesse briciole, stessi pulsanti. Se cambi un componente, controlla dove altro compare.
4. **Animazioni sempre morbide**, mai di colpo: transizioni 0.4–0.6 s con easing. Un pulsante che al passaggio del mouse diventa verde fluo (`--color-accent-cta`) ha il **testo nero** (`--color-text`).
5. **Dopo ogni modifica a CSS/JS: `npm run build`.** Il tema usa `CSS/dist` solo se più recente dei sorgenti: in locale funziona da solo, ma **online le date sono quelle del caricamento**, quindi un `dist` vecchio verrebbe servito al posto dei sorgenti. Si compila sempre prima di caricare.
6. **Il sito online deve essere uguale al locale** (tema, pagine, menu, permalink, opzioni). Non basta caricare i file: contenuti e opzioni stanno nel database (vedi sezione "Messa online" in `references/test-build-deploy.md`).
7. **Non scrivere stile in `style.css`** (solo header del tema). Lo stile va in `CSS/starter.css`; solo editor in `CSS/gutenberg.css`; shop in `CSS/shop.css`.

## Come si lavora, in breve

- **CSS nuovo**: in `CSS/starter.css`, vicino a componenti simili. Verifica il prefisso della classe rispetto ai bucket di `tools/build.js` (vedi `references/architettura.md`): una classe globale che comincia con un prefisso di bucket finisce in un foglio non caricato ovunque.
- **Responsive**: breakpoint usati dal tema: 1180px (menu ridotto), 1099/1023px, 899px, 782px (footer a colonna), 639/600px, 480px (una colonna per prodotti e card a icone), 420px. Controlla sempre a 390px (mobile) e 1280px (desktop).
- **Nuovo blocco**: duplica `template-parts/example-block`, `block.json` + `render.php`, campi ACF in `inc/acf-blocks-extra.php` (vedi `references/blocchi.md`). Si registra da solo.
- **Nuova opzione**: registrazione in `inc/customizer-extra.php` (o `functions.php`), default accanto all'uso, descrizione di una riga.
- **Pagine di esempio**: `inc/starter-pages.php` genera Home, Chi siamo, Servizi, Contatti, Credits, Preferiti con i blocchi. Se cambi un blocco, aggiorna anche il generatore e la mappa chiavi `starter_pg_keys()`.
- **Verifica visiva**: screenshot con Chrome headless a 1280px e 390px, anche dell'**editor a blocchi** (lì i blocchi si rompono più facilmente), vedi `references/test-build-deploy.md`.

## Cosa dire all'utente a fine lavoro

Riassunto breve: cosa è cambiato, dove (file), cosa hai verificato davvero (e come) e cosa **no**. Se hai toccato il database o i file online, elenca cosa è stato fatto. Non dichiarare "fatto" su ciò che non hai visto.

## Lingua e tono

Il progetto è in italiano: testi dell'interfaccia, descrizioni delle opzioni e commenti nel codice in italiano. Chi usa il tema è un professionista che vuole risultati precisi, allineati e coerenti: niente soluzioni "approssimative".
