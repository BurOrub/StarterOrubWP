# Guida al tema — Starter Theme

Questa guida spiega come usare e gestire questo tema WordPress: dove si trovano le impostazioni, come funzionano header, footer, tipografia, colori, blog e menu, e cosa manca ancora. È pensata per chi conosce WordPress a livello base/medio — niente codice, solo "dove clicco per cambiare X".

---

## 1. Prima di usarlo per un nuovo progetto

Il tema è nato come base riutilizzabile. Prima di consegnarlo su un progetto vero:

- In `style.css`: aggiorna **Theme Name**, **Author URI**, **Description**.
- Il *text domain* usato nel tema è `slug-theme` (un segnaposto): se vuoi, sostituiscilo ovunque con lo slug reale del progetto tramite "trova e sostituisci".
- Carica **logo**, **icona del sito** e imposta i **social** (vedi sotto).

---

## 2. Logo e identità del sito

**Dove:** *Aspetto → Personalizza → Identità del sito*

- **Logo**: il logo normale del sito. Viene usato anche come sfondo del logo nella **pagina di login** di WordPress (`/wp-login.php`), al posto dell'icona di WordPress — non serve caricarlo una seconda volta, e il link del logo lì porta all'home del sito invece che a wordpress.org.
- **Icona del sito**: la favicon (quella nella scheda del browser). Serve un'immagine quadrata almeno 512×512px.
- **Logo per l'header trasparente**: un secondo logo opzionale, pensato per quando l'header è ancora trasparente sopra la hero della homepage (dove il logo normale potrebbe avere poco contrasto su una foto scura). Si attiva con l'apposito checkbox, poi carichi l'immagine. Appena l'utente scrolla e l'header prende lo sfondo bianco, torna automaticamente il logo normale — non serve fare nulla, è automatico. Questo stesso logo "chiaro" viene riusato anche nel footer (vedi sezione 8).

---

## 3. Generale (commenti, animazioni, social)

**Dove:** *Aspetto → Personalizza → Opzioni → Generale*

- **Disattiva i commenti su tutto il sito**: rimuove commenti e pingback da articoli e pagine, nasconde il form e la voce "Commenti" dal menu di WordPress. Reversibile: se lo disattivi torna tutto come prima.
- **Animazione di caricamento**: mostra il logo a tutto schermo mentre la pagina carica, poi si apre sul contenuto.
- **Scroll fluido**: rende lo scroll del sito più morbido. Si disattiva da solo per chi ha impostato "riduci le animazioni" nel proprio dispositivo (accessibilità).

Le due opzioni di animazione caricano una libreria esterna (GSAP, più Lenis per lo scroll fluido) solo se almeno una delle due è attiva — se le disattivi entrambe, il tema non carica nessun file aggiuntivo.

### Social

Compila l'URL solo dei social che vuoi mostrare (Instagram, LinkedIn, Facebook, X, YouTube, TikTok, Substack): se lasci un campo vuoto, quell'icona semplicemente non compare — non serve "disattivarla" a parte. Questi social sono **condivisi** tra header e footer: li compili una volta sola qui, e poi decidi separatamente se mostrarli nell'header e/o nel footer (vedi rispettive sezioni).

### Icone social personalizzate

Per piattaforme non in elenco (es. Threads, Pinterest, un sito esterno):

1. Carica un file **SVG** dell'icona nel campo "Icona personalizzata 1 — file SVG".
2. Inserisci il link nel campo accanto.
3. Premi **"+ Aggiungi icona personalizzata"** per sbloccare uno slot successivo (fino a 5 in totale).

L'icona caricata viene "letta" e trattata come le altre (eredita i colori del tema), non resta un'immagine fissa.

---

## 4. Tipografia

**Dove:** *Aspetto → Personalizza → Opzioni → Tipografia*

Permette di scegliere fino a **4 font diversi** e assegnare ciascuno a elementi specifici del sito (H1..H6, oppure "Body" per applicarlo a tutto il sito).

- **Font 1 e Font 2**: un menu a tendina con due gruppi — **Font di sistema** (Arial, Georgia, Times New Roman, ecc., già installati su ogni computer, nessun caricamento) e **Google Fonts** (una selezione di font popolari, in ordine alfabetico). Finché il menu resta su "— Seleziona Font —", quel font non viene caricato sul sito.
- **Font 3 e Font 4**: font caricati da te (per un font "di brand" non disponibile su Google Fonts). Ogni font parte nascosto dietro il proprio bottone **"+ Aggiungi Font 3"** / **"+ Aggiungi Font 4"**: premendolo compaiono il campo "Nome del font" e i file da caricare per la prima variante (TTF/WOFF/WOFF2/EOT/SVG — carica solo i formati che ti servono, non serve compilarli tutti).
  - Ogni font può avere **fino a 4 varianti** (Regular, Bold, Corsivo, Extra Bold, ecc. — scegli Peso e Stile per ciascuna): questo permette che se scrivi un testo in **grassetto** o *corsivo* dentro una pagina, venga usato automaticamente il file giusto invece di un finto grassetto/corsivo disegnato dal browser. Solo la prima variante è visibile di default, le altre si aggiungono con "+ Aggiungi variante".
  - **"Rimuovi questa variante"** / **"Rimuovi questo font"**: svuotano i campi e richiudono la sezione, così puoi tornare indietro se hai aggiunto qualcosa per errore.
- **"Applica a"**: sotto ogni font (tranne i bottoni "Rimuovi") trovi 7 checkbox in linea — H1, H2, H3, H4, H5, H6 e "Body" (tutto il sito). Spunta quali elementi devono usare quel font. Se lo stesso elemento è selezionato in più font contemporaneamente, vince l'ultimo (Font 4 batte Font 3, che batte Font 2, che batte Font 1) — meglio evitare di spuntare lo stesso elemento in più font.

---

## 5. Colori

**Dove:** *Aspetto → Personalizza → Opzioni → Colori* (una sola tab con 4 gruppi: Tavolozza, Sfondi/testi/link, Menu/footer/social, Tag)

Colori di base per tutto il sito — tutti facoltativi: un campo lasciato vuoto non cambia nulla, resta il colore di base del tema.

- **Sfondo del sito** / **Testo**: i due colori principali usati ovunque (cambiano anche i colori del footer, che li riusa invertiti per il suo sfondo scuro).
- **Colore principale** / **Colore secondario**: due colori assegnabili ai titoli con lo stesso sistema a checkbox della Tipografia (H1..H6, qui senza "Body" — quello è già il campo "Testo" sopra).
- **Pulsante principale** (colore, colore hover, colore testo, colore testo hover): ricolora sia il bottone "pieno" dei contenuti (blocco Pulsante di Gutenberg) sia il pulsante CTA dell'header — un solo posto per entrambi.
- **Pulsante secondario** (stessi 4 campi): il bottone Gutenberg in stile "solo bordo" (variante "Outline" del blocco Pulsante).
- **Menu mobile** (stessi 4 campi): il pulsante "Menu" dell'header (quello che apre il pannello su mobile/header "Ridotto").
- **Form newsletter (footer)** (stessi 4 campi): il form di iscrizione nel footer (bordo, casella di spunta, bottone "Iscriviti") — indipendente dagli altri colori del footer.

