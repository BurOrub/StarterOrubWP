# Starter Theme

Tema WordPress classico (non a blocchi) pensato come base per nuovi siti: leggero, scritto per Gutenberg e ACF, con un pannello **Opzioni** in *Aspetto → Personalizza* che permette di configurare header, footer, colori, font, blog, shop e prestazioni senza toccare il codice.

- Autore: Denis Burani · Licenza: GPL v2 o successiva
- Richiede WordPress 6.4+ e PHP 7.4+

## Cosa include

| Area | Contenuto |
|---|---|
| Opzioni | Pannello *Personalizza → Opzioni*: generale, tipografia, colori, header, footer (5 stili), pulsanti, barra annunci, blog, shop, prestazioni, moduli |
| Blocchi | Blocchi ACF in `template-parts/` (hero, slider, sezioni affiancate, recensioni, team, mappa, blog, prodotti...) |
| Moduli | **Portfolio** e **Immobili**: si accendono da *Opzioni → Moduli*; da spenti non vengono caricati |
| Shop | Template WooCommerce, preferiti e filtri unificati |
| Prestazioni | Immagini AVIF/WebP, font in locale, CSS e JS compilati, unione di CSS/JS e HTML compresso (facoltativi) |
| Aggiornamenti | Notifica di WordPress dalle release di questo repository |

## Plugin del sistema

Il tema funziona con questi plugin. Dopo l'attivazione, *Aspetto → Installa plugin* li scarica e attiva in un colpo solo.

| Plugin | Ruolo |
|---|---|
| **ACF Pro** | Obbligatorio (campi dei blocchi). A pagamento: si scarica con la tua chiave, da definire in `wp-config.php` come `define( 'ACF_PRO_LICENSE', '...' );` |
| **Contact Form 7** | Obbligatorio (moduli) |
| Flamingo, Yoast SEO, CookieYes, LiteSpeed Cache, WooCommerce | Consigliati |
| [Starter Live](https://github.com/BurOrub/StarterLiveWP) | Modifica di testi, immagini e link direttamente dal frontend |
| [Starter Security](https://github.com/BurOrub/StarterSecurityWP) | Protezioni di base (header, login, XML-RPC, limiti alle API) |

## Installazione

1. Scarica `starter-theme.zip` dall'ultima [release](https://github.com/BurOrub/StarterOrubWP/releases/latest).
2. In WordPress: *Aspetto → Temi → Aggiungi → Carica tema*, scegli lo zip e attiva.
3. Aggiungi la chiave ACF in `wp-config.php`, poi vai in *Aspetto → Installa plugin*.
4. Configura il sito da *Aspetto → Personalizza → Opzioni*.

Non usare il pulsante *Code → Download ZIP*: lo zip automatico di GitHub non contiene CSS e JS compilati (il tema funzionerebbe, ma più lento) e ha la cartella con un altro nome.

## Tema figlio

Per ogni sito si consiglia di lavorare in un **tema figlio**, così gli aggiornamenti del padre non toccano le personalizzazioni. Con il figlio attivo le opzioni di *Personalizza → Opzioni* funzionano come prima: alla prima attivazione il figlio copia quelle del padre, poi si modificano da lì. Il tuo CSS va in `style.css` del figlio, che si carica dopo tutti quelli del padre; i template si sostituiscono copiando il file con lo stesso percorso. Dettagli in [`docs/GUIDA-TEMA.md`](docs/GUIDA-TEMA.md).

## Aggiornamenti

Il tema legge l'ultima release di questo repository (`inc/updater.php`): se la versione è più alta di quella installata, WordPress mostra la notifica normale in *Bacheca → Aggiornamenti*. Si aggiorna sempre il padre; il figlio resta com'è. Sulle copie di sviluppo (cartella con `.git`) la notifica non compare, per non sostituire la cartella del repository.

## Sviluppo

```
npm install
npm run build     # compila CSS/dist e JS/dist
npm run smoke     # controlli di base
```

Senza `CSS/dist` e `JS/dist` il tema usa i file sorgente (`CSS/*.css`, `JS/scripts.js`): le modifiche si vedono subito.

### Pubblicare una versione

1. Alza `Version:` in `style.css` (e `STARTER_THEME_VERSION` in `functions.php`).
2. Fai il commit e crea il tag con lo stesso numero: `git tag v1.2.3`.
3. `git push origin main v1.2.3`.

L'automazione in `.github/workflows/release.yml` controlla che tag e `Version:` coincidano, compila CSS e JS, crea `starter-theme.zip` (cartella `starter-theme/`, senza strumenti di sviluppo) e lo allega alla release.

## Documentazione

Nella cartella [`docs/`](docs):

- [`GUIDA-TEMA.md`](docs/GUIDA-TEMA.md): come usare il tema, voce per voce
- [`GUIDA-FUNZIONI-STARTER-THEME.pdf`](docs/GUIDA-FUNZIONI-STARTER-THEME.pdf): guida alle funzioni
- [`COMMENTI-DEL-TEMA.md`](docs/COMMENTI-DEL-TEMA.md) e [`RIPRENDI-DA-QUI.md`](docs/RIPRENDI-DA-QUI.md): note tecniche
- [`FILE-DA-NON-CARICARE.md`](docs/FILE-DA-NON-CARICARE.md): cosa non mettere online
