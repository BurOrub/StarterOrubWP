# Starter Theme

**English** · [Italiano](#italiano)

Classic (non-block) WordPress theme built as a base for new sites: lightweight, written for Gutenberg and ACF, with an **Options** panel in *Appearance → Customize* to set up header, footer, colors, fonts, blog, shop and performance without touching code.

- Author: Denis Burani · License: GPL v2 or later
- Requires WordPress 6.4+ and PHP 7.4+
- The interface and the documentation in `docs/` are in Italian.

## What's included

| Area | Content |
|---|---|
| Options | *Customize → Options* panel: general, typography, colors, header, footer (5 styles), buttons, announcement bar, blog, shop, performance, modules |
| Blocks | ACF blocks in `template-parts/` (hero, slider, side-by-side sections, reviews, team, map, blog, products...) |
| Modules | **Portfolio** and **Real estate** ("Immobili"): switched on from *Options → Moduli*; when off they are not loaded |
| Shop | WooCommerce templates, wishlist and shared filters |
| Performance | AVIF/WebP images, local fonts, compiled CSS and JS, optional CSS/JS merging and HTML minification |
| Updates | Standard WordPress update notice, fed by the releases of this repository |

## System plugins

The theme works with the plugins below. After activating the theme, *Appearance → Installa plugin* downloads and activates them all in one go.

| Plugin | Role |
|---|---|
| **ACF Pro** | Required (block fields). Paid: it is downloaded with your license key, defined in `wp-config.php` as `define( 'ACF_PRO_LICENSE', '...' );` |
| **Contact Form 7** | Required (forms) |
| Flamingo, Yoast SEO, CookieYes, LiteSpeed Cache, WooCommerce | Recommended |
| [Starter Live](https://github.com/BurOrub/StarterLiveWP) | Edit texts, images and links straight from the frontend |
| [Starter Security](https://github.com/BurOrub/StarterSecurityWP) | Basic hardening (headers, login, XML-RPC, API rate limits) |

## Installation

1. From the latest [release](https://github.com/BurOrub/StarterThemeWP/releases/latest) download:
   - `starter-pacchetto-completo.zip`: theme + child theme. WordPress's *Upload Theme* accepts only one theme per zip, so this one is unzipped into `wp-content/themes/` (File Manager or FTP);
   - or, to upload from WordPress, `starter-theme.zip` and [`starter-theme-child.zip`](https://github.com/BurOrub/StarterChildWP/releases/latest) separately, with *Appearance → Themes → Add New → Upload Theme*.
2. In *Appearance → Themes* **activate the child theme** (the parent stays installed).
3. Add the ACF license key to `wp-config.php`, then go to *Appearance → Installa plugin*.
4. Configure the site in *Appearance → Customize → Options*.

Do not use the *Code → Download ZIP* button: GitHub's automatic zip has no compiled CSS/JS (the theme would still work, only slower) and the folder has a different name.

## Child theme

Working in a **child theme** ([StarterChildWP](https://github.com/BurOrub/StarterChildWP)) is recommended for every site, so parent updates never touch your customizations. With the child active, *Customize → Options* works as before: on first activation the child copies the parent's options, then you edit them from there. Your CSS goes in the child's `style.css`, which loads after all the parent's styles; templates are replaced by copying the file with the same path.
## Updates

The theme reads the latest release of this repository (`inc/updater.php`): when its version is higher than the installed one, WordPress shows the usual notice in *Dashboard → Updates*. Only the parent is updated; the child is left as it is. On development copies (a folder containing `.git`) the notice is not shown, so the repository folder is never replaced. The two plugins update the same way from their own repositories.

## Development

```
npm install
npm run build     # compiles CSS/dist and JS/dist
npm run smoke     # basic checks
```

Without `CSS/dist` and `JS/dist` the theme uses the source files (`CSS/*.css`, `JS/scripts.js`), so changes show up immediately.

### Publishing a version

1. Raise `Version:` in `style.css` (and `STARTER_THEME_VERSION` in `functions.php`).
2. Commit and create the tag with the same number: `git tag v1.2.3`.
3. `git push origin main v1.2.3`.

The workflow in `.github/workflows/release.yml` checks that tag and `Version:` match, compiles CSS and JS, builds `starter-theme.zip` (folder `starter-theme/`, without development tools) and attaches it to the release, together with `starter-pacchetto-completo.zip` (theme + latest child theme).

## Documentation

In the [`docs/`](docs) folder (Italian):

- [`GUIDA-TEMA.md`](docs/GUIDA-TEMA.md): how to use the theme, option by option
- [`documentation.pdf`](docs/documentation.pdf): feature guide, also included in the theme zip
---

## Italiano

Tema WordPress classico (non a blocchi) pensato come base per nuovi siti: leggero, scritto per Gutenberg e ACF, con un pannello **Opzioni** in *Aspetto → Personalizza* che permette di configurare header, footer, colori, font, blog, shop e prestazioni senza toccare il codice.

- Autore: Denis Burani · Licenza: GPL v2 o successiva
- Richiede WordPress 6.4+ e PHP 7.4+

### Cosa include

| Area | Contenuto |
|---|---|
| Opzioni | Pannello *Personalizza → Opzioni*: generale, tipografia, colori, header, footer (5 stili), pulsanti, barra annunci, blog, shop, prestazioni, moduli |
| Blocchi | Blocchi ACF in `template-parts/` (hero, slider, sezioni affiancate, recensioni, team, mappa, blog, prodotti...) |
| Moduli | **Portfolio** e **Immobili**: si accendono da *Opzioni → Moduli*; da spenti non vengono caricati |
| Shop | Template WooCommerce, preferiti e filtri unificati |
| Prestazioni | Immagini AVIF/WebP, font in locale, CSS e JS compilati, unione di CSS/JS e HTML compresso (facoltativi) |
| Aggiornamenti | Notifica di WordPress dalle release di questo repository |

### Plugin del sistema

Il tema funziona con questi plugin. Dopo l'attivazione, *Aspetto → Installa plugin* li scarica e attiva in un colpo solo.

| Plugin | Ruolo |
|---|---|
| **ACF Pro** | Obbligatorio (campi dei blocchi). A pagamento: si scarica con la tua chiave, da definire in `wp-config.php` come `define( 'ACF_PRO_LICENSE', '...' );` |
| **Contact Form 7** | Obbligatorio (moduli) |
| Flamingo, Yoast SEO, CookieYes, LiteSpeed Cache, WooCommerce | Consigliati |
| [Starter Live](https://github.com/BurOrub/StarterLiveWP) | Modifica di testi, immagini e link direttamente dal frontend |
| [Starter Security](https://github.com/BurOrub/StarterSecurityWP) | Protezioni di base (header, login, XML-RPC, limiti alle API) |

### Installazione

1. Dall'ultima [release](https://github.com/BurOrub/StarterThemeWP/releases/latest) scarica:
   - `starter-pacchetto-completo.zip`: tema + tema figlio. WordPress da *Carica tema* accetta un solo tema per zip, quindi questo si decomprime in `wp-content/themes/` (File Manager o FTP);
   - oppure, per caricare da WordPress, `starter-theme.zip` e [`starter-theme-child.zip`](https://github.com/BurOrub/StarterChildWP/releases/latest) separati, con *Aspetto → Temi → Aggiungi → Carica tema*.
2. In *Aspetto → Temi* **attiva il tema figlio** (il padre resta installato).
3. Aggiungi la chiave ACF in `wp-config.php`, poi vai in *Aspetto → Installa plugin*.
4. Configura il sito da *Aspetto → Personalizza → Opzioni*.

Non usare il pulsante *Code → Download ZIP*: lo zip automatico di GitHub non contiene CSS e JS compilati (il tema funzionerebbe, ma più lento) e ha la cartella con un altro nome.

### Tema figlio

Per ogni sito si consiglia di lavorare in un **tema figlio** ([StarterChildWP](https://github.com/BurOrub/StarterChildWP)), così gli aggiornamenti del padre non toccano le personalizzazioni. Con il figlio attivo le opzioni di *Personalizza → Opzioni* funzionano come prima: alla prima attivazione il figlio copia quelle del padre, poi si modificano da lì. Il tuo CSS va in `style.css` del figlio, che si carica dopo tutti quelli del padre; i template si sostituiscono copiando il file con lo stesso percorso.
### Aggiornamenti

Il tema legge l'ultima release di questo repository (`inc/updater.php`): se la versione è più alta di quella installata, WordPress mostra la notifica normale in *Bacheca → Aggiornamenti*. Si aggiorna sempre il padre; il figlio resta com'è. Sulle copie di sviluppo (cartella con `.git`) la notifica non compare, per non sostituire la cartella del repository. I due plugin si aggiornano allo stesso modo dai loro repository.

### Sviluppo

```
npm install
npm run build     # compila CSS/dist e JS/dist
npm run smoke     # controlli di base
```

Senza `CSS/dist` e `JS/dist` il tema usa i file sorgente (`CSS/*.css`, `JS/scripts.js`): le modifiche si vedono subito.

#### Pubblicare una versione

1. Alza `Version:` in `style.css` (e `STARTER_THEME_VERSION` in `functions.php`).
2. Fai il commit e crea il tag con lo stesso numero: `git tag v1.2.3`.
3. `git push origin main v1.2.3`.

L'automazione in `.github/workflows/release.yml` controlla che tag e `Version:` coincidano, compila CSS e JS, crea `starter-theme.zip` (cartella `starter-theme/`, senza strumenti di sviluppo) e lo allega alla release, insieme a `starter-pacchetto-completo.zip` (tema + ultimo tema figlio).

### Documentazione

Nella cartella [`docs/`](docs):

- [`GUIDA-TEMA.md`](docs/GUIDA-TEMA.md): come usare il tema, voce per voce
- [`documentation.pdf`](docs/documentation.pdf): guida alle funzioni, compresa anche nello zip del tema