**Importante:** questi colori sono solo un punto di partenza. Dentro ogni pagina, con l'editor a blocchi (Gutenberg), puoi sempre scegliere un colore diverso per un singolo blocco — quella scelta vince sempre su quella impostata qui. Ad esempio, se qui hai messo il testo dei paragrafi grigio scuro ma in una pagina hai una sezione con sfondo scuro, puoi impostare quel singolo paragrafo bianco senza alcun problema, senza toccare le impostazioni generali.

**Bonus:** tutti questi colori (personalizzati o no) compaiono anche come **swatch pronti all'uso** nel selettore colore di ogni blocco Gutenberg (sotto "Predefinito") — non serve ricopiare il codice esadecimale, sono lì selezionabili con un clic per qualunque testo/sfondo di qualunque blocco, in qualunque pagina.

---

## 6. Header: versione e social

**Dove:** *Aspetto → Personalizza → Opzioni → Header*

### Versione header
Tre stili tra cui scegliere:

- **Centrato** *(default)*: logo a sinistra, menu al centro, CTA + icone social a destra.
- **Standard**: logo a sinistra, menu, CTA e icone social raggruppati tutti a destra (nessuna centratura) — il classico header "a due blocchi".
- **Ridotto**: solo logo e un pulsante "Menu" che apre un pannello con dentro tutto il resto. Sempre così, a qualunque dimensione di schermo — utile per un design più minimale.

Sia "Centrato" che "Standard" si riducono da soli sotto gli 1180px, diventando come la versione "Ridotto" (stesso pulsante "Menu").

### Header Flottante
Checkbox indipendente, si può attivare sopra a **qualunque** delle 3 versioni sopra senza cambiare la disposizione di logo/menu/CTA né il comportamento responsive. Quando attivo, l'header si stacca dai bordi (stessa distanza da sinistra, destra e alto, larghezza massima 1440px centrata), con sfondo chiaro e angoli arrotondati come i pulsanti — resta sempre visibile con lo sfondo chiaro, anche sopra una hero (niente più trasparenza in cima alla pagina in questo caso). Con l'header flottante attivo viene sempre usato il logo classico, anche se hai impostato un logo per l'header trasparente.

### CTA (pulsante di contatto)
Testo e link della CTA nell'header si modificano dai campi "Testo pulsante CTA" e "Link pulsante CTA" — stessa logica dei social: se lasci il testo vuoto, la CTA non compare. C'è anche un checkbox per farla aprire in una nuova scheda (le icone social lo fanno sempre di default).

### Aspetto dei pulsanti
CTA e pulsante "Menu" sono colorati (colori diversi tra loro, per distinguerli) con testo in grassetto medio, larghezza minima 100px, e hanno tutti la stessa altezza (CTA, icone social, pulsante Menu). Il raggio degli angoli è condiviso da tutti questi elementi.

Al passaggio del mouse: il testo ruota via mentre un suo duplicato prende il suo posto, e il colore di sfondo cambia in un colore acceso/fluo che scorre da sinistra — un colore per la CTA (e in generale per ogni bottone "pieno", inclusi i bottoni Gutenberg — vedi sezione 5 "Colori" per personalizzarli), uno diverso e dedicato per il pulsante "Menu".

### Sottomenu col click (menu mobile)
**Opzioni → Header → "Le voci con sottomenu si aprono col click"** (attiva di base). Le voci con sottomenu diventano pulsanti con una freccia: nel pannello mobile le sottovoci restano **chiuse** e si aprono toccando la voce (un secondo tocco le richiude); su desktop si aprono anche al passaggio del mouse. Tocchi fuori dal pannello, Esc o un link chiudono il menu. Attenzione: la voce madre non porta più alla sua pagina — aggiungila come prima sottovoce (es. "Vedi tutto"). Se disattivi l'opzione tornano i link normali con le sottovoci sempre visibili su mobile.

### Pulsante "Menu" con hamburger animato
Accanto alla parola "Menu" ci sono tre linee che, a menu aperto, diventano una X mentre il testo diventa "Chiudi".

### Header a tutta larghezza
**Opzioni → Header → "Header a tutta larghezza"**: logo e menu arrivano fino ai bordi della pagina (max 1920px) invece di fermarsi alla larghezza dei contenuti. Si combina con qualunque versione e con il flottante.

### Icone shop (solo con WooCommerce)
**Opzioni → Header → Icone shop**: tre caselle indipendenti — **Account**, **Preferiti**, **Carrello**. Compaiono solo se WooCommerce è installato. Ognuna ha un tooltip al passaggio del mouse (carrello: articoli, quantità, prezzo e subtotale; preferiti: i prodotti salvati con foto e prezzo; account: "Accedi o registrati"). Il carrello e i preferiti mostrano un numero. Sotto i 600px nella barra resta solo il carrello: account e preferiti passano nel pannello del menu.

### Social nell'header
Checkbox "Mostra i social" per decidere se le icone (compilate in *Opzioni → Generale → Social*, vedi sezione 3) compaiono nell'header — indipendente dal checkbox equivalente nel footer, puoi tenerle in uno solo dei due punti se preferisci.

---

## 7. Menu

**Dove:** *Aspetto → Menu*

Il tema ha tre posizioni disponibili:

- **Header**: il menu principale — compare sia nell'header sia, senza sottomenu, come colonna menu del footer (vedi sezione 8).
- **Mobile** *(facoltativa)*: se vuoi un menu diverso apposta per il pannello mobile/header "Ridotto" (ad es. più corto), assegnalo qui. Se non assegni nulla, il pannello mobile continua a mostrare semplicemente lo stesso menu "Header" — nessun rischio, è tutto automatico.
- **Privacy**: posizione predisposta per usi futuri (es. un menu ad hoc per un blocco a parte), al momento non collegata automaticamente a nessuna area del sito.

Per creare un **sottomenu** (voce a tendina): nell'editor del menu trascina una voce leggermente a destra e sotto un'altra — diventa una sua "sotto-voce". Compare in automatico una freccina per far capire che è espandibile (su desktop). Nel footer i sottomenu non compaiono (solo le voci principali).

---

## 8. Footer

**Dove:** *Aspetto → Personalizza → Opzioni → Footer*

Il footer ha **3 stili** selezionabili dal campo "Versione footer", come per l'header:

- **Stile 1**: il più ricco — riga introduttiva (titolo + testo) e colonna contatti affiancate al menu, poi riga con i social e il form di iscrizione alla newsletter, poi il logo del sito a tutta larghezza, poi la riga finale.
- **Stile 2 (editoriale)**: logo + descrizione affiancati in alto, poi tre colonne — menu con voci grandi in grassetto separate da virgole + social, colonna contatti, form newsletter.
- **Stile 3 (compatto)**: una sola riga con logo, menu in linea e social, pensato per siti che non hanno bisogno delle colonne/newsletter degli altri due stili.

Tutti e tre condividono la stessa **riga finale** (copyright + link legali) e leggono gli stessi campi qui sotto.

