# File da NON caricare online

Regola: sul sito online vanno solo i file che servono al funzionamento. Sviluppo, strumenti e documentazione restano in locale.
**Questo elenco si aggiorna ogni volta che si aggiunge un file o una cartella di sviluppo** (lo fa Claude; vedi la memoria `project-deploy-exclusions`).

## Tema `starter-theme/`

| Non caricare | Perché |
|---|---|
| `node_modules/` | Dipendenze della build (19 MB). Servono solo per `npm run build`. |
| `package.json`, `package-lock.json` | Configurazione della build. |
| `tools/` | Script di sviluppo: `build.js`, `build-icons.js`, `update-vendor.js`, `smoke-test.js`. |
| `docs/` | Guide, skill e `starter-theme-skill.zip`. **Eccezione: si carica il PDF `docs/GUIDA-FUNZIONI-STARTER-THEME.pdf`** (guida per colleghi e amici), lasciandolo nella cartella `docs/`. |
| `.claude/` | Impostazioni dell'assistente. |
| `.git/`, `.gitignore`, `.github/` | Repository e automazioni di GitHub (quando il tema avrà il suo repository). |
| `.DS_Store` (ovunque) | File di macOS. |
| `*.bak*`, `*.orig`, `*.map`, `*.log` | Copie e file temporanei, se ne compaiono. |
| `images/demo-video-*.mp4` (6,5 MB) | Solo per le pagine di esempio (`inc/starter-pages.php`). Caricarli solo se il sito le usa ancora. |

**Da caricare, anche se sembrano di sviluppo:**
- `CSS/` interi: i file `*.css` sorgente restano come ripiego e `CSS/dist/` è quello servito.
- `JS/dist/` e `JS/vendor/` (compreso `vendor.json`, da cui `functions.php` legge le versioni).
- `inc/icons-data/` (libreria icone, letta a runtime).
- `screenshot.png`, `theme.json`, `languages/`.

## Tema figlio `starter-theme-child/`

Si carica per intero (`style.css`, `functions.php`, e i template che avrà) tranne `README.md`, `.git/`, `.gitignore`, `.github/` e `.DS_Store`: stessa regola del padre. Il figlio è per sito: in un sito nuovo si copia la cartella accanto a `starter-theme/`.

## Plugin

| Plugin | Non caricare |
|---|---|
| `starter-live` | `README.md`, `.git/`, `.gitignore`, `.github/` |
| `starter-security` | `README.md`, `.git/`, `.gitignore`, `.github/` |

## Fuori dal tema

- `wp-content/uploads/starter-demo/` (video e PDF dei prodotti di prova): solo contenuti demo. Il sito vero non li usa.
- `wp-content/uploads/schede-immobili/` (cache dei PDF degli immobili): si ricrea da sola, non copiarla.
- `wp-content/uploads/starter-cache/` (CSS e JS uniti, opzione "Unisci CSS e JS"): si ricrea da solo alla prima visita, non copiarlo.
- `wp-content/uploads/starter-icons/` (favicon.ico, icone 192/512/180 e manifest generati dall'icona del sito): si ricrea da solo entrando in amministrazione; se si copia l'intera cartella uploads viaggia con essa.
- I file `.avif` accanto alle immagini sono parte di uploads: se la cartella uploads non viene copiata (o il server online non li ha) si rifanno da *Media → Ottimizza immagini*. Lo stesso per gli eventuali `.webm` dei video, che si caricano a mano con lo stesso nome dell'mp4.
- Script e prove temporanee non stanno mai in `wp-content`.

## Moduli (CPT) che il sito non usa

Un modulo spento non viene caricato, quindi i suoi file online sono peso inutile. Se un sito **non userà mai** un modulo, i suoi file si possono lasciare fuori.
Il tema non si rompe: il registro `inc/modules.php` controlla che il file del modulo esista prima di caricarlo.

| Modulo | Non caricare se non si usa |
|---|---|
| **Immobili** | `inc/modules/immobili/` (circa 7 MB: contiene la libreria Dompdf del PDF e le foto di esempio), `template-parts/immobili-hero-home/`, `template-parts/immobili-in-evidenza/`, `template-parts/elenco-immobili/`, `template-parts/content/card-immobile.php`, `CSS/immobili.css`, `CSS/dist/immobili.min.css`, `JS/immobili.js`, `JS/dist/immobili.min.js` |
| **Portfolio** | `inc/portfolio.php`, `single-portfolio.php`, `template-parts/portfolio/`, `template-parts/progetti/`, `template-parts/progetto-testo/`, `template-parts/progetto-media/`, `template-parts/progetto-crediti/`, `template-parts/progetto-testata/`, `CSS/dist/portfolio.min.css` |

Se il modulo si usa, tutti questi file vanno caricati. Quando si aggiunge un nuovo modulo, aggiungere qui la sua riga.

Elenco rsync dei moduli (decommentare le righe dei moduli non usati):

```exclude-moduli
# inc/modules/immobili/
# template-parts/immobili-hero-home/
# template-parts/immobili-in-evidenza/
# template-parts/elenco-immobili/
# template-parts/content/card-immobile.php
# CSS/immobili.css
# CSS/dist/immobili.min.css
# JS/immobili.js
# JS/dist/immobili.min.js
# inc/portfolio.php
# single-portfolio.php
# template-parts/portfolio/
# template-parts/progetti/
# template-parts/progetto-testo/
# template-parts/progetto-media/
# template-parts/progetto-crediti/
# template-parts/progetto-testata/
# CSS/dist/portfolio.min.css
```

## Come controllare prima di caricare

```
rsync -avn --include='docs/' --include='docs/GUIDA-FUNZIONI-STARTER-THEME.pdf' --exclude='docs/*' --exclude-from=<(sed -n '/^```exclude$/,/^```$/p' docs/FILE-DA-NON-CARICARE.md | sed '1d;$d') ./ /tmp/anteprima/
```
(le due `--include` vengono prima, così il PDF in `docs/` passa e il resto di `docs/` resta fuori)

Elenco leggibile dalle macchine (formato rsync):

```exclude
node_modules/
package.json
package-lock.json
tools/
docs/
.claude/
.git/
.gitignore
.github/
.DS_Store
*.bak*
*.orig
*.map
*.log
images/demo-video-*.mp4
README.md
```
