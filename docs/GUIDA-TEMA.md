# Starter Theme · Documentazione

*Versione del tema 1.0.4 · aggiornata al 28 ottobre 2026.* Questo file e `documentation.pdf` nascono dagli stessi testi e vanno di pari passo.


# Benvenuto

## Che cos'è Starter Theme

**Starter Theme** è un tema WordPress pensato come **punto di partenza per costruire siti nuovi** senza ripartire ogni volta da zero. È un tema "classico" (non a blocchi), leggero e veloce, che unisce due cose:

- **un pannello Opzioni** dentro *Personalizza*, con cui si decidono header, footer, colori, font, pulsanti, blog, negozio e prestazioni senza toccare il codice;
- **una raccolta di blocchi pronti** (hero, slider, sezioni affiancate, recensioni, team, mappa, elenchi…) con cui si compongono le pagine dall'editor di WordPress, senza page builder.

In più porta con sé due **moduli facoltativi**, Portfolio e Immobili, che si accendono solo se servono, e funziona insieme a due plugin dello stesso sistema: uno per **modificare i contenuti direttamente dal sito** e uno per la **sicurezza di base**.

## A cosa serve

Starter Theme va bene per:

- **siti vetrina** di aziende, studi e professionisti;
- **portfolio** di creativi, agenzie e fotografi;
- **siti immobiliari**, con schede, filtri e scheda in PDF;
- **blog** con elenco, archivi e ricerca curati;
- **negozi WooCommerce** con filtri, preferiti e cassa pronta per la fattura.

È pensato per chi costruisce **più siti con la stessa base**: si imposta tutto una volta, e ogni sito nuovo parte già in ordine. Gli aggiornamenti arrivano da soli, e le personalizzazioni di ogni sito restano al sicuro in un tema figlio.

## Com'è fatto il sistema

Il sistema ha quattro pezzi, che trovi ognuno con il suo repository su GitHub:

| Pezzo | A cosa serve |
|---|---|
| **Starter Theme** (tema padre) | Il tema vero e proprio: opzioni, blocchi, moduli. Si aggiorna da solo. |
| **Starter Theme Child** (tema figlio) | Il tema che si attiva sul sito. Contiene le personalizzazioni di quel sito. |
| **Starter Live** (plugin) | Modifica testi, immagini e link direttamente dal sito. |
| **Starter Security** (plugin) | Protezioni di base: header di sicurezza, login, XML-RPC, limiti alle API. |

C'è anche una **skill per Claude** (facoltativa) che spiega all'assistente com'è fatto il tema, utile a chi sviluppa con il suo aiuto.

## Come usare questa guida

La guida è pensata per chi conosce WordPress a livello **base o medio**: niente codice, solo "dove clicco per cambiare questa cosa". È divisa in quattro capitoli, da leggere nell'ordine la prima volta e da consultare a piacere dopo.

| Capitolo | Che cosa trovi |
|---|---|
| **1 · Installazione e primi passi** | Scaricare e installare tema, tema figlio e plugin; i primi passi su un sito nuovo; aggiornamenti; la skill. |
| **2 · Le Opzioni** | Il cuore del tema: tutte le sezioni del pannello Opzioni, una per una. |
| **3 · I moduli (CPT)** | Portfolio, Immobili e i filtri degli elenchi: come si accendono e come si usano. |
| **4 · I blocchi personalizzati** | I blocchi disponibili nell'editor, a cosa servono e come si impostano. |

**Come leggere i percorsi.** Le indicazioni su dove cliccare sono scritte così: **Aspetto → Personalizza → Opzioni → Generale**. Si legge: nel menu di WordPress a sinistra *Aspetto*, poi *Personalizza*, poi il pannello *Opzioni* e la sezione *Generale*.

**I riquadri colorati** segnalano le cose da non perdere:

> **Nota.** Un'informazione utile da sapere.

> **Consiglio.** Un modo più comodo o più sicuro di fare una cosa.

> **Attenzione.** Qualcosa che può cambiare il sito in modo difficile da annullare, o una trappola già incontrata.

**Contenuti e opzioni** non viaggiano con i file del tema: stanno nel database di ogni sito. Quando metti online un sito costruito in locale, vanno allineati anche quelli.

# 1 · Installazione e primi passi

*Dal download al primo sito funzionante: tema, tema figlio, plugin, primi passi e aggiornamenti.*

## Cosa serve

- **WordPress 6.4 o successivo** e **PHP 7.4 o successivo** (meglio una versione recente di PHP 8).
- Un **sito WordPress vuoto** su cui lavorare: in locale (per esempio con Local) o su un hosting. Il tema è pensato anche per hosting LiteSpeed come Hostinger.
- Nessuna licenza a pagamento: i blocchi e i campi sono nel plugin **Starter Blocks**, incluso nel sistema.
- Una ventina di minuti.

## 1.1 Scaricare il sistema

Tutto si scarica dalle pagine **Releases** dei repository su GitHub. Prendi sempre l'**ultima release**.

| Che cosa | Dove | File da scaricare |
|---|---|---|
| Tema + tema figlio | github.com/BurOrub/StarterThemeWP/releases/latest | `starter-pacchetto-completo.zip`, oppure `starter-theme.zip` |
| Tema figlio da solo | github.com/BurOrub/StarterChildWP/releases/latest | `starter-theme-child.zip` |
| Starter Blocks | github.com/BurOrub/StarterBlocksWP/releases/latest | `starter-blocks.zip` (di solito non serve: lo installa il tema) |
| Starter Live | github.com/BurOrub/StarterLiveWP/releases/latest | `starter-live.zip` (di solito non serve: lo installa il tema) |
| Starter Security | github.com/BurOrub/StarterSecurityWP/releases/latest | `starter-security.zip` (idem) |
| Skill per Claude | github.com/BurOrub/StarterSkillWP/releases/latest | `starter-theme-skill.zip` |

> **Attenzione.** Non usare il pulsante verde *Code → Download ZIP* di GitHub. Quello zip non contiene il CSS e il JavaScript compilati (il tema funzionerebbe, ma più lento) e la cartella ha un nome diverso da quello giusto.

## 1.2 Installare tema e tema figlio

Sul sito si attiva il **tema figlio**; il padre resta installato accanto e fa il lavoro. Ci sono due modi per installarli.

### Modo A · Due zip da WordPress

1. **Aspetto → Temi → Aggiungi → Carica tema**: carica `starter-theme.zip`. **Non attivarlo.**
2. Di nuovo **Aspetto → Temi → Aggiungi → Carica tema**: carica `starter-theme-child.zip`.
3. Attiva **Starter Theme Child**.

### Modo B · Pacchetto completo