### Contenuti personalizzabili

- **Mostra i social**: checkbox indipendente da quello dell'header (stessi dati di *Generale → Social*).
- **Colonna testo**: titolo e testo introduttivo del footer (entrambi hanno un default, personalizzabili liberamente).
- **Colonna contatti**: titolo + campo **"Voci"**, una voce per riga (indirizzo, telefono, email, orari...). Email e numeri di telefono vengono riconosciuti automaticamente e resi cliccabili. Puoi anche scrivere un link HTML dentro una voce (es. un indirizzo con link a Google Maps) — la colonna resta nascosta finché il campo è vuoto.
- **Colonna menu**: titolo personalizzabile, usa il menu "Header" (vedi sezione 7).
- **Newsletter**: il footer (Stile 1 e Stile 2) mostra un form di iscrizione via email — **per ora l'invio non è collegato a nessun servizio**, il form è solo l'elemento visivo/funzionante lato browser, va collegato a un servizio esterno (es. Mailchimp) o a un invio email quando serve davvero. Sotto al form c'è una casella di accettazione privacy obbligatoria, il cui testo si scrive nel campo "Testo accettazione privacy" — può contenere anche un link HTML con classi CSS a tua scelta (utile ad esempio per collegare un servizio come Iubenda).
- **Riga finale**: testo di copyright (supporta i segnaposto `{anno}` e `{sito}`), più quattro link — **Privacy Policy**, **Cookie Policy**, **Termini di servizio**, **Credits** — ciascuno con testo, indirizzo e (tranne Credits) un campo facoltativo di classi CSS. Ogni link compare nel footer solo se hai scritto un testo per lui: lasciato vuoto, semplicemente non compare (niente spazio vuoto lasciato lì). La Privacy Policy **non è più legata automaticamente** alla pagina Privacy di WordPress: puoi collegarla a qualsiasi indirizzo, anche esterno (utile per un servizio come Iubenda).

Su tablet/smartphone l'ordine degli elementi cambia (via CSS, gestito in automatico, non serve toccare nulla a mano).

---

## 9. Homepage e Hero

**Dove:** *Impostazioni → Lettura*

La homepage è una **pagina normale** impostata come "pagina statica" (non il blog). Attualmente è la pagina "Home", con diverse sezioni: hero di presentazione, presentazione del progetto, i vantaggi principali, portfolio, chi sono, form di contatto.

La sezione di apertura (hero a tutto schermo con foto, titolo, sottotitolo e pulsante) è semplicemente un **blocco Cover** di Gutenberg all'inizio della pagina — per cambiarla basta modificare la pagina dall'editor a blocchi: cambiare foto, testo, e il link del pulsante.

Nota: l'header riconosce la hero guardando il contenuto della pagina (cerca un blocco Cover con la classe `hero-section`), non serve che sia per forza la homepage — qualsiasi pagina che inizia con quel blocco Cover ottiene lo stesso header trasparente.

---

## 10. Blog

**Dove:** *Aspetto → Personalizza → Opzioni → Blog* per tutte le scelte; *Impostazioni → Lettura* per la pagina e il numero di articoli.

Le stesse regole valgono per la **pagina Blog**, le **categorie**, i **tag**, gli **autori**, gli **archivi per data** e la **ricerca**: stessa struttura, ma con il **nome della sezione in cui sei** in alto (una piccola etichetta — "Categoria", "Tag", "Autore", "Risultati di ricerca" — e sotto il titolo grande, es. "Guide" oppure “parola cercata”).

- **Aside** (colonna a destra con ricerca, categorie col numero di articoli, ultimi articoli e tag): si accende o spegne **separatamente** per l'elenco/archivi/ricerca e per gli articoli. Se in *Aspetto → Widget* l'area "Sidebar Blog" ha dei widget, si usano quelli. Su tablet e mobile l'aside diventa un pannello che entra da destra col pulsante "Filtri e ricerca".
- **Colonne della griglia**: 2, 3 o 4 (3 di base; scendono da sole su tablet e mobile). La distanza tra le card è stretta.
- **Card dell'elenco**: scegli se mostrare data, autore, categorie, tag.
- **Articolo**: scegli data, autore, categorie, tag (i tag stanno a fine articolo con una linea sopra), il **riquadro autore** (avatar, nome e biografia presi dal profilo utente), gli **articoli correlati** ("Continua a leggere", stessa categoria) e i pulsanti **Condividi**.
- **Titolo e testo introduttivo** della pagina Blog: si modificano editando la pagina "Blog".

---

## 10-bis. Shop (WooCommerce)

**Dove:** *Aspetto → Personalizza → Opzioni → Shop* (la sezione compare solo con WooCommerce attivo). Tutti i colori, i font e le dimensioni vengono dalle opzioni del tema: nessun valore nel CSS dello shop è scritto a mano.

- **Filtri**: attivali per la pagina Shop (e, se vuoi, anche per le categorie). Ogni filtro si accende da solo: ricerca con suggerimenti, categoria, prezzo (da/a), attributi (Colore, Materiale… uno per ogni attributo che ha prodotti). L'URL resta condivisibile. Su tablet e mobile i filtri sono un pannello che entra da sinistra.
- **Card prodotto**: cuore dei preferiti, pulsante "Aggiungi al carrello" all'hover, seconda foto all'hover, categorie, descrizione breve.
- **Pagina dei preferiti**: pagina con lo shortcode `[starter_preferiti]` (creata dai contenuti di prova). I preferiti stanno nel browser del visitatore, nessun account.
- **Cassa**: campi facoltativi Partita IVA, Codice fiscale e Codice SDI/PEC per la fattura.
- Tutte le pagine di WooCommerce (shop, categorie, scheda prodotto, carrello, cassa, account) hanno già lo stile del tema.

---

## 10-ter. Footer "Stile 4" e blocchi aggiunti

