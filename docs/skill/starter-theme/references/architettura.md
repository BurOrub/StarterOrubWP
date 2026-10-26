# Architettura del tema

## Mappa dei file

```
starter-theme/
├── style.css                 SOLO header del tema (nome, versione). Mai stile qui.
├── functions.php             Setup, Customizer base (tipografia, colori, header, footer), enqueue, buckets CSS, body class, editor
├── header.php / footer.php   footer.php è un loader: legge starter_footer_style e carica template-parts/footer/footer-style-N.php
├── index.php, home.php (pagina blog), archive.php, search.php, single.php, page.php, 404.php, sidebar-blog.php
├── inc/modules.php           Moduli (CPT): registro starter_modules(), interruttori in Personalizza, pagina del modulo, caricamento solo se acceso
├── inc/modules/immobili/     Modulo Immobili: CPT, tassonomie, helper, filtri, PDF (dompdf/), acf-json/, templates/single-immobile.php, sample/
├── single-portfolio.php      Scheda progetto (inc/portfolio.php; testata in template-parts/portfolio/project-head.php; card in template-parts/portfolio/card.php; elenco = blocco template-parts/progetti, nessun archivio). Vedi references/portfolio.md
├── theme.json                Palette e scala testi per l'editor (clamp, compreso XXL), sincronizzata dalle opzioni
├── CSS/
│   ├── starter.css           TUTTO lo stile del sito (qui si lavora)
│   ├── shop.css              Bucket shop (WooCommerce) – caricato solo dove serve
│   ├── gutenberg.css         Solo editor a blocchi
│   ├── preset-starter.css    Preset base (entra nel bucket "base")
│   └── dist/                 Output di npm run build (base, blog, blocks, portfolio, shop, filters, immobili + manifest.json)
├── JS/                       scripts.js (frontend: filtri, slider, transizioni tra progetti, caricamento delle pagine), animations.js (solo Lenis, se attivo), customize-controls.js (solo admin)
├── inc/
│   ├── acf-blocks.php        Campi ACF dei blocchi "base"
│   ├── acf-blocks-extra.php  Campi ACF di hero-slider/video, striscia valori, ultimi articoli, prodotti, instagram, team, contatti…
│   ├── acf-options.php       Opzioni ACF (poche)
│   ├── block-helpers.php     Helper dei blocchi: starter_field, starter_link_attrs, starter_flip_text, starter_section_heading_tags,
│   │                         starter_block_head, starter_page_header (+ _titles), starter_block_center_link, starter_page_breadcrumb…
│   ├── icons.php             Icone SVG: starter_icon() legge le icone del tema + la libreria completa; endpoint REST starter/v1/icons (selettori)
│   ├── icons-data/           Libreria generata da tools/build-icons.js: ~1.900 icone Lucide (ISC) a blocchi per lettera + 90 loghi (Simple Icons CC0 + LinkedIn, Amazon, Slack, Skype da Bootstrap Icons MIT; "brand-nome") + index.json (ricerca IT/EN)
│   ├── acf-field-icon.php    Tipo di campo ACF "Icona (libreria Starter)": finestra con ricerca (JS/icon-picker.js, CSS/icon-picker.css). Valore = nome icona
│   ├── header.php            Opzioni e markup condiviso dell'header (stile icone, ordine, logo allo scroll, WPML)
│   ├── search.php            Ricerca nell'header (REST /wp-json/starter/v1/search + pannello)
│   ├── menu.php              Pulsanti dei sottomenu, titoli colonne footer
│   ├── blog.php              Opzioni blog, meta, aside, autore, correlati
│   ├── breadcrumbs.php       Briciole (Yoast se presente), stile inline o barra
│   ├── customizer-extra.php  Opzioni Header/Generale/Shop/Blog/Footer 4
│   ├── cf7.php               Contact Form 7: honeypot + campi nascosti pagina/pagina-url per Flamingo
│   ├── cookieyes.php         CookieYes in italiano
│   ├── duplicate.php         Voce "Clona" negli elenchi di pagine, articoli e CPT (bozza identica, senza plugin)
│   ├── demo-content.php      Strumenti → Contenuti di prova
│   ├── starter-pages.php     Generatore delle pagine standard + moduli CF7 (Contatti, Newsletter)
│   ├── performance.php       WebP, preload font, pulizia head, .htaccess
│   ├── media-modern.php      AVIF + <picture>, video WebM (<source>), favicon .ico/icone/manifest
│   ├── media-optimizer.php   Media → Ottimizza immagini (AVIF mancanti, JPG/PNG → WebP), WP-CLI `starter media`
│   ├── assets-optimizer.php  Unione CSS/JS consecutivi in uploads/starter-cache + minificazione HTML (ob_start su template_redirect)
│   ├── woocommerce.php, wishlist.php, shop-filters.php, shop-setup.php   Shop
├── template-parts/           Un blocco ACF per cartella (block.json + render.php), più content/, blog/, footer/
└── woocommerce/              archive-product.php, content-product.php (card)
```