1. Decomprimi `starter-pacchetto-completo.zip`: dentro ci sono due cartelle, `starter-theme` e `starter-theme-child`.
2. Copiale entrambe in `wp-content/themes/` del sito (con il File Manager dell'hosting o con FTP).
3. In **Aspetto → Temi** attiva **Starter Theme Child**.

> **Nota.** Perché non caricare il pacchetto completo da *Carica tema*? Perché WordPress da quella schermata accetta un solo tema per zip. Il pacchetto completo va quindi decompresso direttamente nella cartella dei temi.

## 1.3 Il plugin Starter Blocks

I blocchi del tema e i campi di Portfolio e Immobili stanno nel plugin **Starter Blocks** (gratuito, senza licenze). Va installato e attivato insieme al tema: senza, nell'amministrazione compare un avviso e i blocchi non si vedono. Si scarica dalla release del suo repository su GitHub, come gli altri plugin Starter.

## 1.4 Installare i plugin in un colpo solo

Dopo l'attivazione, WordPress mostra l'avviso *"Questo tema richiede i seguenti plugin"*. Per installare tutto:

1. Vai in **Aspetto → Installa plugin**.
2. Seleziona i plugin (con la casella in cima li selezioni tutti) e scegli **Installa**, poi **Applica**.
3. Torna alla stessa schermata e scegli **Attiva**.

| Plugin | Ruolo | Livello |
|---|---|---|
| **Starter Blocks** | Blocchi del tema e campi di Portfolio e Immobili | Obbligatorio |
| **Contact Form 7** | Moduli di contatto e newsletter | Obbligatorio |
| Flamingo | Archivio dei messaggi inviati dai moduli | Consigliato |
| Yoast SEO | SEO; fornisce anche le briciole di pane | Consigliato |
| WooCommerce | Negozio online | Consigliato, solo per i siti con negozio |
| CookieYes | Avviso cookie (il tema lo traduce in italiano) | Consigliato |
| LiteSpeed Cache | Cache di pagina su hosting LiteSpeed (es. Hostinger) | Consigliato |
| **Starter Live** | Modifica dal sito | Consigliato |
| **Starter Security** | Sicurezza di base | Consigliato |

> **Consiglio.** I plugin "obbligatori" fanno comparire l'avviso finché mancano; quelli "consigliati" no. Puoi installare solo quelli che servono al sito: per un sito senza negozio, per esempio, salta WooCommerce.

## 1.5 I due plugin Starter

### Starter Live · modificare dal sito

Dopo aver installato Starter Live, chi ha il permesso di modificare una pagina vede in basso al centro il pulsante **✎ Modifica contenuti** (anche nella barra di amministrazione).

1. Passa il mouse sopra un elemento: si evidenzia con la sua etichetta.
2. Cliccalo e cambia il contenuto, poi premi **Applica**: l'anteprima è immediata.
3. **Salva e chiudi** scrive tutte le modifiche insieme; **Annulla** le scarta.

Si modificano testi (con grassetto, corsivo, sottolineato e link), immagini, video, gallerie, link, icone, indirizzo della mappa e shortcode, sia nei blocchi dell'editor sia nei blocchi del tema. Funziona anche sulle schede di immobili e progetti, e sui prodotti WooCommerce. Ogni salvataggio crea una **revisione** di WordPress, quindi si può sempre tornare indietro.

> **Nota.** Su schermi fino a 782 px (telefono) la modifica dal sito è disattivata, perché troppo scomoda: si usa da tablet in su. Con LiteSpeed Cache, se il pulsante non compare, escludi `starter-live` da Minimizza / Combina / Ritarda JS.

### Starter Security · protezioni di base

Ogni misura si accende e spegne da **Impostazioni → Starter Security**. Disattivando il plugin tutto torna come prima.

| Misura | Cosa fa |
|---|---|
| Header di sicurezza | Protezioni standard inviate dal server a ogni pagina (HSTS facoltativo) |
| CSP | Solo in modalità report: non blocca nulla, mostra le segnalazioni |
| XML-RPC | Chiuso (risponde 403) |
| Versione di WordPress | Nascosta nel codice |
| Utenti | Gli username non si scoprono da `/?author=N` né dalle API pubbliche |
| Login | Errore generico; blocco dell'IP dopo 5 tentativi falliti in 15 minuti |
| Editor dei file | Tolto dall'amministrazione |
| PHP in `uploads` | Bloccato con una regola `.htaccess` (Apache / LiteSpeed) |
| Limiti alle API | Ricerca, preferiti e invio moduli limitati per indirizzo IP |

> **Nota.** Se il sito sta dietro Cloudflare o un proxy, scegli nelle impostazioni da dove leggere l'indirizzo IP, altrimenti i limiti contano tutti i visitatori come uno solo.

## 1.6 Il tema figlio

Il **tema figlio** è il tema che resta attivo sul sito. Il padre (`starter-theme`) viene aggiornato da GitHub e non si tocca mai; tutto ciò che è specifico di quel sito sta nel figlio, e gli aggiornamenti del padre non lo sovrascrivono.

- **Le opzioni** del pannello Opzioni funzionano uguali con il figlio attivo. WordPress le salva separatamente per ogni tema attivo, quindi **alla prima attivazione il figlio copia quelle del padre** (se ne ha già, vince il suo valore). Da lì in poi si modificano dal figlio.
- **Il tuo CSS** va nel file `style.css` del figlio. Si carica **dopo** tutti i CSS del padre, quindi le sue regole vincono. Vale anche nell'editor dei blocchi.
- **Per cambiare un template** (per esempio `header.php`, `page.php` o `template-parts/content/card.php`), copia il file dal padre nel figlio **con lo stesso percorso** e modificalo lì: WordPress usa quello del figlio.
- **Per aggiungere codice** si usa `functions.php` del figlio, con `add_action` e `add_filter`. Le funzioni `starter_*` del padre non si ridefiniscono.
- **Un blocco del figlio** (cartella `template-parts/nome/` con il suo `block.json`) sostituisce il blocco del padre con lo stesso nome.
- Il figlio non dipende dal nome della sua cartella: per un sito nuovo puoi copiarlo, rinominarlo e cambiare `Theme Name` in `style.css`.

> **Attenzione.** Non scrivere lo stile del sito nel `style.css` del **padre**: il padre non lo carica. Il file su cui lavora il padre è `CSS/starter.css`, ma sui siti vale la regola di lavorare nel figlio.

## 1.7 I primi passi sul sito

Una checklist per partire con il sito nuovo, nell'ordine più comodo.

1. **Logo e icona.** In **Aspetto → Personalizza → Identità del sito** carica il **logo**, l'**icona del sito** (quadrata, almeno 512×512 px) e, se vuoi, il **logo chiaro** per l'header trasparente e per il footer. Il logo compare anche nella pagina di accesso di WordPress.
2. **Contenuti di prova.** **Strumenti → Contenuti di prova** crea in un clic pagine di base (Home, Chi siamo, Servizi, Contatti, Privacy e Cookie da completare, Preferiti, Blog), un menu con sottomenu, 7 articoli e, con WooCommerce, 12 prodotti finti. Si può rilanciare senza duplicare, e **"Elimina i contenuti di prova"** toglie tutto ciò che è marcato come prova.
3. **Homepage.** In **Impostazioni → Lettura** scegli *Una pagina statica* e indica la pagina Home e la pagina degli articoli (Blog).
4. **Menu.** In **Aspetto → Menu** crea il menu principale e assegnalo alla posizione **Header** (vedi il capitolo 2).
5. **Opzioni.** Percorri le sezioni di **Aspetto → Personalizza → Opzioni**: Generale, Header, Footer, Tipografia, Colori, Pulsanti.
6. **Social.** Compila gli indirizzi in **Opzioni → Generale → Social**: li usano sia l'header sia il footer.
7. **Pagine legali.** Completa Privacy Policy e Cookie Policy, e collega Termini di servizio e Credits se li usi: i link del footer ci sono, le pagine di destinazione vanno create per ogni sito.
8. **Moduli e plugin.** Accendi i moduli che servono in **Opzioni → Moduli (CPT)** (capitolo 3).

> **Nota.** Le **pagine** normali non mostrano un titolo automatico in cima: lasciano libertà di costruire l'intestazione con i blocchi (una hero, il blocco *Intestazione pagina*, o niente). Gli **articoli** invece mostrano titolo, data, autore e categoria da soli.

### Strumenti utili in amministrazione

| Strumento | Dove | A cosa serve |
|---|---|---|
| Contenuti di prova | **Strumenti → Contenuti di prova** | Pagine, articoli, prodotti, progetti e immobili di esempio per vedere il tema al lavoro |
| Clona | Voce accanto a *Modifica* e *Visualizza* | Crea una bozza identica di una pagina, un articolo o un progetto (contenuto, campi, immagine, categorie), senza plugin |
| Ottimizza immagini | **Media → Ottimizza immagini** | Prepara le immagini già in Libreria (vedi *Prestazioni* nel capitolo 2) |

## 1.8 Gli aggiornamenti

Il tema e i due plugin controllano da soli, ogni 6 ore, l'ultima release su GitHub. Quando ce n'è una più nuova, WordPress mostra la **notifica normale** in **Bacheca → Aggiornamenti** e nella pagina dei Temi o dei Plugin, con il pulsante **Aggiorna**.

- **Si aggiorna il padre**, non il figlio: le personalizzazioni del sito restano intatte.
- Per forzare subito il controllo: **Bacheca → Aggiornamenti** e *Controlla di nuovo*.
- Si possono attivare anche gli **aggiornamenti automatici** dalla pagina dei temi e dei plugin.
- Per escludere un sito dai controlli: nel suo `wp-config.php` si imposta il repository vuoto (`STARTER_UPDATE_REPO`, `STARTER_LIVE_UPDATE_REPO`, `STARTER_SECURITY_UPDATE_REPO`).

> **Attenzione.** Su un sito di **sviluppo** il cui tema è una cartella con un repository Git (`.git`), la notifica non compare di proposito: un aggiornamento sostituirebbe l'intera cartella e cancellerebbe il repository.

## 1.9 La skill per Claude (facoltativa)

Chi sviluppa con l'aiuto di **Claude** può installare la skill di Starter Theme: Claude impara com'è fatto il tema, dove si lavora e quali regole non rompere, senza doverlo rispiegare a ogni sessione. La skill si attiva da sola quando si lavora su un progetto con questo tema.

1. Scarica `starter-theme-skill.zip` dall'ultima release di **StarterSkillWP**.
2. **Claude Code:** decomprimi lo zip in modo che la cartella `starter-theme` (quella con dentro `SKILL.md`) stia in `~/.claude/skills/` per tutti i progetti, oppure in `.claude/skills/` dentro un singolo progetto.
3. **App di Claude (web o desktop):** carica lo zip dalla sezione *Skills* delle impostazioni.
4. Avvia una nuova sessione.

Per aggiornarla basta scaricare la nuova release e sostituire la cartella `starter-theme`.

# 2 · Le Opzioni

*Il cuore del tema: tutto l'aspetto e il comportamento del sito si regola da un unico pannello, senza toccare il codice.*

## Dove sono e come funzionano

Le opzioni stanno in **Aspetto → Personalizza → Opzioni**. Il pannello si apre dentro *Personalizza* di WordPress, con l'anteprima del sito accanto: ogni modifica si vede subito, e diventa definitiva solo quando premi **Pubblica**.

- **Tutto è facoltativo.** Un campo lasciato vuoto non cambia nulla: resta il valore di base del tema.
- **Gutenberg vince sempre.** Un colore o un font scelto su un singolo blocco nell'editor ha la precedenza su quello impostato qui.
- **Con il tema figlio** le opzioni funzionano allo stesso modo (alla prima attivazione il figlio copia quelle del padre).
- Molte opzioni hanno una breve descrizione nel pannello stesso.

| Sezione | Che cosa regola |
|---|---|
| **Identità del sito** (WordPress) | Logo, icona del sito, logo chiaro |
| **Menu** (Aspetto → Menu) | Le posizioni dei menu |
| **Generale** | Larghezza, bordi, caricamento pagine, scroll, social, briciole, WhatsApp |
| **Header** | Testata del sito, pulsante CTA, icone, logo |
| **Footer** | Piè di pagina: 5 stili, colonne, riga finale |
| **Tipografia** | I font del sito |
| **Stile testi** | Dimensione e aspetto di titoli e paragrafi |
| **Colori** | Tavolozza e colori del sito |
| **Pulsanti** | Le quattro varianti di pulsante |
| **Barra annunci** | Fascia con testi che scorrono sopra l'header |
| **Blog** | Elenco, archivi, ricerca e articolo |
| **Shop** | Negozio WooCommerce (solo con WooCommerce) |
| **Prestazioni** | Velocità del sito |
| **Portfolio**, **Immobili** | Opzioni dei moduli (compaiono con il modulo acceso) |
| **Moduli (CPT)** | Gli interruttori dei moduli |

## 2.1 Identità del sito

**Aspetto → Personalizza → Identità del sito**

| Opzione | Cosa fa |
|---|---|
| **Logo** | Il logo normale. Compare anche nella pagina di accesso di WordPress (`/wp-login.php`) al posto dell'icona di WordPress, con il link verso l'home del sito. |
| **Icona del sito** | La favicon, quella nella scheda del browser. Immagine quadrata di almeno 512×512 px. Da questa il tema genera anche le icone per Google, Android e Apple. |
| **Logo per l'header trasparente** | Un secondo logo opzionale, **chiaro**, per quando l'header è ancora trasparente sopra una hero scura. Appena si scorre e l'header prende lo sfondo, torna il logo normale in automatico. Lo stesso logo chiaro viene riusato nel footer. |

## 2.2 Menu

**Aspetto → Menu**

Il tema ha cinque posizioni per i menu:

| Posizione | Dove compare |
|---|---|
| **Header** | Il menu principale. Compare nella testata e, senza sottomenu, anche come colonna del footer. |
| **Mobile** *(facoltativa)* | Un menu diverso solo per il pannello mobile e per l'header "Ridotto". Se non assegni nulla, il pannello usa il menu *Header*. |
| **Privacy** | Predisposta per usi futuri; non è collegata automaticamente ad alcuna area. |
| **Footer: colonna Link** e **Footer: colonna Riferimenti** | Le due colonne menu del footer Stile 4. Il titolo di ogni colonna è il nome del menu. |

Per creare un **sottomenu**, nell'editor del menu trascina una voce leggermente a destra e sotto un'altra. Nel footer compaiono solo le voci principali.

## 2.3 Generale

**Aspetto → Personalizza → Opzioni → Generale**

| Opzione | Cosa fa |
|---|---|
| **Commenti** | Disattiva commenti e pingback su tutto il sito: nasconde il form e la voce *Commenti* nel menu. Reversibile. |
| **Larghezza contenuto** | Larghezza massima dei contenuti in px (di base 1280). Le sezioni "wide" sono 200 px più larghe. |
| **Caricamento delle pagine** | Animazione all'apertura di ogni pagina: *nessuna*, *dissolvenza* o *tenda dal basso*. Mostra un testo personalizzato, una percentuale da 0 a 100%, il nome del sito o il logo; si scelgono sfondo e testo dalla Tavolozza. |
| **Scroll fluido** | Rende lo scorrimento più morbido. Si spegne da solo per chi ha impostato "riduci le animazioni" sul dispositivo. |
| **SVG come vettori** | Gli SVG entrano nell'HTML e si colorano con il CSS. |
| **Paginazione: allineamento** | Sinistra, centro o destra: vale per tutte le paginazioni (blog, shop, portfolio, immobili). |
| **Social** | Sette indirizzi (Instagram, LinkedIn, Facebook, X, YouTube, TikTok, Substack) e fino a 5 **icone personalizzate**. Campo vuoto = icona nascosta. |
| **Bordi del sito** | Arrotondamento di card, tab, immagini, riquadri e campi: nessuno, piccoli, medi (di base 20 px), grandi, molto grandi o un valore tuo. Vale in tutto il sito, anche dentro i blocchi, tranne i pulsanti (che hanno la loro opzione). |
| **Briciole di pane** | Mostra o nasconde il percorso *Home / pagina*; si scelgono lo stile (nel titolo della pagina o in una fascia sotto l'header). Usano Yoast SEO se è presente. |
| **WhatsApp** | Link e testo del pulsante fisso in basso a destra. Campo vuoto = nessun pulsante. Diventa rotondo quando il footer entra in vista. |

### Icone social personalizzate

Per piattaforme che non sono in elenco (Threads, Pinterest, un sito esterno):

1. Carica un file **SVG** dell'icona nel campo *Icona personalizzata 1 — file SVG*.
2. Scrivi il link nel campo accanto.
3. Premi **+ Aggiungi icona personalizzata** per sbloccare quella successiva (fino a 5).

L'SVG viene "letto" e trattato come le altre icone, ereditando i colori del tema.

> **Nota.** Le due animazioni (caricamento pagine e scroll fluido) caricano una libreria esterna (GSAP, più Lenis per lo scroll) solo se almeno una è attiva. Se le disattivi entrambe, il tema non carica alcun file aggiuntivo.

## 2.4 Header

**Aspetto → Personalizza → Opzioni → Header**

### Versione dell'header

| Versione | Com'è fatta |
|---|---|
| **Standard** | Logo a sinistra; menu, CTA e icone raggruppati a destra. |
| **Centrato** | Logo a sinistra, menu al centro, CTA e icone a destra. |
| **Ridotto** | Solo il logo e un pulsante **Menu** che apre un pannello con tutto il resto, a qualunque dimensione di schermo. Il pulsante ha un'icona a tre linee che diventa una X, e il testo cambia in *Chiudi*. |
| **Pillole** | Senza sfondo, sempre visibile, con menu e pulsante in due "pillole" a destra. Mostra solo le icone del negozio, niente social. |

*Standard* e *Centrato* sotto i 1180 px diventano da soli come la versione *Ridotto*.

### Le altre opzioni

| Opzione | Cosa fa |
|---|---|
| **Header flottante** | Si combina con **qualunque** versione. L'header si stacca dai bordi, con sfondo chiaro e angoli arrotondati, ed è sempre visibile (niente trasparenza sopra la hero). Usa sempre il logo normale. |
| **Header a tutta larghezza** | Logo e menu arrivano fino ai bordi della pagina (massimo 1920 px). Si combina con tutto; con il flottante si allarga anche la card. |
| **Testo e link del pulsante CTA** | Il pulsante di contatto. Testo vuoto = non compare. Una casella lo fa aprire in una nuova scheda. |
| **Le voci con sottomenu si aprono col click** | Attiva di base. Le voci diventano pulsanti con una freccia: nel pannello mobile le sottovoci restano chiuse e si aprono toccando; su desktop si aprono anche al passaggio del mouse. |
| **Mostra i social** | Decide se le icone social (compilate in *Generale → Social*) compaiono nell'header. Indipendente dall'equivalente del footer. |
| **Stile delle icone** | Con bordo e animazione (come i social), semplici, semplici e vicine. Vale per social e icone. |
| **Ordine e divisore** | Prima i social o prima le icone, con un divisore facoltativo. |
| **Icone nell'header** | Ricerca, Account, Preferiti, Carrello: ciascuna si accende o spegne. Account, Preferiti e Carrello compaiono solo con WooCommerce. |
| **Logo scrollando** | Quando si scorre la pagina il logo resta uguale, diventa più piccolo o diventa un'icona caricata da te. |

> **Attenzione.** Con "le voci con sottomenu si aprono col click" la voce madre **non porta più alla sua pagina**. Aggiungila come prima sottovoce (per esempio *Vedi tutto*). Se disattivi l'opzione tornano i link normali, con le sottovoci sempre visibili su mobile.

La **ricerca** (la lente nell'header) apre un pannello con un'anteprima divisa in Prodotti (solo con WooCommerce), Pagine e Articoli. Le icone del negozio hanno un tooltip al passaggio del mouse (carrello con articoli e subtotale, preferiti con foto e prezzo, account) e, sotto i 600 px, nella barra resta solo il carrello.

## 2.5 Footer

**Aspetto → Personalizza → Opzioni → Footer**

### I cinque stili

| Stile | Com'è fatto |
|---|---|
| **Stile 1** | Il più ricco: riga introduttiva (titolo e testo) e colonna contatti accanto al menu, poi i social e il form newsletter, il logo a tutta larghezza e la riga finale. |
| **Stile 2 · editoriale** | Logo e descrizione in alto; sotto tre colonne: menu con voci grandi in grassetto e social, contatti, newsletter. |
| **Stile 3 · compatto** | Una sola riga con logo, menu in linea e social: per siti che non hanno bisogno di colonne e newsletter. |
| **Stile 4 · 4 colonne** | Quattro colonne con newsletter e due colonne menu (*Footer: colonna Link* e *Riferimenti*). Ha campi suoi: titolo e shortcode del form newsletter e l'immagine dei metodi di pagamento, per i negozi. |
| **Stile 5 · titolo grande** | Titolo grande con frase e sedi. Ha i campi *Frase grande* e *Sedi*. |

Tutti gli stili condividono la stessa **riga finale** e gli stessi campi qui sotto. Su tablet e telefono l'ordine degli elementi cambia da solo.

### I contenuti

| Opzione | Cosa fa |
|---|---|
| **Mostra i social** | Casella indipendente da quella dell'header. |
| **Colonna testo** | Titolo e testo introduttivo, con un valore di base modificabile. |
| **Colonna contatti** | Titolo e campo **Voci**, una per riga (indirizzo, telefono, email, orari). Email e telefoni diventano link cliccabili; si può scrivere anche un link HTML. Vuoto = colonna nascosta. |
| **Colonna menu** | Titolo; usa il menu *Header*. |
| **Spazio libero** | Uguale in tutti gli stili: titolo, sottotitolo e contenuto (shortcode o HTML). Vuoto = niente. |
| **Riga finale** | Testo di copyright (con i segnaposto `{anno}` e `{sito}`) e quattro link: **Privacy Policy**, **Cookie Policy**, **Termini di servizio**, **Credits**, ognuno con testo, indirizzo e classi CSS facoltative. Un link senza testo non compare. Gli indirizzi possono essere anche esterni (per esempio Iubenda). |
| **Colori del footer** | Sfondo, testo, hover dei link e pagina corrente: in *Colori*. |

> **Attenzione.** Il **form newsletter** del footer è un modulo di Contact Form 7 creato dai *Contenuti di prova*, ma **per ora non è collegato a nessun servizio**: va collegato a un servizio esterno (per esempio Mailchimp) o a un invio email quando serve. Sotto al form c'è una casella di accettazione della privacy obbligatoria, il cui testo si scrive nel relativo campo.

## 2.6 Tipografia

**Aspetto → Personalizza → Opzioni → Tipografia**

Si scelgono fino a **quattro font** e si assegna ciascuno a elementi specifici: H1…H6 oppure *Body* (tutto il testo del sito).

| Slot | Che font |
|---|---|
| **Font 1 e Font 2** | Menu a tendina con **font di sistema** (Arial, Georgia, Times New Roman…, già presenti su ogni computer) e **Google Fonts** (una selezione di font popolari). Finché il menu resta su *— Seleziona Font —* il font non viene caricato. |
| **Font 3 e Font 4** | Font **caricati da te** (per un font di brand): premi *+ Aggiungi Font*, scrivi il nome e carica i file (TTF, WOFF, WOFF2, EOT, SVG: solo quelli che ti servono). |

- Ogni font caricato ha **fino a 4 varianti** (Regular, Bold, Corsivo, Extra Bold…, scelte per peso e stile): così un testo in grassetto o corsivo usa il file giusto invece di un finto grassetto disegnato dal browser.
- **Applica a**: sotto ogni font ci sono sette caselle (H1…H6 e *Body*). Se lo stesso elemento è spuntato su più font vince l'ultimo (Font 4 batte 3, che batte 2, che batte 1): meglio non ripetere.
- **Rimuovi questa variante** / **Rimuovi questo font** svuotano i campi, per tornare indietro.

> **Consiglio.** Con *Prestazioni → Google Fonts serviti dal tuo sito* i font scelti qui vengono scaricati una volta sul tuo sito: niente richieste a Google, più veloce e più in regola con la privacy.

## 2.7 Stile testi

**Aspetto → Personalizza → Opzioni → Stile testi**

Dimensione e aspetto di ogni stile di testo. I valori sono già compilati e **si adattano da soli a ogni schermo**.

| Stile | Cosa si regola |
|---|---|
| **Titolo grande** (hero), **H1…H6**, **Paragrafo** | Dimensione, interlinea, spaziatura tra le lettere, peso, maiuscolo e margine sotto. |
| **Dimensioni** XXL, XL, grande, media, piccola | Le misure scelte dal selettore dimensione di Gutenberg. |
| **Pulsanti** | Dimensione e aspetto del testo dei pulsanti. |

Per ogni stile i valori possono essere diversi per **desktop, tablet e mobile**. *Tablet e Mobile vuoti = come Desktop.* Svuotare un campo riporta al valore standard.

## 2.8 Colori

**Aspetto → Personalizza → Opzioni → Colori**

Una sola sezione con **quattro gruppi** che si aprono con un clic. Tutto è facoltativo.

| Gruppo | Che cosa contiene |
|---|---|
| **Tavolozza** | Fino a 10 colori, ognuno con il suo nome. Si scelgono poi in tutte le altre opzioni e nei campi dei blocchi: cambiando un colore qui, cambia ovunque sia usato. |
| **Sfondi, testi e link** | Sfondo e testo del sito, colore principale e secondario (assegnabili ai titoli H1…H6), colore dei link (normale, hover, sottolineatura). |
| **Menu, footer e social** | Menu mobile, form newsletter del footer, colori del footer, icone social. |
| **Tag e categorie** | Colore, hover e bordo di tag e categorie. |

> **Nota.** Tutti questi colori compaiono anche come **campioni pronti** nel selettore colore di ogni blocco Gutenberg, sotto *Predefinito*: non serve ricopiare i codici. E un colore scelto su un blocco vince sempre su quello impostato qui: se hai il testo grigio scuro ma una sezione scura, quel paragrafo si può rendere bianco senza toccare le opzioni.

## 2.9 Pulsanti

**Aspetto → Personalizza → Opzioni → Pulsanti**

L'aspetto di **tutti** i pulsanti del sito, compresi quelli di Gutenberg, dei blocchi e dell'header.

| Opzione | Cosa fa |
|---|---|
| **Arrotondamento** | Come il resto del sito, squadrato, leggero, medio, morbido, a pillola, oppure un valore tuo in px. |
| **Dimensione e padding** | Normale, piccola o molto piccola; padding verticale e orizzontale personalizzabili. |
| **Maiuscolo** | Testo dei pulsanti in maiuscolo. |
| **Pieno 1 e Pieno 2** | Pulsanti con sfondo: colore a riposo e al passaggio del mouse (sfondo, testo, bordo). |
| **Contorno 1 e Contorno 2** | Pulsanti "solo bordo", con gli stessi campi. |

*Pieno 1* e *Contorno 1* sono anche gli stili *Pieno* e *Contorno* del blocco Pulsante di Gutenberg; *Pieno 2* e *Contorno 2* sono due stili in più. I colori si scelgono dalla Tavolozza. Nei blocchi del tema ogni pulsante ha un menu **Stile del pulsante** per scegliere la variante (vedi il capitolo 4). Il carattere e la dimensione del testo restano in *Tipografia* e *Stile testi*.

Al passaggio del mouse il testo ruota via mentre un suo duplicato prende il suo posto, e un colore acceso scorre da sinistra.

## 2.10 Barra annunci

**Aspetto → Personalizza → Opzioni → Barra annunci**

Una **fascia sopra l'header** con testi che scorrono in continuazione, per annunci, eventi o promozioni.

| Opzione | Cosa fa |
|---|---|
| **Mostra la barra annunci** | Accende la fascia. |
| **Annunci** | Un annuncio per riga. |
| **Etichetta in grassetto, link, separatore** | Un'etichetta in evidenza, un link facoltativo e il separatore tra gli annunci. |
| **Velocità** | Quanto scorre il testo. |
| **Pausa al passaggio del mouse** | Ferma lo scorrimento. |
| **Colori** | Sfondo e testo, dalla Tavolozza. |

La barra scorre via con la pagina e l'header fisso la segue. Con "riduci animazioni" attivo sul dispositivo, il testo sta fermo.

## 2.11 Blog

**Aspetto → Personalizza → Opzioni → Blog** (la pagina e il numero di articoli si scelgono in **Impostazioni → Lettura**)

Le stesse regole valgono per la pagina Blog, le categorie, i tag, gli autori, gli archivi per data e la ricerca. In questi archivi in alto compare una piccola etichetta con la sezione in cui ti trovi ("Categoria", "Tag", "Autore", "Risultati di ricerca") e sotto il titolo grande.

| Opzione | Cosa fa |
|---|---|
| **Aside** (filtri e ricerca) | La colonna a destra con ricerca, categorie con il numero di articoli, ultimi articoli e tag. Si accende **separatamente** per elenco/archivi/ricerca e per gli articoli, e può essere **fissa**. Se l'area *Sidebar Blog* ha widget in **Aspetto → Widget**, si usano quelli. Su tablet e mobile diventa un pannello che entra da destra. |
| **Colonne della griglia** | 1, 2, 3 o 4 (3 di base). Con *1 colonna* la foto sta a sinistra e il testo a destra. Su tablet e mobile scendono da sole. |
| **Articoli per pagina** | Quanti articoli per pagina nell'elenco. |
| **Larghezza massima dell'articolo (px)** | Solo con l'aside degli articoli spenta: titolo, immagine e testo non superano questa larghezza e stanno al centro. Vuoto o 0 = tutta la larghezza disponibile. |
| **Filtri per categoria sopra l'elenco** | Solo nella pagina Blog: le categorie con il numero degli articoli in un cerchio, tra il testo di apertura e gli articoli (stesso aspetto dei filtri classici del Portfolio). Scegliendone una la pagina mostra solo quegli articoli. |
| **Ultimo articolo in evidenza** | Un articolo a tutta larghezza, solo nella prima pagina. Nella pagina Blog lo sceglie, in quest'ordine: l'**articolo scelto a mano** (menu sotto), il più recente con categoria o tag **In evidenza** (slug `in-evidenza`), l'ultimo articolo. Senza aside la sua immagine è 4:3, più alta. |
| **Articolo in evidenza scelto a mano** | Un articolo a piacere tra gli ultimi 100; ha la precedenza su tutto. |
| **Card dell'elenco** | Mostra o nasconde data, autore (anche cliccabile), categorie, tag. |
| **Articolo** | Data, autore, categorie, **sommario**, tag (a fine articolo), **riquadro autore** (avatar, nome e biografia dal profilo utente), **articoli correlati** ("Continua a leggere", stessa categoria), pulsanti **Condividi**. |

Titolo e testo introduttivo della pagina Blog si modificano editando la pagina "Blog".

## 2.12 Shop

**Aspetto → Personalizza → Opzioni → Shop** (la sezione compare solo con WooCommerce attivo)

Tutti i colori, i font e le dimensioni vengono dalle opzioni del tema: nel CSS dello shop non c'è nessun valore scritto a mano. Le pagine di WooCommerce (shop, categorie, scheda prodotto, carrello, cassa, account) hanno già lo stile del tema.

| Gruppo | Cosa si regola |
|---|---|
| **Filtri** | Si attivano per la pagina Shop e, se vuoi, anche per le categorie. Ogni filtro si accende da solo: ricerca con suggerimenti, categoria, prezzo (da/a), attributi (Colore, Materiale… uno per ogni attributo che ha prodotti). Colonne dei prodotti e conteggio. L'indirizzo resta condivisibile. |
| **Card prodotto** | Cuore dei preferiti, pulsante *Aggiungi al carrello* al passaggio del mouse, seconda foto all'hover, categorie, descrizione breve. |
| **Pagine e cassa** | Pagina dei **preferiti** (con lo shortcode `[starter_preferiti]`, creata dai contenuti di prova; i preferiti stanno nel browser del visitatore, senza account). Campi facoltativi per la fattura: Partita IVA, Codice fiscale, Codice SDI/PEC. |
| **Negozio in test** | Un avviso con il testo che scegli, mostrato come fascia gialla in fondo alla pagina, sotto il footer. |

## 2.13 Prestazioni

**Aspetto → Personalizza → Opzioni → Prestazioni**

Il sito è pensato per essere veloce e piacere ai motori di ricerca (Core Web Vitals) senza plugin aggiuntivi.

### Sempre attivo

Le immagini diventano **WebP** in automatico; gli script del tema non bloccano il caricamento; il collegamento a Google Fonts e alle librerie esterne è anticipato solo se usato; CSS e JavaScript sono compressi e divisi per tipo di pagina; la testata della pagina è ripulita da emoji, feed e script inutili.

### Le caselle del pannello

| Opzione | Cosa fa |
|---|---|
| **Compressione e cache del browser** | Il tema scrive nel file `.htaccess` le regole per comprimere i file di testo e far ricordare al browser immagini, font, CSS, JS e video per un anno (l'HTML no). Funziona su Apache e LiteSpeed (per esempio Hostinger). Spegnendo la casella le toglie. |
| **Google Fonts serviti dal tuo sito** | I font di *Tipografia* vengono scaricati una volta sul tuo sito: niente richieste a Google, più veloce e più in regola con la privacy. |
| **Precarica la foto di apertura (hero)** | Se la pagina comincia con una hero con foto, il browser la scarica subito, nella misura giusta. |
| **Contact Form 7 solo dove c'è un modulo** | Niente script e stile dei moduli nelle altre pagine. |
| **Immagini AVIF (con ripiego WebP)** | Accanto a ogni immagine il tema crea un file `.avif` più leggero; il browser sceglie da solo il formato che sa leggere. Si carica come sempre JPG o PNG. La qualità si regola da 40 a 90. |
| **Video WebM (con ripiego mp4)** | Carica in Libreria un `.webm` con lo **stesso nome** dell'mp4 (esportato a mano con HandBrake o ffmpeg): il tema offre prima il WebM e poi l'mp4. |
| **Favicon .ico e icone** | Dall'icona del sito genera `favicon.ico`, le icone per Google, Android e Apple e il manifest. |
| **Unisci CSS e JS in un solo file** | Spenta di base. Unisce i file consecutivi in uno solo, nello stesso ordine. Per vedere la pagina senza compressione (da amministratore) aggiungi `?starter-raw=1` all'indirizzo. |
| **Comprimi il codice HTML** | Spenta di base. Toglie commenti, a capo e spazi doppi, senza cambiare ciò che si vede. |
| **Togli jQuery Migrate** | Rimuove un piccolo script per i vecchi plugin. |

### Le immagini già in Libreria

**Media → Ottimizza immagini** crea gli AVIF mancanti (a lotti, con barra di avanzamento) e converte in WebP i JPG e PNG rimasti, aggiornando gli indirizzi in articoli e opzioni.

> **Attenzione.** Prima di convertire la Libreria esistente fai un **backup** del database e della cartella `uploads`.

> **Nota.** Restano fuori dalla conversione gli SVG, il favicon, il logo delle email di WooCommerce e i file con `email` o `nowebp` nel nome. Serve PHP con supporto AVIF: la pagina *Ottimizza immagini* dice se il server lo ha; se manca, restano i WebP.

**Da fare lato hosting** (non dal tema): PHP aggiornato, la cache di pagina del provider (su Hostinger LiteSpeed Cache o la cache integrata) e un CDN se serve. Per verificare, usa PageSpeed Insights e controlla che la risposta del server abbia l'intestazione `content-encoding: br` o `gzip`.

## 2.14 Opzioni dei moduli

**Aspetto → Personalizza → Opzioni → Moduli (CPT)** contiene un interruttore per ogni modulo. Le sezioni **Portfolio** e **Immobili** compaiono solo se il modulo è acceso. Come funzionano i moduli è spiegato nel capitolo 3; qui l'elenco delle opzioni.

### Portfolio

| Gruppo | Cosa si regola |
|---|---|
| **Scheda del progetto** | Se mostrare i dati nella testata (servizi, agenzie, link) e la sezione *Altri progetti* in fondo. |
| **Passaggio tra i progetti** | L'animazione quando si passa dalla scheda a un altro progetto: nessuna, **copertina a tutto schermo**, **tendina** o **dissolvenza**; scritta del caricamento o percentuale da 0 a 100%; colori di sfondo e testo. |
| **Altri progetti: categorie** | Stile delle categorie sulle card: testo con simbolo, tab sotto o sopra l'immagine, tab piene o vuote, simbolo e colori, posizione rispetto al titolo, maiuscolo. |
| **Altri progetti: riassunto e cliente** | Ordine, mostra o nascondi, tag HTML, dimensione, posizione e colori (anche all'hover). |
| **Altri progetti: immagine** | Proporzione delle immagini (16:9, 3:2, 4:3, 1:1, 4:5), **stile delle card** (*Logo*: al passaggio il nome del cliente scorre sull'immagine; *Classica*: titolo e nome del cliente scritti sotto l'immagine, con ordine, tag HTML e dimensione del testo a scelta in "Altri progetti: riassunto e cliente"). Le opzioni sono in quest'ordine: immagine, riassunto e cliente, categorie; per immagine, riassunto, cliente e categorie c'è anche lo **spazio sotto** (MB05, MB1, MB2… fino a MB10 e MB20), uguale nel blocco Elenco progetti, logo con il colore di sfondo, **video al passaggio del mouse**. |

### Immobili

| Opzione | Cosa fa |
|---|---|
| **Shortcode del modulo "Richiedi informazioni"** | Il modulo che compare nella scheda dell'immobile. Si crea da solo con Contact Form 7 quando accendi il modulo. |

# 3 · I moduli (CPT)

*Funzioni facoltative che portano con sé tipi di contenuto, campi, blocchi e pagine: si accendono solo se servono.*

## 3.1 Cosa sono i moduli

Un **modulo** è una funzione facoltativa del tema che porta con sé tutto ciò che le serve: un **tipo di contenuto personalizzato** (CPT) con le sue categorie, i suoi campi, i suoi blocchi, la scheda di dettaglio e una pagina di elenco. Oggi i moduli sono due, **Portfolio** e **Immobili**; altri potranno aggiungersi.

Si accendono e si spengono da **Aspetto → Personalizza → Opzioni → Moduli (CPT)**: un interruttore per ogni modulo, con la sua descrizione. **Nei siti nuovi partono tutti spenti.**

| | Modulo **spento** | Modulo **acceso** |
|---|---|---|
| **Codice** | Non viene nemmeno caricato: niente tipo di contenuto, campi, blocchi, CSS, JS o richieste in più. | Carica solo ciò che serve alla pagina; CSS e JS del modulo partono solo dove sono usati. |
| **Pagina** | Le pagine create dal tema per quel modulo tornano **in bozza** (i loro link, e quelli delle schede, danno 404). | Crea la pagina del modulo (Portfolio, Immobili) con già dentro il blocco dell'elenco, oppure la ripubblica. |
| **Contenuti** | **Restano nel database** e tornano riaccendendo il modulo. | Compaiono i suoi blocchi nell'editor e le sue opzioni. |

Dopo aver cambiato un interruttore, **pubblica e ricarica** la pagina del Personalizza: gli indirizzi del sito si aggiornano alla richiesta successiva. Una pagina fatta a mano che usa il blocco di un modulo spento resta pubblicata, ma il blocco non stampa niente.

> **Consiglio.** Per un sito che non userà mai un modulo, i suoi file si possono anche non caricare online: il tema non si rompe, perché controlla che il file del modulo esista prima di caricarlo.

I contenuti di prova di ogni modulo acceso si creano da **Strumenti → Contenuti di prova**.

## 3.2 Portfolio

**Aspetto → Personalizza → Opzioni → Moduli (CPT) → Portfolio**. Una volta acceso, nel menu di WordPress a sinistra compare la voce **Portfolio** (icona a valigetta) per i progetti.

### Come funziona

**Non esiste un archivio automatico.** L'elenco dei progetti è una **pagina normale** (per esempio *Portfolio*): la crei tu, ci metti titolo, testi e gli altri blocchi che vuoi, e dove vuoi il blocco **Elenco progetti**. Puoi averne anche più di una, per esempio un elenco di soli loghi in un'altra pagina. Le categorie non hanno pagine proprie.

### Il blocco Elenco progetti

Si imposta dai campi del blocco:

| Campo | Cosa fa |
|---|---|
| **Colonne e progetti per pagina** | 2, 3 o 4 colonne; quanti progetti mostrare per pagina. |
| **Layout** | **Classica** (stessa altezza), **Masonry** (formato originale), **A blocchi** (2 + 1, con quarto elemento), **Sfalsata** ed **Elenco** (righe una sotto l'altra: categoria, nome e un dato a scelta; al passaggio del mouse la riga si colora e compare la copertina o il video; versioni *nome al centro* o *nome a sinistra*, quest'ultima con la copertina grande e inclinata che esce dalla riga). |
| **Schede delle categorie** | *Tutti, Web design…* con o senza numero di progetti; si può mostrare solo alcune categorie. |
| **Card** | Numero progressivo, categorie sulle card, titolo e riassunto (ordine, tag, dimensione, posizione, colori anche all'hover). |
| **Filtri** | Linea (Classica), testo grande, pillole, frase; con conteggi; nella stessa pagina o con ricarica; su telefono in una riga scorrevole. |
| **Animazioni** | **Animazione logo**: il logo (anche SVG) si vede sempre intero, nel colore di sfondo, e al passaggio il nome scorre da destra a sinistra, sempre in maiuscolo. **Video all'hover**: il video di copertina parte al passaggio del mouse. |

Le schede e la paginazione sono link alla stessa pagina (`?categoria=…`, `?pagina=2`): funzionano anche senza JavaScript.

### Un progetto

Un progetto si compone così:

- **Titolo**, **immagine in evidenza** (è anche la copertina delle card) e **categorie** (Web design, Graphic design, Logo design…): si creano da **Portfolio → Categorie portfolio** e servono per le schede dell'elenco.
- **Dettagli del progetto** (campi sotto l'editor): **logo del cliente**, **copertina a tutto schermo** (casella), **link al progetto online**, **video di copertina** (facoltativo: sostituisce l'immagine), **testo sotto il titolo** e **agenzia di comunicazione o partner** (tipo, nome e link; se ne possono aggiungere più d'uno). C'è anche una scheda **Progetti correlati**.
- **Contenuto** con i blocchi *Progetto — testo*, *Progetto — media* e *Progetto — crediti* (vedi il capitolo 4), mescolati nell'ordine che serve. Vanno bene anche i blocchi normali di Gutenberg e gli altri blocchi del tema.
- Nell'editor, il blocco *Progetto — testata* mostra un'**anteprima** di copertina, titolo e dati; non si salva e non appare nel sito.
- In fondo, **Altri progetti** (stessa categoria, poi i più recenti) con il pulsante *Tutti i progetti* verso la pagina dell'elenco. Se indichi dei **progetti correlati** (fino a 3), i mancanti si prendono dagli ultimi progetti, mai il progetto stesso e mai doppioni.

Nella scheda, il riassunto è l'H1 (formattato come un H5, maiuscolo) e il nome del progetto è l'H2; il nome del cliente è il titolo del progetto. I **servizi** sono le categorie: aggiungendone una compare nelle tab. Il link *Visita il progetto* mostra il dominio del sito (per esempio `artistica3.com`) nello stesso stile dei nomi delle agenzie.

### I passaggi tra le pagine

Dall'elenco alla scheda di un progetto, e da un progetto a un altro, il tema può animare il cambio pagina (si sceglie in *Opzioni → Portfolio → Passaggio tra i progetti*):

| Passaggio | Come appare |
|---|---|
| **Copertina a tutto schermo** | L'immagine della card si ingrandisce e atterra sulla copertina. Se la copertina è un video, il passaggio è in dissolvenza, senza scatti. |
| **Tendina** | Un velo sale dal basso con il nome. |
| **Dissolvenza** | Una schermata con testo o con la percentuale da 0 a 100%. |
| **Nessuno** | Cambio pagina normale. |

Le stesse animazioni (nessuna, dissolvenza, tenda) si possono usare **all'apertura di ogni pagina** del sito dalla scelta *Caricamento delle pagine* in *Generale*.

### Modifica dal sito e contenuti di prova

Con **Starter Live** attivo, dalla testata di un progetto si modificano titolo, riassunto, testo sotto il titolo, nomi e link delle agenzie e link al progetto, e i blocchi *Progetto —* come tutti gli altri blocchi. **Strumenti → Contenuti di prova** crea la pagina **Portfolio** (intestazione e blocco Elenco progetti), 3 schede complete con strutture diverse (identità visiva, sito web con video, logo e grafica) e 6 progetti brevi, con immagini segnaposto e i video di esempio del tema. *Elimina* toglie i progetti di prova; la pagina resta.

Da fare in seguito: filtro senza ricaricare la pagina e altri layout della griglia.

## 3.3 Immobili

**Aspetto → Personalizza → Opzioni → Moduli (CPT) → Immobili**. Il modulo ha lo stile del tema: colori, font, dimensioni e **pulsanti** sono quelli delle tue opzioni, gli stessi del resto del sito.

### Cosa include

- **Tipo di contenuto Immobile** (titolo e immagine; il resto sono campi) e le categorie **Tipologia**, **Contratto**, **Zona**, **Consulente** ed **Evidenza** (quest'ultima decide chi compare nella *Hero home* e in *In evidenza*).
- **La scheda dell'immobile**: galleria a scorrimento con ingrandimento, dati in sintesi, punti di forza, caratteristiche e altri dati, **planimetria** (con download), **tour virtuale 3D** (link o codice iframe, per esempio Matterport), video, mappa e punti di interesse, consulente e modulo *Richiedi informazioni*, sezione *Altri immobili*.
- **Scheda in PDF** (pulsante *Scarica scheda immobile*): generata al volo con i colori e il logo del tema, poi messa in cache.
- **La pagina Immobili** con il blocco *Immobili — Elenco*: riquadro dei filtri uguale a quello dello shop; **ricerca per parola chiave** solo sugli immobili; filtri per tipologia, contratto, zona, prezzo, superficie, locali e bagni; 9 per pagina con paginazione.
- **I tre blocchi** (nell'editor hanno il prefisso *Immobili —*): *Elenco*, *In evidenza* e *Hero home* (immobile in evidenza con card). Vedi il capitolo 4.

### Come si lavora

- **Opzioni → Immobili:** lo shortcode del modulo *Richiedi informazioni*, creato da solo con Contact Form 7 quando si accende il modulo.
- **Modificabile dal sito con Starter Live:** nella scheda si cambiano titolo, apertura, prezzo, superficie, locali, camere, bagni, note, piano, altri dati, descrizioni, punti di forza, indirizzo, zona, punti di interesse, foto della galleria e planimetria. I numeri si scrivono "puliti" (per esempio `1500000`) e la pagina li formatta da sola.
- **Contenuti di prova:** 3 immobili (uno in vendita, uno in affitto e uno all'asta, con locali, bagni, prezzi, tipologie e zone diverse per provare i filtri), 4 foto e una planimetria neutra, con tutti i campi compilati.

## 3.4 I filtri degli elenchi

Shop, Immobili e ogni futuro tipo di contenuto con filtri usano **lo stesso blocco di filtri**, con lo stesso aspetto e lo stesso comportamento. Così un nuovo modulo non richiede mai un CSS dei filtri a parte.

| | Come si comporta |
|---|---|
| **Desktop** | Griglia a 12 colonne, 3 campi per riga; i due pulsanti (*Cerca* e *Azzera i filtri*) nell'ultima colonna della loro riga. |
| **Tablet e mobile** (fino a 1024 px) | Un pulsante *Filtri* affiancato all'ordinamento apre un pannello da sinistra, con i pulsanti fissi in fondo e un'ombra sopra che indica che i filtri continuano. |
| **Scelta multipla** | Le tendine con più scelte (tipologia, zona…) funzionano allo stesso modo ovunque. |

## 3.5 Aggiungere un nuovo modulo

Questa parte è per chi sviluppa. Un modulo è una voce nell'elenco `starter_modules()` (file `inc/modules.php`) con il suo file, i suoi blocchi, la sua pagina e i suoi contenuti di prova: l'interruttore nelle Opzioni compare da solo. Se usa i filtri, carica il foglio condiviso con `add_filter( 'starter_needs_filters_css', … )`: un esempio è nel modulo Immobili (`inc/modules/immobili/immobili.php`).

# 4 · I blocchi personalizzati

*Le sezioni pronte da inserire nell'editor: hero, contenuti, recensioni, mappa, elenchi e i blocchi dei moduli.*

## 4.1 Come si usano

I blocchi del tema sono blocchi di Gutenberg del plugin **Starter Blocks** (vedi il capitolo 1): si inseriscono dall'editor di WordPress come qualsiasi altro blocco, ma sono sezioni complete con i loro campi.

- **Dove si trovano.** Nel pulsante **+** dell'editor, nella categoria **Starter**. La pagina *Blocchi di prova* (dai *Contenuti di prova*) li mostra tutti.
- **Come si compilano.** Selezionato il blocco, la **matita** nella barra del blocco apre una finestra con tutti i campi, divisi per schede (Contenuto, Layout, Avanzate…); *Salva* aggiorna l'anteprima, *Annulla* scarta. Le opzioni di sfondo e le classi CSS sono anche nella colonna a destra.
- **I titoli** hanno il selettore del tag HTML (H1…H6, p, span): serve alla SEO, per tenere una gerarchia corretta (un solo H1 per pagina).
- **I testi** ammettono grassetto, corsivo e link; in molti blocchi anche il sottolineato (barra **B / I / U**).
- **I pulsanti** dei blocchi hanno un menu **Stile del pulsante**: *Predefinito del blocco*, *Pieno 1*, *Pieno 2*, *Contorno 1*, *Contorno 2* e, sopra le foto, *Chiaro*. I colori di ogni variante si decidono in *Opzioni → Pulsanti*.
- **Le foto** passano dalla Libreria media e diventano da sole AVIF e WebP (vedi *Prestazioni*).
- **Con Starter Live** attivo, quasi tutti i testi, le immagini e i link dei blocchi si cambiano direttamente dal sito.

### Spaziature rapide

Per regolare i margini di un blocco senza scrivere CSS, scrivi le classi nel campo **Classi CSS aggiuntive** del blocco:

| Classe | Effetto |
|---|---|
| `mt2`, `mb1`, `pt3`, `pb4`, `p2`… | Margine sopra (`mt`) o sotto (`mb`), spazio interno sopra (`pt`) o sotto (`pb`), o tutto attorno (`p`); il numero è la misura in rem. |
| `-tab`, `-sma` | Varianti per tablet e per mobile: `mt4 mt2-tab mt1-sma` = 4 su desktop, 2 su tablet, 1 su telefono. |

### Una hero senza blocco del tema

La sezione di apertura può essere anche un normale blocco **Cover** di Gutenberg con la classe `hero-section`: l'header riconosce la hero guardando il contenuto della pagina, quindi qualunque pagina che inizia con quel blocco ottiene l'header trasparente.

## 4.2 Aperture della pagina (hero)

| Blocco | Cosa fa | Opzioni principali |
|---|---|---|
| **Hero con slider** | Foto a tutta larghezza che cambiano in dissolvenza, con titolo, testo e fino a due pulsanti. | 1–6 immagini, durata, due link |
| **Hero con video** | Video a tutta larghezza (muto, in ciclo) con velo scuro, titolo, testo e pulsanti. | Video mp4/webm, copertina, due link |
| **Hero testo + foto** | Prima sezione con occhiello, titolo, testo, due pulsanti e una foto a lato. | Foto a sinistra o a destra |
| **Hero con foto e video** | Apertura con testo, foto dei clienti e, a destra, una foto e un video verticale con etichette. | Etichette e colori, video |
| **Intestazione pagina** | Occhiello, titolo H1 e paragrafo in cima alla pagina, con briciole di pane automatiche. | Testo sotto o a destra |

> **Consiglio.** Se la pagina comincia con una hero con foto, attiva *Prestazioni → Precarica la foto di apertura*: il browser la scarica subito.

## 4.3 Sezioni di contenuto

| Blocco | Cosa fa | Opzioni principali |
|---|---|---|
| **Sezione affiancata** | Foto (1 o 2) accanto a un testo scritto con i blocchi di Gutenberg (titolo, paragrafi, pulsanti). | Formato foto, lato, sfondo |
| **Testo affiancato** | Testo e fino a 2 foto composte, affiancati; checklist, citazione, link. | Lato, sfondo chiaro, livello del titolo |
| **Sezione voci** | Titolo e un elenco di voci in 4 layout: elenco numerato (con testo a fianco), card con testo, card strette con icona, card numerate a passi. | 4 layout, icone, link |
| **Striscia valori** | Riga di voci con icona e titolo (garanzia, spedizione, assistenza…). | Icona + titolo |
| **Due riquadri** | Due (o più) riquadri affiancati su sfondo chiaro; il secondo evidenziato con il colore d'accento. | Occhiello, titolo, testo |
| **Elenco servizi** | Un menu laterale che segue lo scroll (su tablet e telefono diventa una barra di icone fissa in basso) e una scheda per servizio: ancora, icona, occhiello, titolo, foto, elenco con spunte, pulsante. | Icona, foto, pulsante |
| **Slider** | Slider singolo o carosello di immagini e video. | Vedi sotto |
| **Team** | Griglia delle persone: foto quadrata, nome, ruolo e breve bio. | Foto, nome, ruolo, bio |
| **Recensioni** | Carosello di testimonianze con 5 stelle, scorrevole con le frecce o trascinando con il mouse, in ciclo continuo. | Nome, testo, data, video |
| **Instagram** | Foto statiche dal profilo con link (nessun collegamento esterno). | Account, foto |

### Il blocco Slider

Il più configurabile. Si usa una **galleria** (le didascalie vengono dalla Libreria media) e si sceglie:

- **Tipo** (slider singolo o carosello) e **larghezza** (nella misura del sito o a tutto schermo);
- **quante immagini vedere** su desktop, su tablet (fino a 1100 px) e su mobile (fino a 640 px);
- **distanza** tra le immagini, **anteprima** delle slide vicine (quanti pixel si intravedono), **proporzione** e angoli;
- **frecce**: sull'immagine ai lati, sopra o sotto, con lo stile di uno dei quattro pulsanti;
- **paginazione**: puntini (l'attivo si allunga), contatore o barra, centrata;
- **scorrimento automatico**, ricomincio dall'inizio e didascalie.

Scorre con frecce, puntini, tastiera, dito e trascinamento con il mouse.

## 4.4 Contatti e mappa

| Blocco | Cosa fa | Opzioni principali |
|---|---|---|
| **Contatti** | Elenco dei recapiti (presi dai dati del footer) e modulo di contatto con Contact Form 7. | Recapiti con icona, shortcode del modulo |
| **Mappa** | Mappa Google a tutta larghezza con pulsante per le indicazioni stradali, dato solo l'indirizzo. | Indirizzo, pulsante |

> **Nota.** Ogni modulo di Contact Form 7 ha un anti-spam invisibile (campo trappola e orario) e due campi nascosti, **pagina** e **pagina-url**, che dicono da quale pagina arriva il messaggio: li vedi in Flamingo e in fondo alla mail. Non serve toccare i form.

## 4.5 Blog e negozio

| Blocco | Cosa fa | Opzioni principali |
|---|---|---|
| **Ultimi articoli** | Gli articoli più recenti (anche di una sola categoria) con la card del blog. | Categoria, numero |
| **Prodotti in evidenza** | Griglia di prodotti WooCommerce con la card del negozio (serve WooCommerce). | In evidenza, più venduti, recenti, per categoria o scelti a mano |

## 4.6 Blocchi del Portfolio

Compaiono con il modulo **Portfolio** acceso (capitolo 3).

| Blocco | Cosa fa | Opzioni principali |
|---|---|---|
| **Elenco progetti** | Griglia dei progetti con filtri per categoria e paginazione. Si mette in una pagina normale. | Layout, titolo, categorie, filtri, animazioni |
| **Progetto — testo** | Blocco di testo della scheda: occhiello (per esempio *Contesto*), titolo e paragrafi. | Affiancato o centrato |
| **Progetto — media** | Immagini e video della scheda in 1, 2 o 3 colonne. | Proporzione; immagine o video per elemento |
| **Progetto — crediti** | Ruoli e nomi dei collaboratori; ogni nome può avere un link al suo sito (per esempio il fotografo). | Ruolo, nome, link |
| **Progetto — testata** | Solo nell'editor: anteprima in sola lettura di copertina, titolo e dati del progetto. Non si salva e non appare nel sito. | — |

## 4.7 Blocchi degli Immobili

Compaiono con il modulo **Immobili** acceso (capitolo 3).

| Blocco | Cosa fa | Opzioni principali |
|---|---|---|
| **Immobili — Elenco** | Tutti gli immobili con filtri e paginazione, 9 per pagina. | Occhiello, titolo, testo |
| **Immobili — In evidenza** | Griglia con i 3 immobili più recenti marcati *In evidenza home*. | Titolo, link |
| **Immobili — Hero home** | Prima sezione della home: testo, pulsanti, immagine e card dell'immobile in evidenza. | Titolo, due pulsanti |

## 4.8 Creare un nuovo blocco

Questa parte è per chi sviluppa. Un blocco nuovo si registra da solo, senza scrivere JavaScript:

1. Nella cartella `blocks/` del plugin Starter Blocks (o, per un sito solo, in `blocks/` del **tema figlio**) crea una cartella con il nome del blocco, partendo dall'esempio in `esempi/blocco-esempio` del figlio.
2. `block.json`: nome e titolo. `fields.json`: l'elenco dei campi (testo, area di testo, scelta, pulsanti, interruttore, numero, link, foto, file, galleria, icona, ripetitore, categoria, contenuti scelti a mano; con schede e condizioni *mostra solo se*). `render.php`: cosa stampa il blocco, leggendo i campi con `starter_bf( 'nome' )`.
3. Il pannello di modifica (matita, schede, ripetitori) lo disegna il plugin dai campi.

Il blocco compare nella categoria **Starter**. Con lo stesso sistema i campi di un tipo di contenuto (per esempio un Servizio) si dichiarano in un file JSON nella cartella `meta/`: compaiono in un riquadro nella schermata di modifica e nel template si leggono con `starter_field( 'nome' )`.