- **Footer Stile 4** (4 colonne con newsletter, ripreso da Symbolikamente): riusa i campi già presenti (descrizione, contatti, copyright, credits, social, logo chiaro). Campi nuovi, solo per questo stile: **Titolo** e **Shortcode** del form newsletter (serve a stampare il form di Contact Form 7 nell'ultima colonna) e **Immagine dei metodi di pagamento** (facoltativa, per i negozi). Le due colonne menu usano le posizioni "Footer: colonna Link" e "Footer: colonna Riferimenti" (Aspetto → Menu); il titolo della colonna è il nome del menu.
- **Blocchi in più**: Hero con slider, Striscia valori, Ultimi articoli, Prodotti in evidenza (Woo), Instagram, Team, Contatti. Li trovi nella categoria "Starter" quando aggiungi un blocco; la pagina "Blocchi di prova" li mostra tutti.
- **Briciole di pane**: *Opzioni → Generale → Mostra le briciole di pane* (usano Yoast SEO se presente).

---

## 10-quater. Pagine di base, Contact Form 7 e contenuti di prova

- **Strumenti → Contenuti di prova**: crea in un clic Privacy Policy e Cookie Policy (bozze da completare, con la tabella di CookieYes), Preferiti, Blog, un menu con sottomenu, 7 articoli e — con WooCommerce — 12 prodotti finti con foto segnaposto, categorie e attributi. Si può rilanciare senza duplicare e con "Elimina i contenuti di prova" si toglie tutto ciò che è marcato come prova (le pagine Privacy e Cookie restano).
- **Contact Form 7 + Flamingo**: ogni modulo ha da solo un anti-spam invisibile (campo trappola + orario) e due campi nascosti, **pagina** e **pagina-url**, che dicono da quale pagina arriva il messaggio: li vedi in Flamingo e in fondo alla mail. Non serve toccare i form.
- **Spaziature**: classi pronte da scrivere in "Classi CSS aggiuntive" di un blocco — `mt2` (margine sopra 2rem), `mb1`, `pt3`, `pb4`, `p2`… con varianti `-tab` (tablet) e `-sma` (mobile), es. `mt4 mt2-tab mt1-sma`.

---

## 10-quinquies. Moduli (CPT): Portfolio, Immobili e quelli futuri

**Dove:** *Aspetto → Personalizza → Opzioni → Moduli (CPT)*. Un interruttore per ogni modulo, con la sua descrizione.

Un modulo è una funzione facoltativa del tema che porta con sé tutto ciò che serve: il tipo di contenuto (CPT), le categorie, i campi, i blocchi, la scheda e la pagina. **Spento, il suo codice non viene nemmeno caricato**: niente CPT, campi, blocchi, CSS, JS né query in più (l'interruttore sta nelle opzioni del tema, che WordPress legge comunque). Acceso, carica solo ciò che serve alla pagina: il CSS e il JS del modulo partono solo sulle pagine che lo usano.

**Cosa succede quando si accende** (alla richiesta dopo aver pubblicato): gli indirizzi del sito si aggiornano; il modulo crea la sua **pagina** (Portfolio, Immobili) con già dentro il blocco dell'elenco, oppure la ripubblica se l'aveva messa in bozza il tema; compaiono i suoi blocchi nell'editor e le sue opzioni.

**Cosa succede quando si spegne:** le pagine create dal tema per quel modulo tornano **in bozza**, quindi il loro link smette di funzionare (404), come i link delle schede dei progetti o degli immobili. I **contenuti restano nel database** e tornano riaccendendo il modulo. Una pagina fatta a mano che usa il blocco del modulo resta pubblicata ma il blocco non stampa niente.

**Per aggiungere un modulo in futuro:** una voce in `starter_modules()` (`inc/modules.php`) con file, blocchi, pagina e contenuti di prova; l'interruttore compare da solo. I contenuti di prova di ogni modulo acceso si creano da *Strumenti → Contenuti di prova*.

**Siti nuovi:** i moduli partono tutti spenti.

### Immobili

Portato da Di Guida Real Estate con lo stile del tema: colori, font, dimensioni e **pulsanti** sono quelli delle tue opzioni (stessi pulsanti del resto del sito), niente del sito di origine. Include:

- **Tipo di contenuto Immobile** (titolo e immagine; il resto sono campi) e le categorie Tipologia, Contratto, Zona, Consulente ed Evidenza (quest'ultima decide chi compare in Hero home e in Immobili in evidenza).
- **Scheda dell'immobile**: galleria a scorrimento con lightbox, dati in sintesi, punti di forza, caratteristiche e altri dati, planimetria (con download), **tour virtuale 3D** (link o codice iframe, es. Matterport), video, mappa e punti di interesse, consulente e modulo "Richiedi informazioni", "Altri immobili".
- **Scheda in PDF** (pulsante "Scarica scheda immobile"): generata al volo con i colori e il logo del tema, messa in cache.
- **Pagina Immobili** con il blocco **Immobili — Elenco**: riquadro dei filtri come quello dello shop (campi del tema, pulsanti "Cerca immobili" e "Azzera i filtri" grandi in fondo, su mobile un pannello laterale); **ricerca per parola chiave** solo sugli immobili (titolo, indirizzo, descrizioni, etichette, punti di interesse, tipologia, contratto, zona); filtri per tipologia, contratto, zona, prezzo, superficie, locali e bagni; 9 per pagina con paginazione.
- **Blocchi** (nell'editor hanno il prefisso "Immobili —"): *Immobili — Elenco*, *Immobili — In evidenza* e *Immobili — Hero home* (immobile in evidenza con card).
- **Modificabile dal frontend con Starter Live**: nella scheda dell'immobile si cambiano titolo, apertura, prezzo, superficie, locali, camere, bagni, note, piano, altri dati, descrizioni, punti di forza, blocchi, indirizzo, zona, punti di interesse, foto della galleria e planimetria. I numeri si scrivono "puliti" (1500000) e la pagina li formatta da sola.
- **Opzioni → Immobili**: shortcode del modulo "Richiedi informazioni" (creato da solo con Contact Form 7 quando accendi il modulo).
- **Contenuti di prova**: 3 immobili (uno in vendita, uno **in affitto** e uno **all'asta**, con locali, bagni, prezzi, tipologie e zone diverse per provare i filtri), 4 foto e una planimetria neutra, tutti i campi compilati.

---

### Filtri degli elenchi (uno solo, per tutti)
Shop, Immobili e ogni futuro tipo di contenuto con filtri usano **lo stesso blocco**: `CSS/filters.css` (stile) e `initFilters()` in `JS/scripts.js` (pannello laterale su tablet/mobile, "Azzera i filtri", tendina a scelta multipla). **Non si scrive mai un CSS dei filtri per un singolo tipo di contenuto.** Il markup è descritto in testa a `CSS/filters.css` (classi `filters-wrap`, `filters`, `filters__grid`, `filters__field`, `filters__actions`, `filters-toolbar`, `filters-toggle`, `multiselect`).
- Desktop: griglia a 12 colonne, 3 campi per riga; i due pulsanti nell'ultima colonna della loro riga.
- Tablet/mobile (fino a 1024 px): pulsante "Filtri" affiancato all'ordinamento; pannello da sinistra con i pulsanti fissi in fondo e un'ombra sopra, che indica che i filtri continuano.
- Un nuovo modulo carica il foglio con `add_filter( 'starter_needs_filters_css', … )` (esempio in `inc/modules/immobili/immobili.php`).

### Pulsanti, barra annunci e avviso test
- **Opzioni → Pulsanti**: 4 varianti (Pieno 1, Contorno 1, Pieno 2, Contorno 2), ognuna con sfondo, sfondo hover, bordo, bordo hover, testo, testo hover (colori dalla Tavolozza), più arrotondamento dei pulsanti, arrotondamento di campi e riquadri piccoli (`--radius-btn`, `--radius-button`) e maiuscole. Pieno 1 e Contorno 1 sono anche gli stili Pieno e Contorno dei pulsanti di Gutenberg; Pieno 2 e Contorno 2 sono stili in più (`is-style-fill-2`, `is-style-outline-2`; in HTML `.button--fill-2`, `.button--outline-2`). Nel CSS ogni variante riempie le variabili locali `--b-*` (vedi CSS/starter.css vicino a `.button`): non si scrivono colori nei singoli pulsanti. Carattere e dimensione del testo restano in Tipografia e Stile testi (voce Pulsanti).
- **Opzioni → Barra annunci** (`inc/topbar.php`): fascia sopra l'header con testi che scorrono (uno per riga, etichetta in grassetto, separatore, link, velocità, pausa al passaggio, colori dalla Tavolozza). Scorre via con la pagina e l'header fisso la segue; con "riduci animazioni" il testo sta fermo.
- **Avviso "Negozio in test"** (Opzioni → Shop): non è più in cima, ma una fascia gialla in fondo alla pagina, sotto il footer.
- **Bordi del sito** (Opzioni → Generale, subito dopo la larghezza): un solo raggio per card, tab, immagini, riquadri e campi (nessuno, sm, md, lg, xl o un valore in px; di base md = 20 px); imposta `--radius-sm/md/lg/xl/section` e `--radius-button`. I pulsanti hanno il loro in Opzioni → Pulsanti (`--radius-btn`); se non lo scegli, seguono quello generale. Funziona anche dentro i blocchi ACF, perché cambia le variabili e non i singoli blocchi.
- **Stile del pulsante nei blocchi ACF**: ogni link che è un pulsante (heroes, Testo affiancato, Sezione voci, Ultimi articoli, Prodotti, Instagram, Immobili in evidenza) ha sotto un menu "Stile del pulsante" (Predefinito del blocco, Pieno 1/2, Contorno 1/2, e Chiaro sopra le foto). Un nuovo blocco con un pulsante si registra in `starter_button_link_fields()` (inc/acf-blocks.php) e legge lo stile con `starter_button_class()`. Nei filtri e nelle parti non ACF il pulsante principale è Pieno 1 e il secondario (es. Azzera i filtri) è Contorno 1.

## 11. Portfolio

**Dove:** nel menu di WordPress a sinistra, voce **Portfolio** (icona a valigetta) per i progetti; la pagina dell'elenco la crei tu in **Pagine**; due scelte in *Aspetto → Personalizza → Opzioni → Portfolio*.

Il portfolio è un **modulo**: si accende e si spegne da *Opzioni → Moduli (CPT)* (vedi sopra). Spento, i progetti, le categorie e la pagina Portfolio spariscono dal sito (i contenuti restano nel database).

**Non c'è un archivio automatico.** L'elenco dei progetti è una **pagina normale** (es. "Portfolio"): la crei tu, ci metti il titolo, i testi e gli altri blocchi che vuoi, e dove vuoi il blocco **Elenco progetti**. Puoi averne anche più di una (es. un elenco di soli loghi in un'altra pagina).

**Blocco Elenco progetti** (campi del blocco): colonne (2, 3, 4), progetti per pagina, schede delle categorie (**Tutti, Web design…**) con o senza numero di progetti, numero progressivo sulle card, categorie sulle card, e "Mostra solo queste categorie" (facoltativo). Le schede e la paginazione sono link alla stessa pagina (`?categoria=…`, `?pagina=2`): funzionano anche senza JavaScript. Le categorie non hanno pagine proprie.

**Un progetto** si compone così:

- **Titolo**, **immagine in evidenza** (è anche la copertina delle card) e **categorie** (es. Web design, Graphic design, Logo design: si creano da *Portfolio → Categorie portfolio* e servono per le schede dell'elenco).
- **Dettagli del progetto** (campi sotto l'editor): cliente, anno, ruoli e output (separati da virgola), link al progetto online, **video di copertina** (facoltativo: sostituisce l'immagine nella copertina della scheda).
- **Contenuto** con i blocchi: **Progetto — testo** (occhiello tipo "Contesto", titolo, paragrafi), **Progetto — media** (immagini e video in 1, 2 o 3 colonne: per ogni elemento si sceglie *Immagine* o *Video*, con proporzione e didascalia) e **Progetto — crediti** (ruolo e nome dei collaboratori). Si mescolano nell'ordine che serve; vanno bene anche i blocchi normali di Gutenberg e gli altri blocchi del tema.
- In fondo, **"Altri progetti"** (stessa categoria, poi i più recenti) con il pulsante "Tutti i progetti" verso la pagina con l'elenco.

**Opzioni → Portfolio** (compare con il modulo acceso): dati del progetto nella testata (cliente, anno, ruoli, output) e "Altri progetti". Colori e font sono quelli del tema.

**Contenuti di prova:** *Strumenti → Contenuti di prova* crea la pagina **Portfolio** (intestazione + blocco Elenco progetti), 3 schede complete con strutture diverse (identità visiva, sito web con video, logo e grafica) e 6 progetti brevi, con immagini segnaposto e i video di esempio del tema. "Elimina" toglie i progetti di prova (la pagina resta, come la pagina Blog).

**Da fare in seguito:** filtro senza ricaricare la pagina, altri layout della griglia, grafica delle schede.

---

## 12. Pagine normali

Le pagine (diverse dagli articoli del blog) non mostrano un titolo automatico in cima — questo per lasciare libertà di costruire l'intestazione con i blocchi che preferisci (es. una hero, o niente del tutto). Gli **articoli** invece mostrano titolo, data, autore e categoria in automatico.

---

## 13. Blocchi personalizzati (custom block ACF)

Per creare un nuovo blocco personalizzato con campi ACF:

1. Vai nella cartella `template-parts/`.
2. Duplica la cartella `example-block` e rinominala (es. `banner-promo`).
3. Modifica `block.json` (nome, titolo) e `render.php` (cosa stampa il blocco).
4. In ACF crea un gruppo di campi con posizione "Blocco → [nome del tuo blocco]".

Il blocco viene registrato in automatico — non serve toccare `functions.php`. I campi dei blocchi del tema stanno in `inc/acf-blocks.php` (base) e `inc/acf-blocks-extra.php` (hero con slider, striscia valori, ultimi articoli, prodotti, Instagram, team, contatti).

---

## 14. Dove scrivere il CSS

Attenzione: ci sono **due file CSS diversi**, con scopi diversi:

- `style.css` (nella cartella principale): **non toccarlo per lo stile**. Contiene solo i dati obbligatori del tema per WordPress (nome, versione, ecc.).
- `CSS/starter.css`: **questo è il file su cui lavorare**. Tutto lo stile del sito va qui.
- `CSS/gutenberg.css`: stili visibili *solo* dentro l'editor a blocchi di WordPress (per far vedere i blocchi come appariranno nel sito mentre li si modifica).

I colori e i font impostati da *Personalizza → Opzioni → Tipografia/Colori* (sezioni 4 e 5) non vengono scritti dentro questi file: sono generati automaticamente e aggiunti alla pagina solo se effettivamente configurati, senza bisogno di toccare il CSS a mano.

---

## 15. Cosa manca ancora (prossimi passi)

Cose pianificate ma non ancora costruite:

- **Invio reale della newsletter/form di contatto**: sia il form di contatto in homepage sia il form newsletter nel footer sono solo l'elemento visivo/funzionante lato browser — non inviano ancora email o dati a un servizio esterno.
- **Pagine "Termini di servizio" e "Credits"**: i link nel footer esistono e sono personalizzabili, ma le pagine di destinazione vanno create e collegate per ogni progetto.
- **Mega menu**: nel menu principale, un sottomenu con più colonne/contenuti ricchi invece della semplice tendina attuale.

Quando queste funzioni verranno aggiunte, questa guida verrà aggiornata di conseguenza.

---

## Appendice tecnica (per chi mette mano al codice)

Riferimenti rapidi ai punti principali in `functions.php`:

- `starter_setup()` — supporti del tema, menu, textdomain.
- `starter_customize_register()` — tutte le impostazioni del Customizer (Generale, Tipografia, Colori, Header, Footer).
- `starter_get_social_links()` — calcola l'elenco delle icone social da mostrare (usata in `header.php` e nei template footer).
- `starter_is_minimal_header()` / classe `body.header-minimal` — determina quale versione di header è attiva.
- `starter_has_dark_logo()` — determina se mostrare il logo alternativo.
- `starter_get_footer_contacts()` — legge il campo "Voci" della colonna contatti del footer e riconosce automaticamente email/telefono.
- `starter_footer_style_choices()` — stili di footer disponibili; per aggiungerne uno nuovo basta un file `template-parts/footer/footer-style-N.php` + una voce qui.
- `starter_get_active_typography_fonts()` / `starter_typography_css()` — leggono gli slot font attivi e generano il CSS corrispondente (Tipografia).
- `starter_colors_css()` — genera il CSS per i colori impostati (Colori).
- `starter_default_palette()` / `starter_color_field_defaults()` / `starter_color_ref()` — 5 colori di base del tema (stessi valori di `CSS/starter.css`) pronti come "Colori con nome" e collegati ai relativi campi, invece di partire vuoti.
- `starter_extend_editor_color_palette()` — sincronizza i "Colori con nome" nella palette di `theme.json`, così il selettore colore di Gutenberg mostra sempre i colori realmente in uso sul sito.
- `starter_login_logo()` — sostituisce il logo di WordPress nella pagina di login con quello del sito.
- `starter_excerpt_chars()` — estratto degli articoli troncato a un numero di caratteri (attualmente 140, usato in `home.php`).
- `starter_widgets_init()` — registra l'area widget `sidebar-blog`.
- `starter_register_portfolio_cpt()` — registra il Custom Post Type `portfolio` e le tassonomie `portfolio_category` / `portfolio_client_type`.
- `starter_body_class_has_hero()` — aggiunge la classe `has-hero` al `<body>` cercando `hero-section` nel contenuto della pagina corrente (non è legata alla homepage).
- `starter_theme_register_acf_blocks()` — legge `template-parts/*/block.json` e registra ogni blocco trovato.
- `starter_has_content_block()` — vero se la pagina usa almeno uno dei 6 blocchi riutilizzabili elencati sotto (usata da `starter_css_buckets()` per caricarne il CSS solo dove serve).

### Blocchi ACF riutilizzabili (richiedono ACF Pro)

In `template-parts/` ci sono 6 blocchi ACF pronti all'uso (categoria "Starter" nell'inserimento blocchi), pensati per qualunque progetto, non solo per contenuti specifici:

- **Testo affiancato** (`testo-affiancato`) — testo + fino a 2 foto composte, lato configurabile, checklist, citazione, link, sfondo chiaro opzionale.
- **Sezione voci** (`sezione-voci`) — titolo + elenco voci in 4 layout: Elenco numerato (con testo a fianco), Card classiche con testo, Card strette con icona, Card numerate.
- **Elenco servizi** (`elenco-servizi`) — menu laterale che segue lo scroll (su tablet e telefono diventa una barra di icone fissa in basso) + una scheda per servizio: ancora, icona, occhiello, titolo, etichetta del menu, testo, foto, elenco con spunte, pulsante con scelta dello stile. Portato da Di Guida Real Estate. Il layout "Elenco numerato" della Sezione voci è il blocco "cinque ragioni" di Di Guida.
- **Slider** (`slider`) — slider singolo o carosello di immagini e video (campo gallery; didascalie dalla Libreria media). Si sceglie: tipo, larghezza (nella misura del sito o a tutto schermo), quante immagini vedere su desktop / tablet (fino a 1100px) / mobile (fino a 640px), distanza tra le immagini, anteprima delle slide vicine (quanti px si intravedono), proporzione, frecce (sull'immagine ai lati, sopra o sotto) con stile a scelta tra i 4 pulsanti, paginazione (puntini con quello attivo allungato, contatore, barra) centrata, scorrimento automatico, ricomincio dall'inizio, didascalie e angoli. Scorre con frecce, puntini, tastiera, dito e trascinamento col mouse. Esempi in sei progetti del Portfolio.
- **Recensioni** (`recensioni`) — carosello di testimonianze a 5 stelle, scorrevole con le frecce o trascinando col mouse, loop infinito.
- **Mappa** (`mappa`) — mappa Google a tutta larghezza con pulsante indicazioni, dato solo l'indirizzo.
- **Intestazione pagina** (`intestazione-pagina`) — occhiello + H1 + paragrafo, con briciole di pane automatiche.
- **Due riquadri** (`missione-visione`) — 2+ riquadri affiancati, il secondo evidenziato con il colore d'accento.

Campi di ogni blocco registrati via `acf_add_local_field_group()` in `inc/acf-blocks.php` (nessun `acf-json/` da sincronizzare: aggiungere un campo è modificare quell'array). Funzioni di supporto condivise in `inc/block-helpers.php` (`starter_field()`, `starter_link_attrs()`, `starter_flip_text()`, `starter_section_heading_tags()`, `starter_page_breadcrumb()`...) e icone SVG in `inc/icons.php`. CSS in `CSS/starter.css` (sezione "Blocchi ACF riutilizzabili"), variabili condivise (`--color-line`, `--radius-md`, `--spacing-section`...) nel blocco `:root` insieme alle altre.

**Pulsante WhatsApp**: `template-parts/footer/whatsapp-button.php`, mostrato in ogni pagina se il campo "Link WhatsApp" in *Opzioni tema* (pagina ACF, `inc/acf-options.php`) è compilato; diventa rotondo quando il footer entra in vista (`initWhatsappCompact()` in `JS/scripts.js`).

**Lightbox generica**: markup in `footer.php` (`#lightbox`), comportamento in `JS/scripts.js` (`initLightbox()`). Nessun blocco la usa ancora di default — una futura galleria basta che aggiunga alle miniature `data-lightbox-index`/`data-full`/`data-alt`/`data-caption` per attivarla, senza toccare JS.

**Multiselect** (dropdown a scelta multipla) descritto nella guida precedente sul tema Di Guida non è stato portato: nessun blocco qui lo userebbe ancora. Se in un progetto serve un filtro a scelta multipla, prendi `initMultiselect()` da `JS/scripts.js` del tema Di Guida Real Estate come riferimento.

File principali: `header.php`, `footer.php` (solo un loader, carica `template-parts/footer/footer-style-{N}.php`), `home.php` (template del blog), `sidebar-blog.php`, `page.php`, `single.php`, `404.php`, `index.php`, `CSS/starter.css`, `JS/scripts.js`, `JS/animations.js` (preloader + scroll fluido, caricato solo se attivi), `JS/customize-controls.js` (solo per il pannello admin di Personalizza — gestisce gli "Aggiungi.../Rimuovi..." di icone social, font e varianti), `theme.json` (palette per l'editor a blocchi, sincronizzata da `starter_extend_editor_color_palette()`), `inc/performance.php` (WebP automatico, eccetto il favicon che resta sempre .png; preload dei font locali dichiarati in `theme.json`; pulizia head), `inc/media-modern.php` (AVIF, `<picture>`, video WebM, favicon .ico e icone), `inc/media-optimizer.php` (pagina Media → Ottimizza immagini e comandi WP-CLI), `inc/assets-optimizer.php` (unione CSS/JS e compressione HTML), `inc/icons.php`, `inc/block-helpers.php`, `inc/acf-options.php`, `inc/acf-blocks.php` (blocchi riutilizzabili, vedi sopra).

Build CSS/JS (opzionale): `npm install && npm run build` (vedi `tools/build.js`) divide `CSS/starter.css` per tipo di pagina e minifica CSS/JS in `CSS/dist/` e `JS/dist/`; `starter_dist_ready()` in `functions.php` li usa in automatico solo se più recenti dei sorgenti, altrimenti carica i file normali — nessun passo obbligatorio per continuare a sviluppare senza Node.

GSAP e Lenis vengono caricati da CDN (cdnjs/jsdelivr) tramite `starter_enqueue_animation_assets()` in `functions.php`, con versione fissata nel codice. Gli script/CSS specifici del pannello Personalizza (`JS/customize-controls.js`) usano invece la data di modifica del file come versione, per evitare problemi di cache del browser durante lo sviluppo.

## Aggiornamento: header, ricerca, blog, footer, pagine standard

**Header (Opzioni → Header).** Versioni in ordine: *Standard* (logo a sinistra, menu e resto a destra), *Centrato*, *Ridotto*. *Flottante* e *A tutta larghezza* si combinano con tutte: flottante + tutta larghezza = card che occupa la pagina e, scrollando, si attacca in alto. *Stile delle icone*: con bordo e animazione (come i social), semplici, semplici e vicine; vale per social e icone. *Ordine*: prima i social o prima le icone, con divisore facoltativo. Col menu ridotto (sotto 1180px o versione Ridotto) i social stanno solo nel pannello. *Logo scrollando*: uguale, più piccolo, oppure un'icona caricata da te.

**Ricerca.** Lente nell'header (si spegne da Opzioni → Header): pannello con anteprima divisa in Prodotti (solo con WooCommerce), Pagine, Articoli. Endpoint `/wp-json/starter/v1/search`; codice in `inc/search.php` e `initSearchPanel` in `JS/scripts.js`.

**Blog (Opzioni → Blog).** Colonne 1–4 (1 = elenco con foto a sinistra e testo a destra), *Ultimo articolo in evidenza* (a tutta larghezza, solo prima pagina), tag sotto il testo prima di "Leggi tutto", categorie come etichette a destra della data.

**Footer.** *Spazio libero* (Opzioni → Footer) uguale in tutti e 4 gli stili: titolo, sottotitolo e contenuto (shortcode o HTML); vuoto = niente. Il modulo "Newsletter" (nome, email, privacy) è un Contact Form 7 creato da Strumenti → Contenuti di prova. I link Privacy e Cookie del footer puntano alle pagine create dal tema.

**WhatsApp** è in Opzioni → Generale (non più in una pagina ACF).

**Testi.** Tutte le dimensioni sono `clamp()` in `theme.json` (con XXL), già compilate in Opzioni → Stile testi (solo Desktop); `CSS/starter.css` le applica di base a h1–h6, `.title-*`, `.txt`.

**Pagine standard** (Strumenti → Contenuti di prova): Home, Chi siamo, Servizi, Contatti in `inc/starter-pages.php`, con i blocchi ACF (anche i nuovi *Hero testo + foto* e *Sezione affiancata*) e sezioni in blocchi Gruppo. **CookieYes in italiano**: `inc/cookieyes.php`. **Immobiliare**: vedi `docs/RICETTA-IMMOBILIARE.md`.

## Prestazioni (compressione, cache, immagini)

**Dove:** *Aspetto → Personalizza → Opzioni → Prestazioni*. Il sito è pensato per essere veloce e piacere ai motori di ricerca (Core Web Vitals) senza plugin.

**Sempre attivo, senza opzioni** (`inc/performance.php`):
- **Immagini in WebP** (qualità 80): sia le misure ridotte sia l'originale caricato (il JPG/PNG viene sostituito dal WebP). Restano nel formato originale gli SVG, il favicon (qualunque file con `favicon` nel nome o l'icona del sito), il logo delle email di WooCommerce e i file con `email` o `nowebp` nel nome, in tutte le loro misure. Le immagini già presenti si sistemano da *Media → Ottimizza immagini* (vedi sotto).
- **Script in `defer`** (script del tema, GSAP, Lenis, animazioni) e `decoding="async"` sulle immagini.
- **Collegamento anticipato** (`preconnect`) a Google Fonts e alle librerie esterne solo se usate; font locali di `theme.json` precaricati.
- **CSS e JS minificati e divisi per tipo di pagina** (`npm run build` → `CSS/dist`, `JS/dist`).
- **Head pulito**: niente emoji, oEmbed, feed, shortlink, `classic-theme-styles`, `wp-embed`; CSS dei blocchi core caricato solo per i blocchi usati.

**Con una casella in Opzioni → Prestazioni** (tutte accese di base, ognuna con la sua descrizione):
- **Google Fonts serviti dal tuo sito** (`inc/fonts-local.php`): i font scelti in Tipografia (Font 1 e 2, più il serif della scritta grande del footer Stile 1) vengono scaricati una volta dai server di Google in `uploads/starter-fonts` (solo "latin" e "latin-ext", WOFF2), scritti in linea con `font-display: swap` e il file latin viene precaricato. Niente richieste a Google, più veloce e più in regola con la privacy. Si aggiorna quando salvi il Personalizza (o entrando in amministrazione, un tentativo l'ora); se il download fallisce restano i Google Fonts come prima. Per i font statici (es. Ubuntu) scarica i pesi 300/400/500/700 disponibili.
- **Compressione e cache del browser (.htaccess)**: il tema scrive da solo, tra i marcatori `STARTER_PERFORMANCE`, le regole per comprimere i file di testo (Brotli e Gzip), tipi dei file moderni (webp, avif, woff2, mp4) e cache di un anno per CSS, JS, immagini, font e video (l'HTML non viene messo in cache). Funziona su Apache e LiteSpeed (es. Hostinger); su nginx è ignorato. Non tocca le regole di WordPress né quelle di altri plugin; spegnendo la casella le toglie. Le regole si applicano al salvataggio del Personalizza, all'attivazione del tema e, una volta, entrando in amministrazione.
- **Immagini AVIF (con ripiego WebP)** (`inc/media-modern.php`): accanto a ogni immagine (originale e misure) il tema crea un file `.avif` con lo stesso nome, a qualità 70 (regolabile 40–90), e lo tiene solo se pesa almeno il 3% meno del WebP. Nella pagina ogni `<img>` diventa `<picture><source type="image/avif"><img webp></picture>`: il browser sceglie da solo (AVIF se lo legge, altrimenti WebP). Vale per i blocchi del tema, per i blocchi immagine di Gutenberg, per WooCommerce e per i loghi; il preload della hero usa l'AVIF. Non si carica nulla a mano: si caricano JPG/PNG come sempre. Serve PHP con GD+AVIF (verifica in *Media → Ottimizza immagini*); se manca, resta il WebP. Gli .avif si cancellano insieme all'immagine.
- **Video WebM (con ripiego mp4)**: carica in Libreria media un `.webm` con lo **stesso nome** dell'mp4 (es. `film.mp4` e `film.webm`, il WebM va esportato a mano con HandBrake/ffmpeg). Nei blocchi del tema (hero con video, hero media, slider, progetti) e nel blocco Video/Copertina di Gutenberg il tema scrive prima la sorgente WebM e poi l'mp4. Il campo del blocco resta quello dell'mp4. Se il file gemello non c'è, tutto funziona come prima.
- **Favicon .ico e icone**: dall'icona del sito (*Identità del sito*, 512×512) il tema genera in `uploads/starter-icons` il `favicon.ico` (16, 32, 48 e 256 px), `icon-192.png` e `icon-512.png` (Google e Android), `apple-touch-icon.png` (180 px) e `site.webmanifest`, e li mette nell'`<head>` al posto delle icone di WordPress. Anche `/favicon.ico` alla radice del sito risponde con il file generato. Il file caricato resta com'è (PNG).
- **Unisci CSS e JS in un solo file** (`inc/assets-optimizer.php`, spenta di base): nella pagina finale unisce i `<link rel="stylesheet">` e gli `<script src>` locali che stanno uno di seguito all'altro, nello stesso ordine, in un unico file compresso (in `uploads/starter-cache`, si ricrea da solo). I dati `wp_localize_script` passano prima del file; qualunque elemento in mezzo (script o stile inline, file esterni come Google Fonts, `defer` diverso) interrompe la serie, quindi l'ordine non cambia mai. Non unisce file con `@import` o che usano `document.currentScript`. Si può escludere un file con il filtro `starter_bundle_exclude` o l'attributo `data-no-bundle`. Per vedere la pagina senza compressione (da amministratore): `?starter-raw=1`.
- **Comprimi il codice HTML** (spenta di base): toglie commenti, a capo e spazi doppi; lascia intatti `<pre>` e `<textarea>`; comprime `<style>` e JSON-LD; gli script inline li porta su una riga solo se il codice non dipende dagli a capo (lo legge davvero: stringhe, regex, commenti; ogni caso ambiguo resta com'era); il risultato è una pagina senza nemmeno un a capo. Non cambia ciò che si vede (verificato confrontando tag e testo prima/dopo).
- **Precarica la foto di apertura (hero)**: se la pagina comincia con una hero con foto (slider, video con copertina, foto + testo), il browser la scarica subito con la misura giusta.
- **Contact Form 7 solo dove c'è un modulo**: niente script e stile dei moduli nelle altre pagine (restano ovunque se il footer ha il form newsletter).
- **Togli jQuery Migrate**.

**Libreria media già esistente** — *Media → Ottimizza immagini*: crea gli AVIF mancanti (a lotti, con barra di avanzamento; "Rifai anche quelli già creati" dopo aver cambiato la qualità) e converte in WebP i JPG/PNG rimasti aggiornando gli indirizzi in articoli, campi personalizzati e opzioni (fare prima un backup di database e uploads). Stesso lavoro da riga di comando: `wp starter media status|avif [--force]|webp`.

**Da fare lato server/hosting** (non dal tema): PHP aggiornato, cache di pagina del provider (su Hostinger LiteSpeed Cache o la cache integrata) e CDN se serve. Verifica con PageSpeed Insights e controllando che la risposta abbia l'header `content-encoding: br` o `gzip`.


## Tema figlio e aggiornamenti da GitHub

**Tema figlio** (cartella `starter-theme-child/`, accanto al tema): è il punto di partenza per ogni sito. Il padre (`starter-theme`) resta sempre com'è e si aggiorna; le modifiche del sito stanno nel figlio e gli aggiornamenti non le toccano.

- **Come si usa su un nuovo sito**: copia `starter-theme-child/` in `wp-content/themes/` accanto a `starter-theme/`, (facoltativo) cambia `Theme Name` e il nome della cartella, poi *Aspetto → Temi → Attiva* sul figlio. Nessun file del figlio dipende dal nome della cartella.
- **Le opzioni** (*Aspetto → Personalizza → Opzioni*, colori, font, header, footer, moduli...) funzionano uguali con il figlio attivo. WordPress le salva per ogni tema attivo, quindi alla prima attivazione il figlio **copia quelle del padre** (dove il figlio ha già un valore, vince il suo). Da lì in poi si modificano dal figlio; il padre non viene più toccato.
- **Il tuo CSS** va in `style.css` del figlio: si carica dopo tutti i CSS del padre (anche con "Unisci CSS e JS" acceso), quindi le sue regole vincono. Lo stesso CSS vale nell'editor dei blocchi.
- **Modificare un template**: copia il file dal padre nel figlio con lo stesso percorso (`header.php`, `page.php`, `template-parts/content/card.php`, `woocommerce/...`) e cambialo lì; WordPress usa quello del figlio. Un blocco ACF del figlio (cartella `template-parts/nome/` con `block.json`) sostituisce quello del padre con lo stesso nome.
- **Le funzioni `starter_*` del padre non si ridefiniscono** dal figlio: si usano gli hook (`add_action`, `add_filter`) in `functions.php` del figlio.

**Aggiornamenti da GitHub** (`inc/updater.php`): il tema guarda l'ultima *Release* del repository e, se la versione è più alta di quella installata, WordPress mostra la notifica classica (Bacheca → Aggiornamenti, pagina Temi) con il pulsante "Aggiorna". Si aggiorna sempre il padre.
- **Si accende** indicando il repository (`utente/repo`) nella costante `STARTER_UPDATE_REPO` (in `inc/updater.php`, oppure in `wp-config.php` per un sito particolare). Vuota = nessun controllo.
- **Versione**: la release ha il tag `v1.2.0`; il numero deve coincidere con `Version:` in `style.css` del padre. Pacchetto: il file `starter-theme*.zip` allegato alla release (con dentro la cartella `starter-theme/`); se manca si usa lo zip automatico di GitHub, rinominando la cartella.
- **Controllo**: ogni 6 ore; "Controlla di nuovo" in Bacheca → Aggiornamenti interroga subito GitHub. I pacchetti si accettano solo da github.com.
- **Repository privato**: definire in `wp-config.php` `define( 'STARTER_GITHUB_TOKEN', '...' )` con un token di sola lettura.