## Funzioni chiave

- `starter_theme_register_acf_blocks()` registra ogni `template-parts/*/block.json`.
- `starter_css_buckets()` decide quali bucket CSS caricare per la pagina; `starter_dist_ready()` usa `CSS/dist` solo se più recente dei sorgenti.
- `starter_colors_css()`, `starter_typography_css()`, `starter_typography_style_css()` generano il CSS delle opzioni Colori, Tipografia e Stile testi (inline sul foglio del tema). Lo stesso CSS è passato **anche all'editor** con il filtro `block_editor_settings_all`.
- `starter_body_class_has_hero()` aggiunge `has-hero` (header trasparente sopra la hero) se la pagina inizia con una hero.
- `starter_footer_style_choices()`: per aggiungere un footer basta una voce e un file `footer-style-N.php`.
- `starter_has_woocommerce()`, `starter_needs_shop_css()`.

## Bucket CSS (`tools/build.js`)

`npm run build` divide `starter.css` per prefisso di classe:

- **blog**: `blog-`, `sidebar`, `widget`, `tagcloud`, `trust-row`, `contact-form`
- **blocks**: `content-block`, `block-wrap`, `panel`, `section-head`, `eyebrow`, `text-balance`, `pill-button`, `round-button`, `icon-circle`, `breadcrumb`, `split`, `checklist`, `list-block`, `reviews`, `map-block`, `page-header`, `mission-vision`, `block-placeholder`, `hero-slider`, `hero-video`, `hero-split`, `hero-media`, `values-strip`, `latest-posts`, `featured-products`, `insta-grid`, `team-grid`, `contacts-block`, `section-foot`
- **portfolio** (`project-`), **filters**, **immobili** (file propri)
- **shop** (`CSS/shop.css`)
- tutto il resto: **base** (sempre caricato)

Regola: una regola con selettori di bucket diversi finisce in **base**. Se aggiungi un componente nuovo con un prefisso nuovo, aggiungilo a `rules` in `tools/build.js` **e** assicurati che `starter_css_buckets()` lo carichi nelle pagine che lo usano (es. la 404 carica il bucket blocks).

## Variabili CSS principali (`:root` in `CSS/starter.css`)

`--content-width` (1280), `--wide-width`, `--gutter` (clamp 12–24px), `--spacing-unit` (1.5rem), `--spacing-section`, `--header-height`, `--header-button-size` (54px), `--radius-button`, `--radius-md/-lg`, `--transition-base`; colori `--color-background/text/primary/primary-text(-hover)/secondary(-text)/accent-cta/accent-menu/accent-social/menu(-text)/link/border/line/surface-muted/placeholder`; footer `--footer-bg/-text/-muted/-border/-link-hover/-form-*`; shop `--shop-muted/soft/ok/error`.

I valori reali dipendono dalle opzioni del sito (inline), non dai default del file.

## Icone

- Nei render si usa sempre `starter_icon( 'nome', $size, $stroke )`. Le icone di `starter_icon_paths()` (poche, personalizzate) hanno la precedenza su quelle della libreria, così l'aspetto di quelle già usate non cambia.
- Un campo ACF con scelta dell'icona si definisce con `'type' => 'starter_icon'` (opzione `allow_null` per "nessuna icona"). Il vecchio `select` + `starter_icon_choices()` non serve più.
- I loghi (`brand-instagram`, `brand-facebook`…) sono pieni (`fill`), le altre icone a linea. I loghi arrivano da più librerie: per aggiungerne altri a `brandList` (Simple Icons) o a `fromBootstrap` (Bootstrap Icons) in `tools/build-icons.js`.
- Aggiornare o ampliare la libreria: `npm i -D lucide-static simple-icons`, poi `node tools/build-icons.js` (per aggiungere loghi: elenco `brandList` nello script).

## Intestazione di Blog e Shop

`starter_page_header( array( 'live' => array( 'post' => ID, 'title' => true ) ) )` rende occhiello (meta `starter_eyebrow` della pagina) e titolo modificabili dal frontend con il plugin Starter Live tramite `starter_live_attrs()`; senza plugin non cambia nulla. Il testo introduttivo è il contenuto della pagina Blog / Shop (blocchi).
