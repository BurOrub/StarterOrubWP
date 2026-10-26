# Commenti del tema — riepilogo

Indice di tutti i commenti che spiegano **cosa fa** ogni parte del tema: le opzioni del Customizer, i file PHP, i CSS e il JavaScript. È generato leggendo i commenti nei file (non è scritto a mano): per correggere una spiegazione si modifica il commento nel file e poi si rigenera questo elenco (basta chiederlo a Claude Code).

Regole di scrittura dei commenti nel tema: in italiano, una riga che dice *cosa* e *perché*, mai il *come* ovvio; i campi nuovi spiegano **a cosa servono** (es. Footer Stile 4: newsletter, pagamenti).


## 1. Opzioni del Customizer (Aspetto → Personalizza)

Le voci del pannello **Opzioni** e i campi del tema in "Identità del sito". Per ogni campo: etichetta e spiegazione che compare nel pannello.

### Denominazione del sito (`title_tagline`)

- **Attiva il logo per l'header trasparente sulla hero** — Sostituisce il logo finché l'header è trasparente sopra la hero.
- **Logo per l'header trasparente**

### Generale (`starter_options_general`)


**Commenti**

- **Disattiva i commenti su tutto il sito** — Toglie commenti e pingback da tutto il sito.

**Layout**

- **Larghezza contenuto (px)** — Larghezza massima dei contenuti (default 1280). Le sezioni "wide" sono +200px.

**Bordi del sito**

- **Angoli di card, tab, immagini e riquadri** — Vale per tutto il sito: tab di immobili, portfolio e shop, card, immagini, riquadri e campi dei moduli, anche dentro i blocchi con campi personalizzati. Se i pulsanti non hanno un arrotondamento loro, lo seguono.
- **Il tuo valore (px)** — Si usa con "Valore mio (px)". Da 0 a 80.

**Animazioni**

- **Caricamento delle pagine**
- **Cosa mostrare**
- **Testo**
- **Sfondo**
- **Testo**
- **Logo nel colore del testo**
- **Scroll fluido** — Scroll più morbido (Lenis). Si spegne con "riduci animazioni".

**SVG**

- **Inserisci gli SVG come vettori** — Spento: un .svg è un'immagine normale. Acceso: entra nell'HTML ed è colorabile via CSS.

**Paginazione**

- **Allineamento**

**Social**

- **Social: Instagram** — Lascia vuoto per non mostrare questa icona.
- **Social: LinkedIn** — Lascia vuoto per non mostrare questa icona.
- **Social: Facebook** — Lascia vuoto per non mostrare questa icona.
- **Social: X (Twitter)** — Lascia vuoto per non mostrare questa icona.
- **Social: YouTube** — Lascia vuoto per non mostrare questa icona.
- **Social: TikTok** — Lascia vuoto per non mostrare questa icona.
- **Social: Substack** — Lascia vuoto per non mostrare questa icona.
- **Icona personalizzata 1 — file SVG** — Letto come codice: si colora via CSS.
- **Icona personalizzata 1 — link**
- **Icona personalizzata 2 — file SVG** — Letto come codice: si colora via CSS.
- **Icona personalizzata 2 — link**
- **Icona personalizzata 3 — file SVG** — Letto come codice: si colora via CSS.
- **Icona personalizzata 3 — link**
- **Icona personalizzata 4 — file SVG** — Letto come codice: si colora via CSS.
- **Icona personalizzata 4 — link**
- **Icona personalizzata 5 — file SVG** — Letto come codice: si colora via CSS.
- **Icona personalizzata 5 — link**
- ****

**Briciole di pane**

- **Mostra le briciole di pane** — Percorso Home / pagina, tranne in home.
- **Stile delle briciole** — Vale per tutte le pagine, blog e shop compresi.

**WhatsApp**

- **Link WhatsApp** — Es. https://wa.me/393331234567. Vuoto = nessun pulsante.
- **Testo del pulsante** — Vuoto = "Scrivici su WhatsApp".

### Tipografia (`starter_options_typography`)


**Font 1**

- **Font** — Finché resta su "— Seleziona Font —" questo font non viene caricato sul sito.
- **Applica a**

**Font 2**

- **Font** — Finché resta su "— Seleziona Font —" questo font non viene caricato sul sito.
- **Applica a**

**Font 3 — caricato da te**

- ****
- **Nome del font** — Nome a tua scelta. Vuoto = font non caricato.
- **Variante 1 — peso**
- **Variante 1 — stile**
- **File TTF**
- **File WOFF**
- **File WOFF2**
- **File EOT**
- **File SVG**
- ****
- **Variante 2 — peso**
- **Variante 2 — stile**
- **File TTF**
- **File WOFF**
- **File WOFF2**
- **File EOT**
- **File SVG**
- ****
- **Variante 3 — peso**
- **Variante 3 — stile**
- **File TTF**
- **File WOFF**
- **File WOFF2**
- **File EOT**
- **File SVG**
- ****
- **Variante 4 — peso**
- **Variante 4 — stile**
- **File TTF**
- **File WOFF**
- **File WOFF2**
- **File EOT**
- **File SVG**
- ****
- **Applica a**
- ****

**Font 4 — caricato da te**

- ****
- **Nome del font** — Nome a tua scelta. Vuoto = font non caricato.
- **Variante 1 — peso**
- **Variante 1 — stile**
- **File TTF**
- **File WOFF**
- **File WOFF2**
- **File EOT**
- **File SVG**
- ****
- **Variante 2 — peso**
- **Variante 2 — stile**
- **File TTF**
- **File WOFF**
- **File WOFF2**
- **File EOT**
- **File SVG**
- ****
- **Variante 3 — peso**
- **Variante 3 — stile**
- **File TTF**
- **File WOFF**
- **File WOFF2**
- **File EOT**
- **File SVG**
- ****
- **Variante 4 — peso**
- **Variante 4 — stile**
- **File TTF**
- **File WOFF**
- **File WOFF2**
- **File EOT**
- **File SVG**
- ****
- **Applica a**
- ****

### Stile testi (`starter_options_text_style`)

Già compilate con clamp(): si adattano da sole a ogni schermo. Tablet e Mobile vuoti = come Desktop. Svuota un campo per tornare al valore standard.


**Titolo grande hero (.title-display)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**

**H1 (h1, .title-h1)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**
- **margin-bottom**

**H2 (h2, .title-h2)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**
- **margin-bottom**

**H3 (h3, .title-h3)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**
- **margin-bottom**

**H4 (h4, .title-h4)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**
- **margin-bottom**

**H5 (h5, .title-h5)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**
- **margin-bottom**

**H6 (h6, .title-h6)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**
- **margin-bottom**

**Paragrafo (.txt)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**
- **margin-bottom**

**Dimensione XXL (.has-xx-large-font-size)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**

**Dimensione XL (.has-x-large-font-size)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**

**Dimensione grande (.has-large-font-size)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**

**Dimensione media (.has-medium-font-size)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**

**Dimensione piccola (.has-small-font-size)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**

**Pulsanti (.button, .wp-block-button__link)**

- **font-size**
- **line-height**
- **letter-spacing**
- **font-weight**
- **text-transform: uppercase**

### Colori (`starter_options_colors`)

Tutti i colori del sito, in 4 gruppi che si aprono con un click. Parti dalla Tavolozza: i colori che definisci lì (con il loro nome) si scelgono poi negli altri gruppi e in Opzioni → Pulsanti; cambiando un colore nella Tavolozza cambia ovunque sia usato. Un colore scelto su un blocco in Gutenberg vince su questi.


**Tavolozza**

- **Colore 1 · nome**
- **Colore**
- **Colore 2 · nome**
- **Colore**
- **Colore 3 · nome**
- **Colore**
- **Colore 4 · nome**
- **Colore**
- **Colore 5 · nome**
- **Colore**
- **Colore 6 · nome**
- **Colore**
- ****
- **Colore 7 · nome**
- **Colore**
- ****
- **Colore 8 · nome**
- **Colore**
- ****
- **Colore 9 · nome**
- **Colore**
- ****
- **Colore 10 · nome**
- **Colore**
- ****
- ****

**Sfondi, testi e link**

- **Sfondo del sito** — Vuoto = resta il colore di base del tema.
- **Testo** — Vuoto = resta il colore di base del tema.

**Colore principale**

- **Colore**
- **Applica a**

**Colore secondario**

- **Colore**
- **Applica a**

**Colore link**

- **Colore** — Vuoto = resta il colore principale del tema.
- **Colore hover** — Vuoto = nessun cambio colore al passaggio del mouse.
- **Sottolineatura**
- **Applica a**

**Menu, footer e social**


**Menu mobile**

- **Colore**
- **Colore hover**
- **Colore testo**
- **Colore testo hover**

**Form newsletter (footer)**

- **Colore**
- **Colore hover**
- **Colore testo**
- **Colore testo hover**

**Footer**

- **Sfondo footer**
- **Colore testo footer** — Si applica al testo generico, ai link legali (Privacy/Cookie/Termini/Credits), a eventuali link nei contatti e alle voci del menu di navigazione.
- **Colore hover dei link** — Stesso gruppo di link del campo precedente, in hover.
- **Colore pagina corrente**

**Icone social**

- **Colore** — Vuoto = segue il colore del contesto (bianco sopra una hero trasparente, scuro altrove), come sempre.
- **Colore hover**
- **Colore testo hover**

**Tag e categorie**


**Tag e categorie**

- **Colore** — Bordo e testo a riposo.
- **Colore hover** — Sfondo pieno al passaggio del mouse.
- **Mantieni lo stesso colore del bordo anche in hover** — Spento: in hover il bordo prende "Colore hover". Acceso: resta com'è.

### Pulsanti (`starter_options_buttons`)

- **Arrotondamento dei pulsanti** — Vale per tutti i pulsanti del sito, anche quelli di Gutenberg, dei blocchi e dell'header.
- **Il tuo valore (px)** — Si usa con "Valore mio (px)". Da 0 a 999 (999 = a pillola).
- **Testo dei pulsanti in maiuscolo**
- **Dimensione dei pulsanti**
- **Padding verticale (px)**
- **Padding orizzontale (px)**

**Pieno 1**

- **Sfondo**
- **Sfondo al passaggio del mouse**
- **Testo**
- **Testo al passaggio del mouse**
- **Bordo (vuoto = come lo sfondo)**
- **Bordo al passaggio (vuoto = come lo sfondo hover)**

**Contorno 1**

- **Bordo**
- **Sfondo al passaggio del mouse**
- **Testo**
- **Testo al passaggio del mouse**
- **Sfondo a riposo (vuoto = trasparente)**
- **Bordo al passaggio (vuoto = come il testo hover)**

**Pieno 2**

- **Sfondo**
- **Sfondo al passaggio del mouse**
- **Testo**
- **Testo al passaggio del mouse**
- **Bordo (vuoto = come lo sfondo)**
- **Bordo al passaggio (vuoto = come lo sfondo hover)**

**Contorno 2**

- **Bordo**
- **Sfondo al passaggio del mouse**
- **Testo**
- **Testo al passaggio del mouse**
- **Sfondo a riposo (vuoto = trasparente)**
- **Bordo al passaggio (vuoto = come il testo hover)**

### Header (`starter_options_header`)

- **Mostra i social** — Social in Generale → Social. Qui: mostrarli o no.
- **Versione header** — Standard: logo a sinistra, menu e resto a destra. Centrato: menu al centro. Ridotto: solo il pulsante "Menu". Pillole: senza sfondo, sempre visibile, con menu e pulsante in due pillole a destra.
- **Header Flottante** — Card staccata dai bordi, con angoli arrotondati. Scrollando si attacca in alto.
- **Testo pulsante CTA** — Lascia vuoto per non mostrare la CTA nell'header.
- **Link pulsante CTA**
- **Apri la CTA in una nuova scheda** — Le icone social si aprono sempre in una nuova scheda; per la CTA puoi scegliere.
- **Header a tutta larghezza** — Fino a 1920px. Con "Flottante" anche la card si allarga.
- **Le voci con sottomenu si aprono col click** — Sottomenu chiusi, si aprono col click. La voce madre non è più un link.

**Social e icone**

- **Stile delle icone** — Vale per social e icone dell'header.
- **Ordine: social e icone** — Quale gruppo sta a sinistra. Col menu ridotto i social sono solo nel pannello.
- **Divisore tra social e icone** — Linea tra social e icone.

**Icone nell'header**

- **Mostra la ricerca** — Ricerca in prodotti (con Woo), pagine e articoli.
- **Mostra l'icona Account** — Con tooltip. Solo con WooCommerce.
- **Mostra l'icona Preferiti** — Con numero e tooltip. Pagina in Opzioni → Shop.
- **Mostra l'icona Carrello** — Con numero e tooltip.

**Logo scrollando**

- **Cosa fa il logo quando scorri la pagina** — Dopo lo scroll il logo si rimpicciolisce o diventa l'icona qui sotto.
- **Icona del logo (scroll)** — PNG o SVG quadrata, alta 40px.

### Barra annunci (`starter_options_topbar`)

Una fascia sopra l'header con testi che scorrono in continuazione, per annunci, eventi o promozioni. Scorre via con la pagina.

- **Mostra la barra annunci**
- **Etichetta in grassetto** — Facoltativa: compare in grassetto prima di ogni annuncio (es. OPEN DAY).
- **Annunci** — Uno per riga. Scorrono uno dopo l'altro e ricominciano.
- **Link (facoltativo)** — Se lo metti, tutta la fascia diventa cliccabile.
- **Separatore tra gli annunci**
- **Velocità**
- **Ferma lo scorrimento al passaggio del mouse**
- **Colore di sfondo** — Vuoto = colore del testo del sito.
- **Colore del testo** — Vuoto = colore di sfondo del sito.

### Shop (`starter_options_shop`)

Negozio WooCommerce.


**Filtri**

- **Attiva i filtri nella pagina Shop** — Filtri sopra la griglia; su mobile in un pannello.
- **Filtri anche nelle pagine delle categorie**
- **Filtro: ricerca con suggerimenti**
- **Filtro: categoria**
- **Filtro: prezzo (da / a)**
- **Filtri: attributi (Colore, Materiale…)** — Un filtro per attributo di WooCommerce.
- **Colonne dei prodotti** — Su desktop; su tablet e telefono si riducono da sole.
- **Mostra il numero di prodotti** — Es. "12 prodotti" sopra la griglia.

**Card prodotto**

- **Cuore "preferiti" sulla card**
- **Pulsante "Aggiungi al carrello" all'hover**
- **Seconda foto all'hover** — Mostra la 2ª foto della galleria.
- **Mostra le categorie**
- **Mostra la descrizione breve**

**Pagine e cassa**

- **Pagina dei preferiti** — Pagina con [starter_preferiti]. Vuoto = /preferiti/.
- **Cassa: campi per la fattura** — P. IVA, Codice fiscale, SDI e PEC (facoltativi).
- **Negozio in test** — Avviso giallo in fondo a tutte le pagine, sotto il footer. Da spegnere online.
- **Testo dell'avviso** — Vuoto = testo standard.

### Moduli (CPT) (`starter_options_modules`)

Funzioni facoltative del tema: attiva solo quelle che servono al sito. Dopo aver pubblicato, ricarica la pagina: il modulo acceso aggiunge i suoi contenuti, i suoi blocchi e la sua pagina; spento, il suo codice non viene caricato (i contenuti già creati restano e tornano riaccendendolo).

- **Portfolio** — Progetti con categorie, scheda del progetto e blocco "Elenco progetti". Crea la pagina Portfolio.
- **Immobili** — Immobili con filtri, scheda con galleria, planimetria, tour virtuale e PDF, e i blocchi Immobili — Hero home, In evidenza ed Elenco. Crea la pagina Immobili.

### Blog (`starter_options_blog`)

Pagina Blog, categorie, tag, autori, ricerca e articoli.


**Struttura**

- **Aside (filtri e ricerca) nella pagina Blog, nelle categorie e nella ricerca** — Colonna a destra: ricerca, categorie, ultimi articoli, tag.
- **Aside negli articoli**
- **Aside fissa** — Resta in vista mentre scorri (a 20px dall'alto). Solo desktop.
- **Colonne della griglia articoli** — Su desktop. "1 colonna": foto a sinistra, testo a destra.
- **Articoli per pagina** — È la stessa impostazione di WordPress (Impostazioni → Lettura). Con 3 colonne conviene un multiplo di 3.
- **Ultimo articolo in evidenza** — Il più recente a tutta larghezza, con foto a sinistra. Solo prima pagina, non nella ricerca.

**Card dell'elenco**

- **Mostra la data**
- **Mostra l'autore**
- **Nome dell'autore cliccabile** — Porta all'elenco degli articoli di quell'autore. Vale per le card e per l'articolo.
- **Mostra le categorie**
- **Mostra i tag** — Sotto il testo, prima di "Leggi tutto".

**Articolo**

- **Mostra la data**
- **Mostra l'autore**
- **Mostra le categorie**
- **Mostra i tag (a fine articolo)**
- **Riquadro autore a fine articolo** — Avatar, nome e biografia presi dal profilo dell'utente (Utenti → Profilo).
- **Articoli correlati ("Continua a leggere")**
- **Pulsanti "Condividi"** — Facebook, X, LinkedIn e WhatsApp: solo link, nessuno script di terze parti.
- **Sommario** — Indice dei titoli (H2 e H3) prima del primo H2, con almeno due titoli.

### Portfolio (`starter_options_portfolio`)

Progetti del portfolio. L'elenco si crea in una pagina normale con il blocco "Elenco progetti" (colonne, schede e card si scelgono nel blocco).


**Scheda del progetto**

- **Dati del progetto nella testata** — Servizi (le categorie del progetto), agenzie, partner e link al progetto online.
- **"Altri progetti" a fine scheda**

**Altri progetti: categorie**

- **Categorie sulle card**
- **Tab sopra l'immagine**
- **Tab piene o vuote**
- **Simbolo tra le categorie**
- **Colore del simbolo**
- **Posizione rispetto al titolo**
- **Categorie in maiuscolo**
- **Colore delle categorie**

**Altri progetti: riassunto e cliente**

- **Ordine**
- **Mostra il riassunto**
- **Riassunto: tag HTML**
- **Riassunto: dimensione**
- **Riassunto: colore**
- **Riassunto: colore hover**
- **Mostra il nome del cliente**
- **Cliente: tag HTML**
- **Cliente: dimensione**
- **Cliente: posizione**
- **Cliente: colore**
- **Cliente: colore hover**

**Altri progetti: immagine**

- **Proporzione delle immagini**
- **Animazione logo (al passaggio il nome del cliente scorre sull'immagine)**
- **Logo colore sfondo**
- **Video al passaggio del mouse**

**Passaggio tra i progetti**

- **Animazione**
- **Scritta del caricamento**
- **Percentuale da 0 a 100% al posto della scritta**
- **Sfondo (dissolvenza e tendina)**
- **Testo (dissolvenza e tendina)**

### Immobili (`starter_options_immobili`)

Scheda dell'immobile. L'elenco si crea nella pagina Immobili con il blocco "Elenco immobili".

- **Shortcode del modulo "Richiedi informazioni"** — Compare nella scheda dell'immobile, accanto al consulente. Es. [contact-form-7 id="12"]. Il modulo di esempio è creato da solo quando accendi il modulo.

### Prestazioni (`starter_options_perf`)

Velocità del sito per visitatori e motori di ricerca. Le immagini diventano WebP in automatico e gli script non bloccano il caricamento: qui scegli il resto. Le immagini già in Libreria media si ottimizzano da Media → Ottimizza immagini.

- **Compressione e cache del browser (file .htaccess)** — Scrive nel .htaccess le regole per comprimere i file (Brotli/Gzip) e farli ricordare al browser per un anno. Serve un hosting Apache o LiteSpeed; spegnendo la casella le regole vengono tolte.
- **Google Fonts serviti dal tuo sito** — Scarica i font scelti in Tipografia sul tuo sito (cartella uploads/starter-fonts): niente richieste a Google, più veloce e più in regola con la privacy. Si aggiorna quando salvi il Personalizza; se il download fallisce restano i Google Fonts.
- **Precarica la foto di apertura (hero)** — Se la pagina comincia con una hero con foto, il browser la scarica subito: la pagina sembra più veloce.
- **Immagini AVIF (con ripiego WebP)** — Accanto a ogni immagine crea una versione AVIF più leggera (sempre di ottima qualità) e la propone ai browser che la leggono; gli altri vedono il WebP. Non tocca SVG, favicon e logo delle email. Richiede PHP con AVIF: se il server non lo ha, resta il WebP.
- **Qualità AVIF (40–90)** — 70 è un buon equilibrio. Più alto = file più pesante. Vale per le immagini create da ora; per le altre usa Media → Ottimizza immagini.
- **Video WebM (con ripiego mp4)** — Se accanto a un video mp4 carichi in Libreria media un .webm con lo stesso nome (es. film.mp4 e film.webm), il browser usa il WebM e gli altri l'mp4. Vale per i blocchi del tema e per il blocco Video di Gutenberg.
- **Favicon .ico e icone per Google, Android e Apple** — Dall'icona del sito (Identità del sito, 512×512) crea in automatico favicon.ico, le icone PNG 192 e 512, l'icona Apple 180 e il manifest. Il file originale resta PNG.
- **Carica Contact Form 7 solo dove c'è un modulo** — Evita di caricare script e stile dei moduli nelle pagine che non ne hanno (con il form newsletter nel footer restano in tutte le pagine).
- **Togli jQuery Migrate** — Piccolo script per vecchi plugin: se un plugin datato smette di funzionare, riaccendilo.
- **Unisci CSS e JS in un solo file** — Per ogni tipo di pagina crea un solo file CSS e uno JS (nello stesso ordine di prima, compressi) al posto di molti. Esclusi i file esterni (es. Google Fonts). Se qualcosa si rompe, spegni la casella.
- **Comprimi il codice HTML** — Toglie spazi, a capo e commenti dal codice della pagina (che diventa praticamente su una riga). Non cambia ciò che si vede.

### Footer (`starter_options_footer`)

- **Versione footer** — Campi comuni a tutti gli stili; "Spazio libero" e Stile 4 in fondo.
- **Mostra i social** — Social in Generale → Social. Qui: mostrarli o no.

**Colonna testo**

- **Titolo**
- **Testo** — Riga di testo sotto al titolo, nella prima colonna del footer. Massimo 180 caratteri.
- **Frase grande (Stile 5)**
- **Sedi (Stile 5)** — Una sede per blocco, separate da una riga vuota: la prima riga è il titolo.

**Colonna contatti**

- **Titolo**
- **Voci** — Una voce per riga. Telefono ed email diventano link. HTML ammesso.

**Colonna menu**

- **Titolo**

**Riga finale**

- **Testo copyright** — Usa {anno} per l'anno corrente e {sito} per il nome del sito.

**Link "Privacy Policy"**

- **Testo** — Vuoto di default: il link compare nel footer solo dopo aver scritto qui un testo.
- **Indirizzo**
- **Classi CSS (facoltative)** — Classi aggiunte al link (es. Iubenda), separate da spazio.

**Link "Cookie Policy"**

- **Testo** — Vuoto di default: il link compare nel footer solo dopo aver scritto qui un testo.
- **Indirizzo**
- **Classi CSS (facoltative)** — Classi aggiunte al link (es. Iubenda), separate da spazio.

**Link "Termini di servizio"**

- **Testo** — Vuoto di default: il link compare nel footer solo dopo aver scritto qui un testo.
- **Indirizzo**
- **Classi CSS (facoltative)** — Classi aggiunte al link (es. Iubenda), separate da spazio.

**Link "Credits"**

- **Testo** — Vuoto di default: il link compare nel footer solo dopo aver scritto qui un testo.
- **Indirizzo**

**Spazio libero**

- **Titolo dello spazio libero** — Facoltativo.
- **Sottotitolo** — Facoltativo, sotto il titolo.
- **Contenuto dello spazio libero** — Shortcode (es. un form), testo o HTML. Vuoto = nessuno spazio.

**Stile 4: pagamenti**

- **Immagine dei metodi di pagamento** — Solo Stile 4: loghi dei pagamenti, sotto i social.


## 2. File PHP

### `functions.php`
Starter Theme - functions.php
- `starter_setup()` — Setup tema: supporti, menu, textdomain.
- `starter_body_class_has_hero()` — Classe "has-hero" sul body: permette all'header fixed di partire trasparente solo sopra la hero, senza JS.
- `starter_excerpt_chars()` — Estratto troncato a caratteri (non parole), per altezza prevedibile nelle card blog.
- `starter_widgets_init()` — Sidebar del blog (categorie, articoli, tag, ricerca).
- `starter_customize_register()` — Customizer: logo header trasparente (in "Identità del sito") + pannello "Opzioni" per le impostazioni del tema.
- `render_content()` — Disegna il pulsante "+ Aggiungi icona personalizzata"
- `render_content()` — Disegna un titolo di sezione nel pannello
- `render_content()` — Disegna un pulsante con testo e classe scelti
- `render_content()` — Disegna un gruppo di caselle da spuntare
- `render_content()` — Disegna i tre campi Desktop/Tablet/Mobile
- `render_content()` — Disegna un menu a tendina con i font, raggruppati
- `starter_asset_version()` — Versione asset da data modifica file, non da costante fissa: evita cache stale durante lo sviluppo.
- `starter_customize_controls_enqueue_scripts()` — Script admin Customizer: rivela uno slot icona personalizzata alla volta ad ogni click "+ Aggiungi".
- `starter_allow_svg_upload()` — Consente upload SVG solo a chi può modificare le opzioni tema, non a tutti gli utenti.
- `starter_fix_svg_filetype_check()` — wp_check_filetype_and_ext rifiuta comunque l'SVG: qui lo whitelista per chi può già caricarlo.
- `starter_sanitize_uploaded_svg()` — Sanifica anche il file SVG salvato su disco, non solo l'output in pagina.
- `starter_sanitize_checkbox()` — Sanitizzazione checkbox del Customizer.
- `starter_sanitize_content_width()` — "Larghezza contenuto": pixel in un range ragionevole, altrimenti default 1280.
- `starter_sanitize_css_length()` — Valida una misura CSS libera (numero + unità opzionale, per line-height senza unità); altro = vuoto per prevenire iniezione CSS.
- `starter_sanitize_text_style_font_weight()` — "font-weight" in Stile testi è facoltativo: a differenza di starter_sanitize_font_weight(), invalido torna vuoto non "400".
- `starter_register_color_control()` — Registra un campo colore: ora è un menu che salva un riferimento a uno slot "Colori con nome" ("slot_1".."slot_8"), non un esadecimale diretto.
- `starter_colors_section()` — Pannello del Customizer in cui finiscono i controlli colore registrati da qui in poi (cambia a ogni gruppo: sfondo/testi, pulsanti, menu/footer, tag).
- `starter_default_palette()` — Palette colori di partenza (nome, esadecimale, slug)
- `starter_palette_slug()` — Slug del preset di uno slot: fisso per i colori di base (li usa CSS/starter.css), "starter-named-N" per quelli aggiunti dall'utente.
- `starter_color_field_defaults()` — Campo colore del Customizer => slot di base che rappresenta (vuoto = nessun default, resta il valore fisso già in CSS).
- `starter_color_ref()` — Riferimento (slot_N) salvato per un campo colore, o quello di base se non è mai stato toccato.
- `starter_get_named_colors()` — Colori con nome attivi (16 slot possibili, i primi 5 già precompilati coi colori di base del tema): attivo solo chi ha un valore; nome vuoto → "Colore N" come ripiego.
- `starter_named_color_choices()` — Scelte tendina campo colore: "— Non impostato —" (= colore base tema) più un'opzione per ogni colore con nome attivo.
- `starter_sanitize_named_color_ref()` — Accetta solo riferimenti a slot "Colori con nome" attivi; altrimenti vuoto, per non lasciare riferimenti rotti.
- `starter_resolve_named_color()` — Risolve un riferimento "slot_N" nel colore esadecimale vero, usata da starter_colors_css().
- `starter_maybe_migrate_named_colors()` — Migrazione una tantum da esadecimali diretti a slot (dedup, un solo run: flag "starter_named_colors_migrated" evita di sovrascrivere modifiche successive).
- `starter_sanitize_header_style()` — Versione header: valore ammesso o fallback a "extended".
- `starter_sanitize_footer_description()` — Descrizione tema nel footer: testo semplice troncato a 180 caratteri.
- `starter_footer_consent_allowed_html()` — Whitelist HTML ristretta per il testo privacy footer, condivisa da sanitizzazione e stampa per restare coerenti.
- `starter_sanitize_footer_consent_text()` — Testo accettazione privacy: solo i tag/attributi whitelistati, resto rimosso da wp_kses().
- `starter_sanitize_footer_contacts()` — Voci contatti footer: stessa whitelist HTML del testo privacy, per link/classi oltre al testo semplice.
- `starter_sanitize_css_classes()` — Classi CSS dei link footer (per agganciare script esterni come Iubenda): sanitizzate una per una.
- `starter_google_fonts_list()` — Elenco selezionato a mano (non tutti i 1400+ Google Fonts), non usato direttamente dal menu (serve il prefisso "google:").
- `starter_system_fonts_list()` — Font di sistema: valore è già lo stack "font-family" con fallback, non solo il nome nudo.
- `starter_system_fonts_choices()` — Scelte gruppo "Font di sistema": valore con prefisso "system:" => nome mostrato.
- `starter_google_fonts_choices()` — Scelte gruppo "Google Fonts": valore con prefisso "google:" => nome mostrato.
- `starter_sanitize_typography_font_choice()` — Font 1/2: valida "system:{nome}"/"google:{nome}" o vuoto; non ripiega su un default come altrove, invalido = vuoto.
- `starter_font_weight_choices()` — Pesi per "Variante N — peso" di Font 3/4: valore = "font-weight" per la relativa @font-face.
- `starter_sanitize_font_weight()` — "Variante N — peso": valore valido o default "400" — qui non esiste "vuoto", una variante ha sempre un peso.
- `starter_font_style_choices()` — Stili per "Variante N — stile": separato dal peso apposta, così il corsivo non si nasconde tra 16 combinazioni.
- `starter_sanitize_font_style()` — "Variante N — stile": "normal" o "italic", altrimenti default "normal".
- `starter_allow_font_upload_mimes()` — Consente upload font (TTF/WOFF/WOFF2/EOT) per Font 3/4, solo a chi può modificare le opzioni tema.
- `starter_fix_font_filetype_check()` — Stesso problema di starter_fix_svg_filetype_check() ma per i 4 formati font: whitelist esplicita in wp_check_filetype_and_ext.
- `starter_get_active_typography_fonts()` — Font attivi dei 4 slot tipografia, condiviso da CSS e enqueue Google Fonts per restare coerenti.
- `starter_typography_css()` — CSS dei font attivi: fallback var(--font-body) salvo font di sistema; se un elemento è in più slot vince l'ultimo (Font 4 > 3 > 2 > 1) per ordine di cascade.
- `starter_enqueue_typography_assets()` — Inline il CSS tipografia (niente richiesta HTTP extra) e, se serve, un unico <link> Google Fonts per tutti gli slot attivi.
- `starter_corner_radius()` — Raggio unico degli angoli scelto in Opzioni → Generale → Bordi del sito (px). Di base: Medi (20).
- `starter_colors_css()` — CSS Opzioni→Colori: campo vuoto = nessuna regola; H1-H6 usano selettore diretto a bassa specificità (non variabile) così i colori inline di Gutenberg vincono sempre.
- `starter_enqueue_colors_assets()` — Inline style sullo stesso foglio della tipografia, niente richiesta HTTP extra.
- `starter_text_style_elements_map()` — Mappa chiave campo -> selettore CSS per Opzioni→Stile testi, condivisa con starter_typography_style_css().
- `starter_text_style_presets()` — Elemento di Stile testi => preset di dimensione in theme.json (--wp--preset--font-size--{slug}). I componenti del tema (shop, blog, header...) usano SOLO queste variabili: cambiando una dimensione in Opzioni → Stile testi cambia ovunque, per desktop, tablet e mobile. Mai font-size scritti a mano nei CSS.
- `starter_theme_json_font_size()` — Dimensione di un preset di theme.json (la stessa che usa CSS/starter.css), es. 'title-1' => 'clamp(2.75rem, …)'.
- `starter_text_style_default()` — Valori standard di Stile testi: dimensione = preset di theme.json (clamp, si adatta a ogni larghezza) e interlinea. Sono quelli che compaiono già compilati nelle opzioni (solo Desktop) e che CSS/starter.css applica di base.
- `starter_text_style_declarations()` — Dichiarazioni CSS per un elemento a un breakpoint: proprietà responsive + (solo desktop) font-weight/uppercase.
- `starter_typography_style_css()` — CSS Stile testi: regola desktop + media query tablet/mobile solo se compilati; campo vuoto eredita dal breakpoint più largo (cascata max-width).
- `starter_enqueue_text_style_assets()` — Inline style sullo stesso foglio di tipografia/colori, niente richiesta HTTP extra.
- `starter_enqueue_layout_assets()` — --content-width da Opzioni→Generale; non stampato se è il default (1280); --wide-width è sempre content-width+200px.
- `starter_footer_style_choices()` — Stili footer disponibili: per aggiungerne uno basta una voce qui + template-parts/footer/footer-{slug}.php.
- `starter_sanitize_footer_style()` — Sanitizzazione versione footer: valore ammesso o fallback al primo disponibile.
- `starter_wpml_register_string()` — Registra un campo Personalizza come stringa traducibile WPML; no-op se WPML non è attivo.
- `starter_wpml_translate_string()` — Ritorna la stringa tradotta via WPML, o il valore originale se WPML non è attivo.
- `starter_contact_href()` — Link automatico di un recapito: email → mailto:, numero di telefono → tel:, altro (indirizzo, orari…) → nessun link.
- `starter_contact_icon()` — Icona di ripiego di un recapito (quando il campo icona è vuoto): email, telefono o indirizzo.
- `starter_get_footer_contacts()` — Recapiti del footer (indirizzo, telefono, email…) pronti da stampare
- `starter_sanitize_svg()` — Sanitizza l'SVG caricato: whitelist di tag/attributi per evitare markup pericoloso.
- `starter_inline_svg_images()` — Opzioni → Generale → "SVG": se attivo, sostituisce ogni <img src=".svg"> con l'SVG inline (colorabile via CSS). Disattivo di default.
- `starter_replace_img_with_inline_svg()` — Callback di starter_inline_svg_images(): sostituisce l'img con l'SVG, riportando classe/alt; in caso di errore lascia l'img invariato.
- `starter_svg_url_to_path()` — Converte un URL della Libreria media nel percorso locale (vuoto se fuori dalla cartella upload).
- `starter_has_dark_logo()` — True se il logo per l'header trasparente è attivo e caricato.
- `starter_is_minimal_header()` — True se Opzioni → Header ha l'header "Ridotto" selezionato.
- `starter_is_standard_header()` — True se Opzioni → Header ha l'header "Standard" selezionato.
- `starter_is_pills_header()` — True se Opzioni → Header ha l'header "Pillole" selezionato: senza sfondo, sempre visibile, menu e pulsante in due pillole. Usa la disposizione "Standard".
- `starter_is_floating_header()` — True se "Header Flottante" (Opzioni → Header) è attivo; si applica sopra qualunque versione dell'header.
- `starter_social_presets()` — Icone social predefinite (chiave => etichetta + SVG) mostrate come campi URL in Opzioni → Header.
- `starter_get_social_links()` — Icone social da mostrare in header: preset con URL compilato + slot custom con URL e SVG entrambi compilati.
- `starter_render_social_links_items()` — Stampa gli <li> delle icone social col markup sdoppiato per l'animazione flip; usata sia nell'header che nel pannello mobile.
- `starter_get_svg_markup_from_attachment()` — Legge l'SVG da Media Library e ritorna il markup sanificato, trattato come codice invece che come <img>.
- `starter_force_svg_currentcolor()` — Forza fill/stroke a "currentColor" (lascia "none") così le icone social caricate seguono i colori del tema; non usata per l'SVG generico nel contenuto.
- `starter_dist_ready()` — CSS/dist è valido solo se più recente dei sorgenti (altrimenti si usano i sorgenti: le modifiche si vedono subito).
- `starter_has_content_block()` — Vero se in pagina c'è almeno uno dei blocchi ACF in template-parts/ (vedi inc/acf-blocks.php).
- `starter_page_has_block()` — Vero se la pagina corrente contiene il blocco ACF del tema indicato (es. 'ultimi-articoli').
- `starter_needs_filters_css()` — Filtri degli elenchi (CSS/filters.css, uguale per tutti): archivio dello Shop e pagine dei moduli che li usano (filtro starter_needs_filters_css).
- `starter_css_buckets()` — Fogli CSS extra: bucket => serve in questa pagina? "base" (header, footer, bottoni...) si carica sempre, non è in elenco.
- `starter_footer_wordmark_font_needed()` — Vero se il footer mostra la scritta grande in Fraunces (Stile 1, senza logo chiaro): solo allora serve quel font.
- `starter_theme_enqueue_assets()` — Enqueue di stile e script del frontend.
- `starter_vendor_version()` — Versione di una libreria in JS/vendor, letta da JS/vendor/vendor.json (la aggiorna tools/update-vendor.js).
- `starter_preloader_mode()` — Caricamento delle pagine scelto in Opzioni → Generale: none | dissolvenza | tendina.
- `starter_enqueue_animation_assets()` — Scroll fluido (Lenis), solo se attivo in Opzioni → Generale. Niente GSAP: non serve.
- `starter_theme_register_acf_blocks()` — Registra automaticamente ogni blocco ACF con block.json in /template-parts; senza ACF resta registrato ma senza campi.
- `starter_block_categories()` — Categoria "Starter" nell'inserimento blocchi, per i blocchi ACF del tema in template-parts/.
- `starter_theme_cleanup_head()` — Rimuove meta poco utili dall'head in produzione.
- `starter_theme_image_sizes()` — Dimensione custom per le immagini in evidenza, pronta all'uso nei template.
- `starter_wp_block_button_flip_animation()` — Inserisce il markup duplicato per l'animazione flip del testo del blocco Pulsante (il core non lo genera da solo); solo frontend, mai nell'editor.
- `starter_register_button_block_styles()` — Stili del blocco Pulsante di Gutenberg: "Pieno" e "Contorno" sono già del core (qui prendono i colori di Pieno 1 e Contorno 1); questi due sono le varianti 2 di Opzioni → Pulsanti.
- `starter_extend_editor_color_palette()` — Palette di theme.json: i "Colori con nome" (Customizer) sostituiscono per slug i colori di base e aggiungono quelli nuovi.
- `starter_login_logo()` — Sostituisce il logo WordPress in login col logo del sito (variante normale, non quella per header trasparente).
- `starter_login_logo_url()` — Logo di login: link all'homepage invece di wordpress.org, title col nome del sito.
- `starter_login_logo_title()` — Title del logo di login: nome del sito
- `starter_comments_disabled()` — True se "Disattiva i commenti su tutto il sito" (Opzioni → Generale) è attivo.
- `starter_maybe_disable_comments()` — Disattiva completamente i commenti (hook attivi solo se l'opzione è spuntata): chiude commenti/pingback, li nasconde, rimuove tracce dall'admin.

### `404.php`
Template per la pagina 404 (contenuto non trovato): stesso titolo di pagina del resto del sito, campo di ricerca e qualche link per ripartire.

### `archive.php`
Archivi: categoria, tag, autore, data e qualunque tassonomia/post type senza un template più specifico (es. /portfolio/). Struttura condivisa in template-parts/blog/list.php: intestazione col nome della sezione in cui siamo, griglia di card, aside. Si regola da Opzioni → Blog.

### `footer.php`
Footer del tema: carica lo stile scelto in Personalizza → Opzioni → Footer → Versione footer (vedi template-parts/footer/footer-{slug}.php e starter_footer_style_choices() in functions.php).

### `header.php`
Header del tema.

### `home.php`
Pagina blog (elenco articoli). Struttura condivisa in template-parts/blog/list.php: intestazione col nome della sezione in cui siamo, griglia di card, aside. Si regola da Opzioni → Blog.

### `index.php`
Template di fallback principale.

### `page.php`
Template per le pagine (post_type "page").

### `search.php`
Risultati di ricerca. Struttura condivisa in template-parts/blog/list.php: intestazione col nome della sezione in cui siamo, griglia di card, aside. Si regola da Opzioni → Blog.

### `sidebar-blog.php`
Aside del blog (a destra su desktop; su tablet/mobile pannello aperto dal pulsante "Filtri e ricerca"). Se in Aspetto → Widget l'area "Sidebar Blog" ha dei widget, si usano quelli (l'ordine lo decidi tu). Altrimenti il tema stampa un aside di base: cerca, categorie (col numero di articoli), ultimi articoli, tag. Si spegne da Opzioni → Blog (aside nell'elenco / negli articoli).

### `single-portfolio.php`
Singolo progetto del Portfolio: testata (categorie, titolo, dati del progetto), copertina (video o immagine), contenuto fatto con i blocchi "Progetto — testo / media / crediti" e "Altri progetti".

### `single.php`
Template per il singolo articolo (post_type "post"): titolo, data/autore/categorie, immagine, testo, tag, riquadro autore, "Continua a leggere" e aside. Tutto si accende o spegne da Opzioni → Blog.

### `inc/acf-blocks-extra.php`
Campi ACF dei blocchi aggiuntivi (hero con slider, striscia valori, ultimi articoli, prodotti in evidenza, Instagram, team, contatti), importati da Symbolikamente e Di Guida Real Estate e resi generici. Registrati via codice come gli altri (inc/acf-blocks.php): ogni blocco ha block.json + render.php in template-parts/.
- `starter_extra_head_fields()` — Campi comuni "intestazione di sezione": titolo, testo, pulsante.
- `starter_extra_block_location()` — Posizione "Blocco = starter-theme/{nome}".
- `starter_register_extra_block_fields()` — Registra i campi ACF dei blocchi aggiuntivi

### `inc/acf-blocks.php`
Campi ACF dei blocchi in template-parts/ (registrati via codice, nessun acf-json da sincronizzare). Un blocco in più: duplica una delle sezioni sotto, cambia "key"/block name, aggiorna anche block.json.
- `starter_add_block_group()` — Registra il gruppo di campi di un blocco aggiungendo, subito dopo "Occhiello" e "Titolo", i selettori "Tag HTML" (Automatico, H1-H6, span, p): servono a scegliere come marcare titoli e occhielli per la SEO. "Automatico" lascia il tag che il tema calcola da solo. Nei repeater si applica solo dove l'elenco è indicato in $repeater_groups (Missione e visione, che ha occhiello e titolo per riga).
- `starter_section_bg_choices()` — Sfondi della sezione (campo "Sfondo della sezione"): chiave => etichetta. Nessuno, "Chiaro" (grigio leggero) e tutti i colori della palette di Opzioni → Colori (slot_N, con il nome che hanno lì).
- `starter_section_bg_legacy_map()` — Colori salvati con le vecchie chiavi (prima dell'elenco completo) => slot della palette.
- `starter_color_is_dark()` — Vero se un colore esadecimale è scuro (testo chiaro sopra). Luminanza relativa sRGB < 0,45.
- `starter_blocks_without_section_bg()` — Blocchi che hanno già una foto/video di sfondo: lì non ha senso lo sfondo colorato, restano solo le classi.
- `starter_advanced_fields()` — Scheda "Avanzate" in fondo a ogni blocco: sfondo colorato a tutta larghezza + classi CSS personalizzate (margini, padding, ecc.). Applicati da starter_apply_block_advanced().
- `starter_button_link_fields()` — Campi link che sono pulsanti, per gruppo: sotto ognuno si aggiunge il menu "Stile del pulsante" (Pieno 1/2, Contorno 1/2). true = c'è anche "Chiaro" (blocchi sopra le foto). Un nuovo blocco con un pulsante si aggiunge qui e legge lo stile con starter_button_class( get_field( 'nome_stile' ), 'classi predefinite' ).
- `starter_insert_button_style_fields()` — Aggiunge ai campi di un blocco le scelte di stile dei pulsanti
- `starter_insert_heading_tag_fields()` — Aggiunge ai campi di un blocco la scelta del tag del titolo (H2, H3…), anche nei ripetitori
- `starter_register_acf_blocks_fields()` — Registra i campi ACF di tutti i blocchi del tema

### `inc/acf-field-icon.php`
Tipo di campo ACF "Icona" (libreria Starter): un pulsante apre una finestra con tutte le icone, con ricerca. Il valore salvato è il nome dell'icona (es. "shield-check", "brand-instagram"), lo stesso che legge starter_icon(). Chi usava il vecchio campo "Selezione" con poche icone non deve cambiare nulla: i valori già salvati restano validi.
- `initialize()` — Nome, etichetta e categoria del campo
- `render_field_settings()` — Impostazioni del campo: "nessuna icona" consentita e icona predefinita
- `render_field()` — Disegna il campo: anteprima, nome e pulsanti Scegli/Rimuovi
- `validate_value()` — Errore se manca l'icona e il campo non può restare vuoto
- `input_admin_enqueue_scripts()` — Carica stile e script del selettore icone

### `inc/acf-options.php`
Pulsante WhatsApp fisso in basso a destra. I campi sono in Aspetto → Personalizza → Opzioni → Generale (prima stavano in una pagina "Opzioni tema" di ACF, ora tolta: chi aveva già compilato i campi li ritrova qui).
- `starter_whatsapp_opt()` — Valore di un campo WhatsApp ('url' o 'text'); ripiego sui vecchi campi ACF se il sito li aveva compilati.
- `starter_whatsapp_body_class()` — Classe "has-whatsapp" sul body quando il pulsante WhatsApp è attivo (spazio riservato in basso su mobile, vedi CSS/starter.css).

### `inc/assets-optimizer.php`
Compressione: unisce i CSS e i JS consecutivi in un solo file e comprime l'HTML su una riga (opzioni Prestazioni → "Unisci CSS e JS" e "Comprimi il codice HTML").
- `starter_cache_dir()` — Cartella dei file uniti (uploads/starter-cache): si ricrea da sola, non va copiata online.
- `starter_minify_css()` — CSS: toglie commenti e spazi inutili, lasciando intatti stringhe e url(). Se il risultato non torna (graffe diverse) restituisce l'originale.
- `starter_js_single_line()` — JS inline su una riga sola: legge il codice (stringhe, espressioni regolari, commenti) e al posto di ogni a capo mette uno spazio, ma solo dove il codice non ne dipende (dopo ; { , ( [ : o un operatore, oppure prima di } ) ] , ; . ? :). Toglie i commenti. Se trova un a capo "ambiguo" (una riga senza punto e virgola seguita da un'altra istruzione), un apice inverso o un caso che non sa leggere, restituisce null e lo script resta com'è.
- `starter_minify_inline_js()` — JS inline: su una riga sola se è sicuro (vedi sopra); altrimenti toglie solo gli spazi a inizio e fine riga e le righe vuote (e, senza apici inversi, le righe di solo commento).
- `starter_minify_block()` — Una <script>…</script> o <style>…</style> già completa → versione compressa (JSON-LD compatto, CSS minificato, JS ripulito).
- `starter_html_block_tag()` — Tag che non mostrano nulla o vanno a capo da soli: gli spazi e gli a capo accanto a loro non servono.
- `starter_minify_html()` — HTML: toglie commenti, a capo e spazi doppi, e gli spazi accanto ai tag di blocco (div, p, section…). Non tocca <pre> e <textarea>, lascia uno spazio tra elementi di testo (a, span, li…) e comprime <style>, JSON-LD e, con cautela, gli script inline.
- `starter_local_asset_path()` — Percorso su disco di un CSS/JS del sito dato il suo URL; '' se è esterno, dinamico o illeggibile.
- `starter_asset_mergeable()` — Vero se un file si può unire senza rischi (CSS senza @import, JS che non cerca da solo il proprio indirizzo). Il verdetto si ricorda finché il file non cambia.
- `starter_css_rebase()` — Riscrive gli url() relativi di un CSS in indirizzi dalla radice del sito, così il file unito (che sta in un'altra cartella) trova ancora font e immagini.
- `starter_build_bundle()` — Crea (o riusa) il file unito di una serie di CSS o JS. Restituisce l'indirizzo, oppure '' se non riesce.
- `starter_cache_prune()` — Una volta al giorno toglie i file uniti non usati da più di 45 giorni (e gli avanzi di scritture interrotte).
- `starter_cache_clear()` — Svuota i file uniti (si ricreano alla prima visita). Restituisce quanti ne ha tolti.
- `starter_bundle_assets()` — Nell'HTML finale cerca serie di <link rel="stylesheet"> o <script src> locali uno di seguito all'altro (ordine di pagina invariato) e le sostituisce con un solo file. I <script> inline "…-js-extra" (dati di wp_localize_script) passano prima del file unito; ogni altro elemento in mezzo (script inline, stile inline, file esterni, file con attributi diversi) interrompe la serie.
- `starter_optimize_applies()` — Vero se la richiesta corrente è una normale pagina HTML del sito da ottimizzare.
- `starter_optimize_output()` — Applica unione CSS/JS e compressione HTML (se accese) alla pagina finale; in caso di errore restituisce la pagina com'era

### `inc/block-helpers.php`
Funzioni di supporto condivise dai blocchi ACF del tema (in template-parts/).
- `starter_field()` — Legge un campo ACF; se ACF non è attivo ripiega sul meta grezzo, così il tema non va in errore.
- `starter_link_attrs()` — Attributi HTML di un campo ACF "Link" (array url/title/target).
- `starter_button_style_choices()` — Stili di pulsante tra cui scegliere nei blocchi: sono le 4 varianti di Opzioni → Pulsanti (Pieno 1 e Contorno 1 = i pulsanti "principale" e "secondario" del sito).
- `starter_button_class()` — Classi del pulsante di un blocco: lo stile scelto nel menu "Stile del pulsante", oppure quelle predefinite del blocco se è su "Predefinito". $extra = classi in più (es. split__button).
- `starter_link_title()` — Testo di un campo ACF "Link", o un valore di ripiego se il titolo è vuoto.
- `starter_attachment_ids()` — Id di allegati da un valore ACF salvato: un id, un elenco di id, o (vecchi contenuti) array con 'ID'.
- `starter_photo_pair()` — Foto principale + seconda foto facoltativa di un blocco (max 2 id). Ogni foto è un campo immagine singolo. Compatibile con i contenuti salvati quando erano una gallery: l'elenco vecchio ($main come array, oppure $legacy) vale ancora.
- `starter_kses()` — Testo con HTML limitato consentito (titoli con <em>, ecc.).
- `starter_inline()` — Testo scritto in un blocco (titolo, occhiello, testo, voce di un elenco…): permette solo la formattazione in linea (grassetto, corsivo, sottolineato, a capo, link), niente altro HTML.
- `starter_flip_text()` — Testo di un pulsante con l'animazione "flip" già usata sul blocco Pulsante nativo (vedi starter_wp_block_button_flip_animation() in functions.php): stessa struttura HTML, riusa lo stesso CSS.
- `starter_heading_tag_choices()` — Tag ammessi per occhiello e titolo dei moduli (campi "Tag HTML" dei blocchi): da H1 a H6, span e paragrafo.
- `starter_pick_tag()` — Tag scelto: $value se è uno di quelli ammessi, altrimenti quello automatico ($auto).
- `starter_heading_tag()` — Tag HTML scelto nel blocco per l'occhiello ($part = 'occhiello') o per il titolo ($part = 'titolo'): campi "Tag HTML" del modulo. "Automatico" (o nessuna scelta, come nei contenuti vecchi) lascia il tag che il tema calcola per la gerarchia SEO ($auto). Dentro una riga di un repeater legge la riga, altrimenti il campo del blocco; fuori da un blocco restituisce sempre $auto.
- `starter_section_heading_tags()` — Tag heading di una sezione: occhiello (h1/h2) → titolo (un livello sotto) → eventuale figlio (un altro livello sotto). Il modulo può sostituire occhiello e titolo con il tag scelto.
- `starter_breadcrumb()` — Briciole di pane: $items = [ [ etichetta, url ], ... ], l'ultima senza url.
- `starter_page_breadcrumb()` — Briciole di una pagina: Home, pagine genitore, pagina corrente.
- `starter_block_placeholder()` — Messaggio in editor per un blocco ancora vuoto (sul sito non stampa niente).
- `starter_block_head()` — Intestazione di sezione dei blocchi: titolo (H2) e testo, centrati. Non stampa niente se sono vuoti.
- `starter_block_center_link()` — Pulsante centrato sotto una griglia (campo ACF "Link").
- `starter_live_attrs()` — Aggancio per Starter Live (plugin di modifica dal frontend): restituisce gli attributi che rendono modificabile un elemento che non è un blocco (titolo della pagina, occhiello salvato come meta…). Senza il plugin, o per chi non può modificare, restituisce una stringa vuota.
- `starter_page_header()` — Stampa l'intestazione di pagina: briciole e titolo H1
- `starter_page_header_titles()` — Occhiello + titolo di un titolo di pagina (vedi starter_page_header). $title_html: ammesso <em>/<br>.
- `starter_page_has_own_header()` — Vero se la pagina apre già con la sua intestazione o hero (un blocco che la stampa): allora page.php non aggiunge il titolo. I moduli (inc/modules.php) aggiungono i loro blocchi con il filtro starter_page_header_blocks.

### `inc/blog.php`
Blog: opzioni (Opzioni → Blog), meta degli articoli, intestazioni di sezione, aside. Le stesse funzioni servono la pagina Blog (home.php), gli archivi di categoria/tag/autore/data (archive.php), la ricerca (search.php) e l'articolo (single.php): cambiando un'opzione cambia ovunque.
- `starter_blog_defaults()` — Valori predefiniti delle opzioni del blog (le chiavi sono i nomi dei theme_mod senza prefisso "starter_blog_").
- `starter_blog_opt()` — Valore di un'opzione del blog.
- `starter_blog_has_aside()` — Vero se l'aside va mostrato in questa pagina (articolo oppure elenco).
- `starter_blog_meta()` — Riga dei dati dell'articolo: a sinistra data e autore, a destra le categorie come etichette (solo quelli attivi nelle opzioni). I tag non stanno qui: nelle card vanno sotto il testo, prima di "Leggi tutto" (starter_blog_card_tags); nell'articolo a fine testo.
- `starter_blog_card_tags()` — Tag nelle card (se attivi in Opzioni → Blog): etichette sotto il testo, prima di "Leggi tutto".
- `starter_blog_featured_active()` — Vero se in questa pagina il primo articolo va in evidenza: opzione attiva, elenco (Blog o archivi, non la ricerca), prima pagina.
- `starter_blog_section_header()` — Intestazione della sezione corrente: occhiello (che sezione è), titolo (il nome della sezione) e introduzione. Categoria "Notizie" → occhiello "Categoria", titolo "Notizie"; ricerca → "Risultati di ricerca" / “parola”.
- `starter_blog_render_header()` — Stampa l'intestazione della sezione (stessa su Blog, archivi e ricerca): lo stesso titolo delle pagine con i blocchi.
- `starter_blog_aside_toggle()` — Pulsante che apre l'aside su tablet/mobile (non stampato se l'aside è spento).
- `starter_blog_pagination()` — Paginazione dell'elenco.
- `starter_blog_author_box()` — Riquadro autore a fine articolo (avatar, nome, biografia dal profilo dell'utente).
- `starter_blog_related()` — "Continua a leggere": 3 altri articoli (stessa categoria se ce ne sono, altrimenti i più recenti).
- `starter_blog_toc_filter()` — Sommario dell'articolo (Opzioni → Blog → "Sommario"): indice dei titoli H2 (con gli H3 come sottovoci) in un riquadro prima del primo H2, con link che scorrono al titolo. Si crea da solo dal contenuto; i titoli senza id ne ricevono uno. Compare solo negli articoli e solo se ci sono almeno due titoli.

### `inc/breadcrumbs.php`
Briciole di pane (Opzioni → Generale → "Mostra le briciole di pane"). Usano le briciole di Yoast SEO se il plugin c'è (la barra visibile e il BreadcrumbList dei dati strutturati hanno così la stessa fonte); senza Yoast ripiegano su quelle di WooCommerce nelle pagine dello shop, altrimenti su un percorso semplice Home › pagina madre › titolo. Per un tipo di contenuto senza archivio ma con una pagina "elenco" (es. un CPT Immobili con la pagina Immobili) filtra `wpseo_breadcrumb_links` nel progetto, così Yoast scrive Home › Immobili › Titolo.
- `starter_breadcrumb_items()` — Voci delle briciole nel formato array( array( testo, url ), ... ); l'ultima ha l'url vuoto.
- `starter_breadcrumbs_style()` — Stile delle briciole (Opzioni → Generale): 'inline' (nel titolo della pagina, come nelle pagine coi blocchi) o 'bar' (fascia con bordo sotto l'header).
- `starter_breadcrumbs_inline_on()` — Vero se le briciole vanno mostrate nel titolo della pagina (opzione attiva, stile "inline", non in home).
- `starter_breadcrumb_inline()` — Briciole "nel titolo": stesso aspetto di quelle dei blocchi (".breadcrumb"). Non stampa niente se spente o senza percorso.
- `starter_breadcrumb_header()` — Briciole "nel titolo" per pagine senza il blocco Intestazione pagina: stesso contenitore e stesse distanze (".page-header").
- `starter_breadcrumb_bar()` — Barra delle briciole sotto l'header, in tutte le pagine tranne la home (se l'opzione è attiva e lo stile è "fascia").

### `inc/cf7.php`
Contact Form 7: anti-spam senza plugin né servizi esterni + campi nascosti per Flamingo. 1) Honeypot automatico in TUTTI i moduli (niente da incollare nel form): - un campo "trappola" invisibile (classe .form-hp): le persone non lo vedono e non lo compilano, i bot sì; - un orario firmato (starter_ts): inviare il modulo in meno di 3 secondi dal caricamento, o senza un orario valido, è considerato spam. Le richieste scartate finiscono nel registro spam di CF7 (e Flamingo) con il motivo; il bot non vede errori. 2) Campi nascosti per sapere DA DOVE arriva ogni contatto. A ogni modulo il tema aggiunge da solo (non serve toccare il form) i tag [hidden pagina] e [hidden pagina-url]: titolo e indirizzo della pagina in cui si trova il modulo. Flamingo li registra con il messaggio e il tema li aggiunge in fondo alla mail ("Pagina: …"). Se in un modulo li scrivi a mano, il tema non li duplica. Nota: `default:post_title` NON funziona sui campi hidden di CF7, per questo il valore lo scrive il tema. 3) Auto-paragrafo di CF7 disattivato: il markup dei moduli resta quello scritto (niente <p> e <br> inseriti da soli).
- `starter_honeypot_signature()` — Firma (HMAC) dell'orario di caricamento: riconosce gli invii troppo rapidi dei bot

### `inc/compatibility.php`
Compatibilità con gli aggiornamenti di WordPress, WooCommerce e ACF. Il tema dichiara con quali versioni (maggiore.minore) è stato provato. Se un sito monta una versione più nuova, compare un avviso in Bacheca, Aggiornamenti, Temi e Plugin, e un controllo in Strumenti → Salute del sito. Non blocca nulla: serve a ricordare di provare in locale (tools/smoke-test.js) prima di aggiornare un sito vero. Dopo aver provato una versione nuova, si alza il numero in starter_compat_tested().
- `starter_compat_tested()` — Versioni con cui il tema è stato provato (maggiore.minore).
- `starter_compat_current()` — Versioni installate sul sito (vuote se il software non c'è).
- `starter_compat_ahead()` — Elenco dei software con una versione più nuova di quella provata: nome => array( installata, provata ).
- `starter_compat_message()` — Testo dell'avviso: software più nuovo di quello provato con il tema

### `inc/cookieyes.php`
CookieYes in italiano. CookieYes (plugin "cookie-law-info") nasce in inglese: banner, categorie e pulsanti escono in inglese anche su un sito italiano. Qui si usano i testi italiani che il plugin stesso porta con sé (con qualche ritocco alle traduzioni più rigide, es. "Accettare tutto" → "Accetta tutto") e si imposta l'italiano come lingua del banner. - parte da sola una volta, la prima volta che si apre l'amministrazione dopo aver installato il plugin; - si può rilanciare da Strumenti → Contenuti di prova → "Imposta CookieYes in italiano" (utile se qualcuno ha rimesso l'inglese o dopo aver ricreato il banner). Non tocca colori, posizione, tipo di banner né i cookie che hai già descritto a mano.
- `starter_cookieyes_dir()` — Cartella con i testi per lingua del plugin ('' se il plugin non c'è o ha un'altra struttura).
- `starter_cookieyes_active()` — True se CookieYes è installato e caricato.
- `starter_cookieyes_polish()` — Ritocchi alle traduzioni del plugin (più naturali per un sito italiano).
- `starter_cookieyes_set_italian()` — Imposta l'italiano come lingua di CookieYes: impostazioni, banner (testi) e categorie.

### `inc/customizer-extra.php`
Opzioni del Customizer aggiunte oltre a quelle di functions.php (si registrano dopo, priorità 20): - Header: versione a tutta larghezza, sottomenu col click, icone shop (account, preferiti, carrello) solo con Woo; - Generale: briciole di pane; - Shop (solo con WooCommerce attivo): filtri, card prodotto, preferiti, dati per la fattura; - Blog: aside, colonne, cosa mostrare di data/autore/categorie/tag, riquadro autore, articoli correlati. Tutti i valori sono theme_mod "starter_*": i default stanno accanto all'uso (inc/blog.php, inc/woocommerce.php).
- `starter_customizer_checkbox()` — Registra un'opzione con casella di spunta.
- `starter_customizer_select()` — Registra un menu a tendina; i valori ammessi sono le chiavi di $choices (il resto torna al predefinito).
- `starter_customizer_heading()` — Titoletto che divide i gruppi di opzioni di una sezione.
- `starter_customize_register_extra()` — Registra le opzioni del Customizer (Header, Footer, Prestazioni, Blog…)

### `inc/demo-content.php`
Contenuti di prova per collaudare il tema (Strumenti → Contenuti di prova). Crea, senza duplicare niente se lo rilanci: - pagine di base: Privacy Policy e Cookie Policy (bozza da completare, con tabella di CookieYes), Preferiti, Blog; - menu "Header" di prova con un sottomenu (per provare l'apertura col click nel pannello mobile); - articoli di prova con categorie, tag e immagini (per Blog, archivi, ricerca, articolo); - con WooCommerce attivo: pagine Woo (shop, carrello, cassa, account) se mancano, categorie, attributi e 12 prodotti finti con immagini segnaposto (uno in offerta, uno esaurito, due con galleria per la seconda foto all'hover). Tutto ciò che crea è marcato con il meta "_starter_demo": "Elimina i contenuti di prova" toglie solo quello (le pagine Privacy e Cookie, utili davvero, restano). Nessun testo è definitivo: sono segnaposto da sostituire.
- `starter_demo_admin_page()` — Pagina Strumenti → Contenuti di prova: pulsanti Crea/Elimina ed esito
- `starter_demo_page_by_slug()` — Pagina con questo slug, se esiste (qualunque stato tranne cestino).
- `starter_demo_page()` — Crea una pagina se manca; $demo = true la marca come contenuto di prova (si elimina con il pulsante).
- `starter_demo_image()` — Immagine segnaposto (PNG con sfondo a tinta unita e il testo) nella libreria media; 0 se GD non c'è.
- `starter_demo_create()` — ------------------------------------------------------------------- Crea
- `starter_demo_privacy_content()` — Testo di esempio della Privacy Policy
- `starter_demo_cookie_content()` — Testo di esempio della Cookie Policy
- `starter_demo_block()` — Scrive un blocco ACF nel contenuto di una pagina, con testi e immagini segnaposto. $data: nome campo => valore; $keys: nome campo => chiave ACF (serve a ACF per leggere i valori dal blocco).
- `starter_demo_posts()` — Articoli di prova: ritorna il numero creato.
- `starter_demo_products()` — Prodotti di prova con categorie e attributi: ritorna il numero creato.
- `starter_demo_menu()` — Menu "Header di prova": Home, Chi siamo, Servizi, Shop (con sottomenu), Blog (voce singola), Contatti; assegnato a Header e Footer.
- `starter_demo_delete()` — ----------------------------------------------------------------- Elimina

### `inc/duplicate.php`
Clona: voce "Clona" negli elenchi di pagine, articoli e CPT (Portfolio, Immobili…). Crea una copia in bozza con stesso titolo, contenuto, campi (ACF compresi), immagine in evidenza, categorie e genitore. Lo slug non si copia: in bozza non c'è.
- `starter_clone_post_types()` — Tipi di contenuto in cui compare "Clona". Esclusi allegati e prodotti (varianti e SKU univoco non si copiano così).
- `starter_clone_post()` — Crea la copia. Restituisce l'ID nuovo, o 0 se non riesce.
- `starter_clone_row_action()` — Voce "Clona" accanto a Modifica / Modifica rapida / Cestina / Visualizza.
- `starter_clone_handle()` — Esegue la copia e torna all'elenco.
- `starter_clone_notice()` — Avviso dopo la copia, con il collegamento per modificarla.

### `inc/fonts-local.php`
Google Fonts in locale: i font scelti in Opzioni → Tipografia (Font 1 e Font 2) vengono scaricati una volta dai server di Google e serviti dal tuo sito (cartella uploads/starter-fonts), come nei siti Di Guida e Symbolikamente. Perché: niente richiesta a un sito esterno (più veloce, niente collegamento a Google, meglio per la privacy/GDPR), `font-display: swap`, file WOFF2 con lo stesso nome per sempre e cache lunga, precaricamento del file "latin". Se il download non riesce, il tema continua a usare i Google Fonts come prima: il sito non resta mai senza font. Solo i sottoinsiemi "latin" e "latin-ext" (italiano, lingue europee); gli altri alfabeti restano su Google se servono. Opzione: Opzioni → Prestazioni → "Google Fonts serviti dal tuo sito" (accesa di base).
- `starter_local_fonts_enabled()` — Vero se l'opzione è accesa.
- `starter_google_families()` — Famiglie Google attive (Font 1 e Font 2), senza doppioni, in ordine alfabetico.
- `starter_local_fonts_paths()` — Cartella e indirizzo dei file scaricati.
- `starter_local_fonts_download()` — Scarica i font attivi. Restituisce true se tutto è andato a buon fine (e salva CSS e file da precaricare nell'opzione "starter_local_fonts"), false se qualcosa non è riuscito (in quel caso non cambia nulla e restano i Google Fonts).
- `starter_local_fonts_ready()` — Font locali pronti per le famiglie attive adesso? Restituisce l'array salvato oppure vuoto.

### `inc/header.php`
Header: opzioni e pezzi di markup condivisi (icone, ordine di social e icone, logo che cambia allo scroll). Struttura dell'header (header.php), da sinistra a destra: .site-branding logo (con eventuale icona che compare dopo lo scroll) .site-header__nav-wrap menu + CTA (su desktop sono figli diretti della riga; su mobile/Ridotto è il pannello a tendina) .site-header__tools social, divisore, icone (ricerca, account, preferiti, carrello): l'ordine si sceglie in Opzioni → Header .mobile-menu-toggle pulsante "Menu" con hamburger (solo mobile/Ridotto) Opzioni (Opzioni → Header): stile delle icone (con bordo e animazione come i social, oppure semplici), divisore tra social e icone, quale gruppo viene prima, ricerca, logo piccolo o icona allo scroll. Le classi sul body si scrivono qui sotto.
- `starter_header_icon_style()` — Stile delle icone dell'header: 'boxed' (bordo + animazione, come i social), 'plain' (solo l'icona) o 'tight' (solo l'icona, senza spazio intorno).
- `starter_header_tools_order()` — Gruppo che sta per primo (a sinistra) tra social e icone: 'social-first' o 'icons-first'.
- `starter_header_scroll_logo()` — Cosa fa il logo scrollando: 'none', 'small' (stesso logo, più piccolo) o 'icon' (icona caricata da te; ripiego su 'small' se manca).
- `starter_header_search_enabled()` — Ricerca nell'header: funziona sempre (articoli e pagine; i prodotti solo con WooCommerce).
- `starter_header_icon_btn()` — Pulsante-icona dell'header (ricerca, account, preferiti, carrello). Stessa icona sdoppiata dei social: con lo stile "con bordo" al passaggio del mouse l'icona ruota e lascia il posto al duplicato (vedi CSS ".icon-btn").
- `starter_wpml_languages()` — Lingue di WPML per il selettore dell'header (vuoto se WPML non c'è o c'è una sola lingua). Ogni voce porta alla HOME della lingua scelta (non alla traduzione della pagina corrente).
- `starter_header_lang_html()` — Selettore di lingua: pulsante col codice della lingua attiva e tooltip con l'elenco.

### `inc/icons.php`
Set di icone SVG lineari (stile Lucide) usate da template e blocchi.
- `starter_icon_paths()` — Contenuto interno degli SVG, per nome.
- `starter_icon_paths_base()` — Icone di linea del tema (nome → tracciati SVG)
- `starter_icon_choices()` — Scelte per i campi ACF "Icona" (es. voci della Sezione voci in stile "icone").
- `starter_icon_data()` — Legge un blocco di dati (una lettera, "brands" o "index") una sola volta per richiesta.
- `starter_icon_is_brand()` — Vero per le icone dei social (nome che inizia per brand-)
- `starter_icon_is_custom()` — Icone caricate dall'utente (Libreria media → SVG): si chiamano "svg-123" (123 = ID dell'allegato).
- `starter_icon_svg_parts()` — Un SVG della Libreria media ripulito e pronto per essere disegnato inline come icona: array( vb, paint (attributi), inner ), oppure null. Il risultato è messo in cache nei meta dell'allegato (si rifà se il file cambia).
- `starter_icon_inner()` — Contenuto interno dell'SVG per nome, oppure '' se l'icona non esiste.
- `starter_icon_exists()` — Vero se l'icona esiste nella libreria
- `starter_icon()` — Restituisce l'icona come SVG inline (decorativo, aria-hidden).
- `starter_icon_library()` — Tutta la libreria per i selettori (backend ACF e plugin Starter Live): { icons: { nome: markup }, keywords: { nome: "parole" } }. Le icone personalizzate del tema sostituiscono quelle omonime della libreria.
- `starter_icon_svg_entry()` — Dati di un SVG caricato per i selettori: markup, viewBox, attributi e parole chiave (titolo del file).

### `inc/media-modern.php`
Formati moderni: immagini AVIF (ripiego WebP), video WebM (ripiego mp4), favicon .ico e icone per Google, Android e Apple.
- `starter_upload_path_from_url()` — Da URL di un file in uploads al percorso sul disco ('' se l'URL è di un altro sito o fuori da uploads).
- `starter_avif_quality()` — Qualità AVIF (Opzioni → Prestazioni): 70 dà un peso ben più basso del WebP a 80 senza differenze visibili.
- `starter_avif_supported()` — Vero se il server sa creare AVIF (GD con libavif).
- `starter_avif_url()` — URL dell'.avif che sta accanto a un'immagine (stesso nome), solo se il file esiste davvero; altrimenti ''.
- `starter_generate_avif()` — Crea l'.avif accanto a un file immagine (stesso nome). Lo tiene solo se pesa almeno il 3% meno del file di partenza. Non tocca favicon, logo delle email, file "nowebp" e SVG/GIF. Restituisce true se l'.avif esiste a fine lavoro.
- `starter_generate_avif_for_attachment()` — Crea gli .avif dell'originale e di tutte le misure di un allegato. Restituisce quanti ne ha creati.
- `starter_picture_off()` — Interruttore: nelle email di WooCommerce niente <picture> (i programmi di posta non lo capiscono).
- `starter_picture_skip()` — Vero per anteprime dell'editor, feed e REST: lì le immagini restano <img> semplici.
- `starter_picturefy()` — Avvolge ogni <img> di uploads in <picture> con la sorgente AVIF: il browser sceglie da solo (AVIF se lo legge, altrimenti l'<img> WebP). Ignora le immagini già dentro un <picture> e quelle senza .avif sul disco. Opzione: Prestazioni → Immagini AVIF.
- `starter_video_sources()` — Righe <source> di un video: se accanto all'mp4 c'è un .webm con lo stesso nome (o viceversa) mette prima il WebM, poi l'mp4. Il browser usa il primo formato che sa leggere. Senza file gemello restituisce la sola sorgente originale. Opzione: Prestazioni → Video WebM.
- `starter_icons_dir()` — Cartella delle icone generate (uploads/starter-icons).
- `starter_icon_png()` — Immagine quadrata (centrata, ritagliata) di lato $side con sfondo trasparente, come dati PNG; '' se non riesce.
- `starter_build_ico()` — Contenitore .ico con immagini PNG già pronte (array lato => dati PNG): lo leggono tutti i browser moderni.
- `starter_generate_icons()` — Dal Site Icon (Aspetto → Personalizza → Identità del sito, 512×512) genera in uploads/starter-icons: favicon.ico (16, 32, 48, 256 px), icon-192.png e icon-512.png (Google e Android), apple-touch-icon.png (180 px, Apple) e site.webmanifest. Sono sempre PNG/ICO, qualunque sia il formato del file caricato. Rigenera solo se l'icona è cambiata.
- `starter_icons_ready()` — Vero se le icone sono pronte e l'opzione è accesa.

### `inc/media-optimizer.php`
Ottimizzazione delle immagini già in Libreria media: crea gli AVIF mancanti e converte JPG/PNG rimasti in WebP (pagina Media → Ottimizza immagini e comandi WP-CLI).
- `starter_media_stats()` — Fotografia della libreria: quali immagini si possono ottimizzare e a che punto sono. Restituisce id da fare per l'AVIF ('avif_ids'), id JPG/PNG da convertire ('webp_ids'), tutti gli id ottimizzabili ('all_ids') e i video.
- `starter_replace_in_value()` — Sostituisce $map (vecchio => nuovo) dentro un valore, anche se è un array serializzato (le lunghezze si ricalcolano).
- `starter_replace_urls_in_db()` — Cambia nel database (articoli, campi personalizzati, opzioni, meta dei termini) gli indirizzi dei file convertiti. $stem: parte iniziale comune, es. "/2026/08/foto".
- `starter_convert_attachment_to_webp()` — Converte un allegato JPG/PNG in WebP: nuovo file e nuove misure, aggiorna la libreria, sostituisce i vecchi indirizzi nei contenuti e solo alla fine cancella i vecchi file. Se qualcosa va storto l'originale resta com'era. Restituisce true se convertito.
- `starter_media_fix_mimes()` — Allegati con estensione .webp ma tipo MIME vecchio (es. image/png): si riallinea il tipo. Restituisce quanti ne ha sistemati.
- `starter_media_run_batch()` — Esegue un lotto del compito 'avif' o 'webp' entro $budget secondi. Con $force (solo avif) rifà anche gli .avif già creati, partendo da $offset. Restituisce array( fatti, rimasti, prossimo offset ).
- `starter_media_optimize_page()` — Disegna la pagina Media → Ottimizza immagini (conteggi, pulsanti e barra di avanzamento)
- `class Starter_Media_CLI` — Ottimizzazione immagini del tema.
- `status()` — Mostra a che punto è la libreria.
- `avif()` — Crea gli AVIF mancanti. ## OPTIONS [--force] : Rifà anche gli AVIF già creati.
- `webp()` — Converte in WebP i JPG e PNG rimasti (fai prima un backup).
- `loop()` — Ripete i lotti finché non resta nulla da fare

### `inc/menu.php`
Menu principale: voci con sottomenu. Con l'opzione "Le voci con sottomenu si aprono col click" (Opzioni → Header, attiva di base) le voci di primo livello che hanno sottovoci non sono link ma pulsanti con la freccia a sinistra: - desktop: il sottomenu si apre all'hover e al click/tastiera, si richiude con un secondo click, Esc o clic fuori; - pannello mobile (e header "Ridotto"): sottomenu CHIUSI, si aprono e chiudono toccando la voce. La logica è in initSubmenus() di JS/scripts.js; l'aspetto in CSS/starter.css ("Menu principale"). Nota: se la voce madre ha una pagina propria, aggiungila anche come prima sottovoce (es. "Vedi tutto"), perché il click sulla madre apre il sottomenu e non porta alla pagina. Spegnendo l'opzione, le voci tornano link normali con sottomenu all'hover (e sempre aperto nel pannello mobile).
- `starter_submenu_toggle_markup()` — Aggiunge ai link con sottomenu il pulsante freccia (apre il sottomenu al clic)
- `starter_footer_menu_title()` — Titolo di una colonna menu del footer = nome del menu assegnato (Aspetto → Menu), tradotto da WPML; ripiego sul titolo predefinito.
- `starter_footer_free_html()` — Spazio libero del footer (al posto della vecchia newsletter finta), uguale in tutti gli stili: titolo facoltativo sottotitolo facoltativo e contenuto scritto in Opzioni → Footer → "Spazio libero". Può essere uno shortcode (es. il form di Contact Form 7 o di un servizio di newsletter), ma anche testo, un link, un'immagine: qualunque HTML. Se il contenuto è vuoto non si stampa nulla e il footer resta senza quella colonna.
- `starter_footer_shop_links()` — Pagine interne dello shop per il footer (Stile 4, quando non c'è un secondo menu): Negozio, Preferiti, Carrello, Account.

### `inc/modules.php`
Moduli (CPT): funzioni facoltative del tema che si accendono e si spengono da Personalizza → Opzioni → Moduli (CPT). Ogni modulo è una cartella/un file a parte (CPT, tassonomie, campi, blocchi, template, CSS). Se il modulo è spento il suo codice non viene nemmeno caricato: niente CPT, campi, blocchi, CSS, JS né query in più. L'interruttore sta nei theme_mod, che WordPress legge comunque una volta per richiesta con le altre opzioni del tema. Aggiungere un modulo = una voce in starter_modules() (+ i suoi file). L'interruttore compare da solo nel Personalizza. Quando un modulo si accende o si spegne (alla richiesta dopo il salvataggio): - gli indirizzi del sito si aggiornano (una volta sola); - se il modulo ha una pagina (es. "Portfolio", "Immobili") la si crea, o si ripubblica se l'aveva messa in bozza il tema; - spegnendo, le pagine create dal tema per quel modulo tornano in bozza (il link smette di funzionare); i contenuti restano nel database.
- `starter_modules()` — Elenco dei moduli. - label, description: testo dell'interruttore (stringhe semplici, tradotte quando si mostrano: il registro si legge prima di init); - file: file da caricare quando il modulo è acceso (relativo al tema); - blocks: cartelle di template-parts/ dei blocchi del modulo (si registrano solo a modulo acceso); - page: pagina creata all'attivazione (slug, title, block = blocco che la identifica, content = funzione che ne scrive il contenuto); - demo: funzione dei contenuti di prova (lanciata da Strumenti → Contenuti di prova); - activate: funzione lanciata quando il modulo si accende (es. crea un modulo di contatto); - default: acceso di partenza (no: ogni sito accende solo ciò che gli serve).
- `starter_module_active()` — Il modulo è acceso?
- `starter_modules_active()` — Moduli accesi: id => definizione.
- `starter_modules_inactive_blocks()` — Cartelle dei blocchi dei moduli spenti (starter_theme_register_acf_blocks le salta).
- `starter_module_ensure_page()` — Pagina del modulo creata dal tema (o già presente con il suo blocco): la crea se manca, la ripubblica se l'aveva messa in bozza il tema.
- `starter_module_draft_pages()` — Modulo spento: le sue pagine create dal tema tornano in bozza (il link smette di funzionare); i contenuti restano.

### `inc/performance.php`
Prestazioni: immagini WebP, pulizia dell'head, script solo dove servono.
- `starter_is_favicon_file()` — Vero se il file è il favicon: nome con "favicon", oppure il Site Icon impostato in Personalizza → Identità del sito (che online diventa i vari <link rel="icon">/apple-touch-icon in <head>). In entrambi i casi deve restare .png: alcuni browser e Google Search non riconoscono un'icona .webp, un nome file non è garanzia sufficiente da solo.
- `starter_keep_original_format()` — Vero se il file deve restare nel formato originale (niente WebP): - il favicon (vedi sopra); - il logo delle email di WooCommerce (Outlook desktop non mostra i WebP): è il file impostato in WooCommerce → Impostazioni → Email → Immagine dell'intestazione, oppure un file con "email" nel nome; - qualsiasi file con "nowebp" nel nome.
- `starter_keep_format_flag()` — Quando WordPress crea le misure ridotte non passa il nome del file al filtro del formato: si ricorda qui se l'immagine in lavorazione deve restare nel formato originale (favicon, logo email, "nowebp"), impostato da "intermediate_image_sizes_advanced" qui sotto.
- `starter_convert_to_webp()` — Converte in WebP (qualità 80) un file JPG/PNG, girandolo secondo l'EXIF delle foto da telefono. Restituisce il percorso del .webp oppure '' se non si può (favicon, logo email, formato non supportato, errore).
- `starter_preload()` — Precarica i font del tema dichiarati in theme.json (se locali): niente flash di testo non stilizzato.
- `starter_clean_head()` — Pulizia dell'head e degli asset di WordPress non usati (wp_generator/wlwmanifest/rsd già rimossi da starter_theme_cleanup_head in functions.php).
- `starter_dequeue_assets()` — Toglie stili e script di WordPress che il tema non usa
- `starter_page_has_form()` — Contact Form 7: CSS e JS solo nelle pagine che hanno un modulo (nel testo, in un blocco del tema o nel footer). Spegnibile da Opzioni → Prestazioni.
- `starter_preload_hero_image()` — Immagine di apertura (LCP) precaricata: se la pagina comincia con una hero con foto, il browser la scarica subito (con la misura giusta per lo schermo) invece di scoprirla a pagina disegnata.
- `starter_htaccess_rules()` — Righe da scrivere nel .htaccess: compressione (Brotli e Gzip), cache lunga del browser per file statici e immagini, tipi dei file moderni. L'HTML non viene messo in cache. Funziona su Apache e LiteSpeed (es. Hostinger); su nginx (es. Local by Flywheel) il file è ignorato.
- `starter_apply_htaccess()` — Scrive (o toglie) il blocco nel .htaccess del sito, senza toccare le regole di WordPress o di altri plugin. Restituisce true se il file è stato aggiornato.

### `inc/portfolio.php`
Portfolio: progetti (CPT "portfolio"), categorie, blocco "Elenco progetti" e pagina del singolo progetto. Modulo del tema (vedi inc/modules.php): si accende e si spegne da Opzioni → Moduli (CPT). Spento, questo file non viene caricato. - Nessun archivio: l'elenco dei progetti è il blocco "Elenco progetti" (template-parts/progetti), da mettere in una pagina normale creata da te (titolo, testi e altri blocchi sono liberi). Filtro per categoria e pagine con ?categoria= e ?pagina=. - Singolo progetto: single-portfolio.php (testata + copertina + contenuto con i blocchi "Progetto — …" + altri progetti) - Blocchi dei contenuti: Progetto — testo, Progetto — media (immagini e video in 1/2/3 colonne), Progetto — crediti - Contenuti di prova: starter_portfolio_demo() (lanciata da Strumenti → Contenuti di prova)
- `starter_portfolio_defaults()` — Valori predefiniti (le chiavi sono i nomi dei theme_mod senza prefisso "starter_portfolio_"). L'aspetto dell'elenco (colonne, schede…) è nei campi del blocco.
- `starter_portfolio_opt()` — Valore di un'opzione del Portfolio (o il suo predefinito)
- `starter_portfolio_enabled()` — Vero se il modulo Portfolio è acceso (Opzioni → Moduli)
- `starter_portfolio_page_id()` — Pagina che contiene il blocco "Elenco progetti" (la prima pubblicata): serve per le briciole e per "Tutti i progetti". 0 se non c'è.
- `starter_portfolio_url()` — Indirizzo della pagina Portfolio ('' se non esiste)
- `starter_portfolio_margin_choices()` — Margini delle categorie: le classi già presenti nel CSS (.mt05, .mt1, … e .mb05, .mb1, …). '' = nessuna classe (margine 0).
- `starter_portfolio_card_config()` — Opzioni delle card (categorie, riassunto e cliente, video, animazione logo, colori) lette da una sorgente: i campi del blocco "Elenco progetti" ('get_field') oppure le opzioni del tema per gli "Altri progetti" (vedi starter_portfolio_related_get()). $get riceve il nome del campo (gli stessi nomi dei campi del blocco) e restituisce il valore, o null se non è mai stato impostato: allora valgono i predefiniti. Restituisce: cats (stile), cats_opts, titles, video, reveal, style (variabili CSS dei colori delle categorie).
- `starter_portfolio_hover_video()` — Video mostrato sopra l'immagine al passaggio del mouse: il video di copertina del progetto. Restituisce l'URL o ''.
- `starter_portfolio_logo_ratio()` — Proporzione (larghezza / altezza) di un logo. Gli SVG non hanno dimensioni nella Libreria media: si leggono dal file (width e height, oppure viewBox). 0 se non si riesce.
- `starter_portfolio_reveal_html()` — Animazione logo di una card: al centro dell'immagine il logo del cliente (se manca, il nome in testo); passando il mouse il logo sparisce e il nome del cliente, in grande, scorre da destra a sinistra (senso di lettura). $opts: tint (logo nel colore dello sfondo del sito), color (colore esadecimale facoltativo).
- `starter_portfolio_grid_html()` — Griglia dei progetti nella visualizzazione scelta. $cards: HTML delle card (già scappato). $s: cols, cols_t, cols_m, gap_x, gap_y, ratio, fourth. classica = colonne con immagini della stessa altezza; masonry = colonne CSS con il formato originale; blocchi = gruppi da 3 (2 orizzontali + 1 verticale, poi invertiti) con un quarto elemento facoltativo a tutta larghezza; sfalsata = 2 colonne con offset e larghezze diverse.
- `starter_portfolio_transition_config()` — Transizione tra elenco e progetto (scheda Animazioni del blocco "Elenco progetti"): nessuna | cover | dissolvenza | tendina. Sulla pagina dell'elenco vale la scelta del blocco; sulla scheda del progetto, quella del blocco nella pagina Portfolio. Usa le View Transitions tra pagine (Chrome, Edge, Safari recenti): dove non ci sono, si naviga normalmente.
- `starter_portfolio_transition()` — Solo il tipo di transizione (nessuna | cover | tendina | dissolvenza).
- `starter_portfolio_color_choices()` — Menu colori dei campi del blocco: la prima voce è "Predefinito" (breve, entra nel pannello stretto).
- `starter_portfolio_client_name()` — Nome del cliente di un progetto: è il titolo del progetto (non c'è un campo a parte). Lo usano le animazioni e la riga "cliente" delle card.
- `starter_portfolio_hover_color_choices()` — Colori con nome per l'hover: "Nessuno" = il colore non cambia (si potrà usare un effetto grafico al suo posto).
- `starter_portfolio_tag_choices()` — Tag HTML scelti per riassunto e cliente: tutti i titoli, paragrafo e span (si sceglie in base a dove sta il blocco nella pagina).
- `starter_portfolio_font_size_choices()` — Misure per i menu di dimensione: titoli del tema e misure di Gutenberg, con i px (minimo–massimo) letti da theme.json.
- `starter_portfolio_size_class()` — Classe CSS di una misura: i titoli usano le classi .title-hN del tema, le misure di Gutenberg .has-…-font-size.
- `starter_portfolio_titles_html()` — Riassunto (tipo di lavoro) e nome del cliente di una card, nell'ordine, tag, misura e colore scelti nel blocco "Elenco progetti". Il riassunto è l'estratto del progetto; il cliente è il campo "Cliente" (se vuoto, il titolo). Se si spengono entrambi resta il titolo solo per chi usa lo screen reader, così il link ha sempre un nome. $where: 'sotto' o 'sopra' l'immagine (la chiamata "sotto" stampa anche il titolo nascosto).
- `starter_portfolio_separator_choices()` — Simboli separatori per i campi del blocco (stesso set della barra annunci).
- `starter_portfolio_cats()` — Categorie di un progetto (nomi).
- `starter_portfolio_cats_html()` — Categorie di una card, nello stile scelto nel blocco "Elenco progetti". $style: testo | tab_sotto | tab_sopra; $opts: separator (chiave del set), fill (piene|vuote), shape (singole|unica), upper (maiuscolo), mt/mb (classi .mt…/.mb… sotto l'immagine). Restituisce l'HTML (vuoto se non ci sono categorie). Le categorie non sono link: la card intera è già un link.
- `starter_portfolio_tabs()` — Schede delle categorie del blocco "Elenco progetti": "Tutti" + una per categoria. Sono link alla stessa pagina con ?categoria=, funzionano senza JavaScript.
- `starter_portfolio_related()` — Progetti correlati in fondo alla scheda di un progetto
- `starter_portfolio_related_defaults()` — "Altri progetti" a fine scheda: le card si scelgono qui, con gli stessi nomi e valori dei campi del blocco "Elenco progetti" (tranne il layout, sempre a 3 colonne).
- `starter_portfolio_related_get()` — Legge un'opzione delle card degli "Altri progetti" (stessa firma di get_field, da passare a starter_portfolio_card_config()).
- `starter_portfolio_page_content()` — Contenuto della pagina Portfolio creata dal modulo: intestazione e blocco "Elenco progetti".
- `starter_portfolio_demo()` — Categorie e progetti di esempio: 3 schede complete con strutture diverse (identità visiva, sito web con video, logo e grafica) e 6 più brevi per riempire l'archivio. Immagini segnaposto; i video sono quelli di esempio del tema. Si può rilanciare senza duplicare; "Elimina i contenuti di prova" li toglie.
- `starter_portfolio_preview_marker()` — Marcatore del blocco anteprima: sta solo nell'editor. Si aggiunge all'apertura del progetto e si toglie al salvataggio, così il contenuto nel database resta pulito.

### `inc/search.php`
Ricerca del sito (lente nell'header): pannello a tutta larghezza con anteprima dei risultati mentre si scrive, divisi per tipo: Prodotti (solo con WooCommerce, anche per SKU), Pagine, Articoli e gli eventuali altri contenuti pubblici del sito. Niente plugin: un endpoint REST pubblico (/wp-json/starter/v1/search?q=…) e initSearchPanel() in JS/scripts.js. Invio porta alla normale pagina dei risultati (search.php), quindi funziona anche senza JS. Si attiva da Opzioni → Header → "Mostra la ricerca". Funziona con o senza WooCommerce.
- `starter_search_item()` — Un risultato nel formato letto dal JS.
- `starter_search_extra_types()` — Gli altri contenuti pubblici cercabili (tipi di contenuto personalizzati), oltre a prodotti, pagine e articoli.
- `starter_search_ids()` — Ricerca per un tipo di contenuto: ID dei risultati.
- `starter_search_groups()` — Risultati raggruppati per tipo, per l'anteprima (poche voci) e per un eventuale elenco completo ($full = true).
- `starter_search_rest()` — Risposta REST della ricerca: gruppi di risultati per tipo di contenuto
- `starter_search_tip()` — Frase del campo: elenca solo ciò che si può davvero cercare.
- `starter_search_panel()` — Pannello di ricerca: si apre dalla lente dell'header (initSearchPanel in JS/scripts.js).

### `inc/shop-filters.php`
Filtri del negozio, sullo schema dei filtri di Di Guida Real Estate: form GET che ricarica la pagina (niente AJAX, URL condivisibili), parametri con prefisso f_, su tablet/mobile un pannello che entra da sinistra aperto dal pulsante "Filtri". Si attivano da Opzioni → Shop → Filtri (solo con WooCommerce). Di base compaiono nella pagina Shop; con l'opzione dedicata anche nelle pagine delle categorie (che hanno sempre l'ordinamento). I filtri nascono dai dati dei prodotti, niente di inventato, e ognuno si accende o spegne dalle opzioni: - Cerca: ricerca sui soli prodotti, con suggerimenti mentre scrivi (Store API di Woo); - Categoria: categorie prodotto (nelle pagine di una categoria, le sue sottocategorie); - Prezzo: da / a, sul prezzo del prodotto; - Attributi: un filtro per ogni attributo globale di WooCommerce (Prodotti → Attributi) che ha almeno un prodotto assegnato (es. Materiale, Colore). Senza prodotti non compare.
- `starter_shop_filter_param()` — Nome del parametro URL di un filtro.
- `starter_shop_filters_enabled()` — Pagina del negozio in cui ci sono i filtri: lo Shop, e (se scelto nelle opzioni) le pagine delle categorie.
- `starter_shop_filters()` — Configurazione dei filtri attivi in questa pagina, nell'ordine del form.
- `starter_shop_filter_term_options()` — Opzioni slug => nome di una tassonomia (solo termini con prodotti), con rientro per i livelli.
- `starter_shop_filter_number()` — Numero da un parametro, '' se vuoto o non valido.
- `starter_shop_filters_values()` — Valori dei filtri letti dall'URL e sanitizzati (solo opzioni esistenti).
- `starter_shop_filter_is_active()` — True se il filtro ha un valore.
- `starter_shop_filters_active_count()` — Numero di filtri attivi (per il badge del pulsante "Filtri").
- `starter_shop_filters_tax_query()` — Filtri di tassonomia applicati alla query dei prodotti di WooCommerce.
- `starter_shop_filters_meta_query()` — Filtro prezzo applicato alla query dei prodotti (prezzo attuale, anche in offerta).
- `starter_shop_filters_search()` — Ricerca testuale sui soli prodotti (campo "Cerca" dei filtri).
- `starter_shop_filters_reset_url()` — URL della pagina senza filtri (per "Azzera i filtri"), mantenendo l'ordinamento scelto.
- `starter_shop_filters_render()` — Stampa il form dei filtri (desktop: riquadro sopra la griglia; tablet/mobile: pannello a scomparsa).
- `starter_shop_filters_toggle()` — Pulsante "Filtri" (tablet/mobile), allineato all'ordinamento.
- `starter_catalog_orderby_labels()` — Ordinamento in italiano (le etichette di WooCommerce restano in inglese senza il pacchetto di traduzione).
- `starter_shop_result_count()` — Numero di risultati, in italiano.

### `inc/shop-setup.php`
Impostazioni di partenza del negozio: spedizione in Italia e avviso "negozio in test". - Spedizione Italia: 5 € a ordine, gratis da 99 € di prodotti. Quando la spedizione gratuita è disponibile il costo fisso non viene proposto (starter_shop_hide_flat_rate). Si crea da Strumenti → Contenuti di prova (o con starter_shop_setup_shipping()); poi si cambia da WooCommerce → Impostazioni → Spedizione. - Negozio in test: pagamento alla consegna attivo per provare un ordine intero e una barra con l'avviso su tutte le pagine (Opzioni → Shop → "Negozio in test"). Da spegnere prima di andare online.
- `starter_shop_setup_shipping()` — Zona "Italia" con spedizione a 5 € e spedizione gratuita sopra i 99 €. Non tocca una zona Italia già esistente.
- `starter_shop_setup_test()` — Negozio in test: attiva il pagamento alla consegna (per completare un ordine di prova) e l'avviso su tutte le pagine.
- `starter_shop_page_intro()` — Testo sotto il titolo della pagina Shop: se la pagina Shop non ha testo ne mette uno di esempio (si cambia dalla pagina).

### `inc/starter-pages.php`
Pagine standard di partenza: Home, Chi siamo, Servizi, Contatti. Costruite con i blocchi ACF del tema (template-parts/) e con blocchi di Gutenberg: ogni sezione è un blocco ACF oppure un Gruppo (blocco "Gruppo", tag "section"), così la pagina si legge e si riordina per sezioni. I testi e le foto sono segnaposto da sostituire. Si creano da Strumenti → Contenuti di prova → "Crea le pagine standard" e non si cancellano con "Elimina i contenuti di prova" (sono pagine vere, da riscrivere). La Home diventa la pagina iniziale del sito; Contatti usa il primo modulo di Contact Form 7 che trova.
- `starter_pg_h()` — Titolo.
- `starter_pg_p()` — Paragrafo.
- `starter_pg_buttons()` — Pulsanti: $items = [ [ testo, url, 'outline' (facoltativo) ], ... ].
- `starter_pg_group()` — Sezione = blocco Gruppo (tag section, a tutta larghezza, contenuto nella larghezza del sito).
- `starter_pg_link()` — Link nel formato dei campi "Link" di ACF.
- `starter_pg_block_inner()` — Blocco ACF con dei blocchi di Gutenberg dentro (es. "Sezione affiancata").
- `starter_pg_keys()` — Chiavi dei campi ACF dei blocchi, per scrivere i valori nei blocchi (vedi inc/acf-blocks*.php).
- `starter_pg_block()` — Blocco ACF "semplice" (senza blocchi dentro) con le sue chiavi.
- `starter_pg_cta()` — Sezione finale "contattaci": Gruppo che contiene una Copertina con l'immagine di sfondo in parallasse (fissa mentre si scorre), velo scuro, titolo, testo e pulsante, con molto spazio sopra e sotto.
- `starter_cf7_privacy_line()` — Testo di accettazione privacy per i moduli creati dal tema: con link alla pagina Privacy se c'è.
- `starter_contact_form_create()` — Modulo "Contatti" di Contact Form 7 (nome, email, oggetto, messaggio e accettazione privacy), per la pagina Contatti. Se manca lo crea; il modulo di esempio di Contact Form 7 non ha l'accettazione della privacy.
- `starter_pg_form_shortcode()` — Shortcode del modulo Contatti (lo crea se serve), '' se il plugin non c'è.
- `starter_pg_video()` — Video di esempio (Mixkit, licenza gratuita anche commerciale) dalla cartella images del tema (verticale per Chi siamo, orizzontale per la hero con video): lo importa nella libreria media una volta sola.
- `starter_pg_images()` — Foto segnaposto per le pagine (restano anche eliminando i contenuti di prova).
- `starter_pg_home()` — Contenuto della pagina Home standard
- `starter_pg_about()` — Contenuto della pagina Chi siamo
- `starter_pg_services_list()` — Blocco "Elenco servizi" di esempio (5 servizi con menu laterale, foto, elenco con spunte e pulsante).
- `starter_pg_numbered_reasons()` — Blocco "Sezione voci" con layout "Elenco numerato": 5 ragioni con testo introduttivo e pulsante a fianco.
- `starter_pg_services()` — Contenuto della pagina Servizi
- `starter_pg_credits()` — Pagina Credits: titolo, frase e un elenco con il ruolo di chi ha realizzato il sito.
- `starter_pg_contacts()` — Contenuto della pagina Contatti
- `starter_pages_create()` — Crea (o aggiorna se ancora vuote) le pagine standard e imposta la Home come pagina iniziale.
- `starter_newsletter_form_create()` — Modulo "Newsletter" di Contact Form 7 (nome, email, accettazione privacy) per lo spazio libero del footer. Se manca lo crea e, se lo spazio libero del footer è vuoto, ci mette il suo shortcode con titolo e sottotitolo. Le iscrizioni arrivano per email e in Flamingo (con la pagina di provenienza, vedi inc/cf7.php).
- `starter_pages_link_footer()` — Collega nel footer i link Privacy Policy e Cookie Policy alle pagine create dal tema (solo se i campi sono vuoti).
- `starter_wishlist_page_content()` — Contenuto della pagina Preferiti: titolo come Chi siamo (occhiello, titolo, testo), l'elenco e il banner finale.
- `starter_wishlist_page_create()` — Pagina "Preferiti" (shortcode [starter_preferiti]): la crea se manca (o se ha solo lo shortcode) e la collega in Opzioni → Shop.

### `inc/topbar.php`
Barra annunci sopra l'header: una fascia con testi che scorrono in continuazione (marquee), accesa da Opzioni → Barra annunci. Markup: .topbar fascia (in cima alla pagina, scorre via con la pagina; l'header fisso la segue) .topbar__inner <a> se c'è un link, altrimenti <div> .topbar__track due gruppi identici uno dopo l'altro: l'animazione sposta la pista di un gruppo .topbar__group > .topbar__item (+ .topbar__sep) Altezza, scorrimento dell'header e animazione: CSS/starter.css (sezione "Barra annunci") e initTopBar() in JS/scripts.js.
- `starter_topbar_separators()` — Simboli separatori: li usano la barra annunci (tra un annuncio e il successivo) e le categorie delle card del Portfolio.
- `starter_topbar_active()` — Barra accesa e con almeno un annuncio.
- `starter_topbar_messages()` — Annunci: uno per riga.

### `inc/wishlist.php`
Preferiti (cuore sulle card prodotto). L'elenco sta nel browser del visitatore (localStorage "starter-wishlist", gestito da initWishlist in JS/scripts.js): niente account, niente database. La pagina "Preferiti" contiene lo shortcode [starter_preferiti]: il JS chiede qui (admin-ajax, azione starter_wishlist) le card dei prodotti salvati, le stesse del negozio, e le toglie dalla pagina quando si spegne il cuore. L'icona cuore nell'header porta alla pagina scelta in Opzioni → Shop ("Pagina dei preferiti"); se non è scelta, alla pagina con indirizzo /preferiti/ (creata dai contenuti di prova).
- `starter_wishlist_url()` — Indirizzo della pagina Preferiti ('' se non esiste).
- `starter_wishlist_count_html()` — Badge con il numero di preferiti nell'header: il numero lo scrive il JS (il server non conosce l'elenco).
- `starter_wishlist_ids_from_request()` — Elenco di ID dal parametro "ids" (solo numeri, max 100).
- `starter_wishlist_ajax()` — Card dei prodotti salvati (stesso markup del negozio: woocommerce/content-product.php).
- `starter_wishlist_tip_ajax()` — Tooltip dei preferiti nell'header: foto, nome e prezzo dei prodotti salvati (max 5). L'elenco arriva dal browser.

### `inc/woocommerce.php`
WooCommerce: supporto del tema, CSS di Woo solo dove serve, icone shop nell'header (account, preferiti, carrello con tooltip), card prodotto unica, scheda prodotto. Tutto quello che segue è inerte se WooCommerce non è installato: le opzioni "Shop" e le icone dell'header compaiono nel Customizer solo con Woo attivo (vedi inc/customizer-shop.php).
- `starter_has_woocommerce()` — Vero se WooCommerce è attivo.
- `starter_is_woocommerce_page()` — Vero nelle pagine del negozio (shop, categorie, prodotto, carrello, cassa, account).
- `starter_needs_shop_css()` — Vero se in questa pagina servono gli stili dello shop (CSS/shop.css): pagine Woo, pagina Preferiti, card prodotto.
- `starter_woocommerce_setup()` — Supporto del tema: galleria prodotto (zoom, lightbox, slider) e dimensioni immagini delle card (quadrate 600).
- `starter_woocommerce_styles()` — Fogli di stile di Woo: "layout" e "smallscreen" sostituiti dal CSS del tema (griglia e card sono nostre); "general" resta solo nelle pagine del negozio, non in home.
- `starter_woocommerce_scripts()` — Script di Woo che aggiornano il carrello nell'header: - "wc-add-to-cart": aggiunta AJAX dal pulsante della card (evento "added_to_cart"); - "wc-cart-fragments": dopo l'aggiunta (e al cambio pagina) sostituisce il badge e il tooltip del carrello. Da WooCommerce 7.8 "wc-cart-fragments" non si carica più da solo su tutte le pagine.
- `starter_header_shop_enabled()` — Icone shop nell'header, attivabili una per una da Opzioni → Header (solo con Woo attivo). $which: 'account' | 'wishlist' | 'cart'.
- `starter_header_shop_any()` — Vero se almeno una delle icone shop è attiva (per sapere se stampare il contenitore).
- `starter_cart_count()` — Numero di articoli nel carrello (0 se Woo non è attivo o la sessione non c'è).
- `starter_cart_count_html()` — Badge del conteggio: nascosto a 0; stesso markup nel primo caricamento e nel frammento AJAX.
- `starter_cart_label()` — Etichetta accessibile del link carrello, aggiornata insieme al numero.
- `starter_cart_tip_html()` — Tooltip del carrello nell'header: gli articoli dentro (max 5), con foto, quantità e prezzo, più il totale.
- `starter_cart_fragments()` — Frammenti AJAX: badge, etichetta e tooltip del carrello nell'header.
- `starter_shop_urls()` — URL utili all'header (vuoti se Woo non è attivo).
- `starter_product_categories()` — Categorie del prodotto per la card (prima la sottocategoria, poi la madre), massimo 2; esclusa "Uncategorized".
- `starter_product_hover_image_id()` — Seconda immagine (prima della galleria) per il cambio foto all'hover della card.
- `starter_product_card_text()` — Testo breve della card: descrizione breve senza HTML, max ~110 caratteri.
- `starter_loop_add_to_cart_link()` — Pulsante "Aggiungi al carrello" della card con l'animazione del tema (testo che ruota).
- `starter_register_invoice_fields()` — Cassa a blocchi: dati per la fattura (partita IVA, codice fiscale, codice SDI, PEC), tutti facoltativi. Woo li salva nell'ordine con i dati di fatturazione e li mostra nella scheda dell'ordine in admin (sezione Fatturazione, modificabili), in "Ordine ricevuto" e nelle email. Attivi di base; si spengono da Opzioni → Shop.
- `starter_shop_columns()` — Colonne della griglia prodotti (Opzioni → Shop → Colonne): classe sul body nelle pagine Shop e categorie.

### `inc/modules/immobili/immobili-cpt.php`
Custom Post Type "Immobile"
- `starter_imm_register_immobile_cpt()` — Registra il CPT "immobile".
- `starter_imm_immobile_admin_columns()` — Colonne dell'elenco admin
- `starter_imm_immobile_admin_column_content()` — Contenuto delle colonne personalizzate.
- `starter_imm_immobile_admin_evidenza_filter()` — Filtro a tendina "Evidenza" sopra l'elenco admin degli immobili.
- `starter_imm_immobile_admin_evidenza_query()` — Applica il filtro Evidenza alla query dell'elenco admin (query_var disattivato sulla

### `inc/modules/immobili/immobili-filters.php`
Filtri dell'elenco immobili (blocco "Elenco immobili").
- `starter_imm_immobili_filters()` — Configurazione dei filtri, nell'ordine in cui compaiono nel form.
- `starter_imm_filter_param()` — Nome del parametro URL
- `starter_imm_filter_number()` — Valore numerico dal parametro, o '' se vuoto o non valido.
- `starter_imm_filters_get_values()` — Legge e sanitizza i valori dei filtri dall'URL ($_GET).
- `starter_imm_filter_is_active()` — True se il filtro ha un valore.
- `starter_imm_filters_active_count()` — Numero di filtri attivi.
- `starter_imm_filters_query_args()` — Argomenti WP_Query per i valori dei filtri.
- `starter_imm_filters_current_page()` — Pagina corrente dell'elenco (parametro f_pagina).
- `starter_imm_filter_term_options()` — Opzioni di una tassonomia per una select
- `starter_imm_filters_render_form()` — Stampa il form dei filtri (GET, ricarica la pagina, nessun AJAX).

### `inc/modules/immobili/immobili-helpers.php`
Funzioni di supporto per gli immobili
- `starter_imm_format_price()` — "1300000" → "€ 1.300.000".
- `starter_imm_get_price_label()` — Prezzo dell'immobile formattato, oppure "Trattativa riservata" se il campo è vuoto.
- `starter_imm_field()` — Legge un campo ACF; se ACF non è attivo ripiega sul meta grezzo, così il tema non va in
- `starter_imm_get_page_title_html()` — Titolo mostrato nella scheda
- `starter_imm_get_zone_label()` — Etichetta zona per card e scheda
- `starter_imm_get_term_name()` — Nome del primo termine di una tassonomia dell'immobile ('' se assente).
- `starter_imm_get_badges()` — Badge dell'immobile
- `starter_imm_get_contract_badge()` — Testo del contratto per i badge
- `starter_imm_count_label()` — "5 locali", "1 bagno"
- `starter_imm_get_card_facts()` — Dati sintetici per card e riga "fatti"
- `starter_imm_get_featured_immobili()` — Immobili in evidenza per una posizione ("hero-home", "in-evidenza-home")
- `starter_imm_page_id()` — Pagina dell'elenco: quella creata dal modulo (Opzioni → Moduli) o una qualunque che contiene il blocco "Elenco immobili". 0 se non c'è.
- `starter_imm_get_immobili_page_url()` — URL della pagina dell'elenco, o della home se non esiste.
- `starter_imm_immobile_image_alt()` — Alt SEO per le foto di un immobile
- `starter_imm_poi_types()` — Tipi di punto d'interesse "Nelle vicinanze"
- `starter_imm_get_tour_embed()` — Codice HTML del video o tour virtuale
- `starter_imm_get_virtual_tour_embed()` — Tour virtuale 3D (Matterport e simili): accetta il link oppure il codice iframe incollato, e ne ricava l'indirizzo.
- `starter_imm_menu_highlight_immobili()` — Nel menu, nella scheda di un immobile resta evidenziata la voce della pagina "Immobili".
- `starter_imm_get_hero()` — Immobile in hero e relativa immagine (campo "immagine_hero" o copertina)
- `starter_imm_join_choices()` — Elenco di scelte multiple in testo: "Centralizzato, a radiatori, a metano"
- `starter_imm_get_consultant()` — Consulente dell'immobile: il primo scelto, altrimenti il termine "Team".
- `starter_imm_field_of()` — Legge un campo ACF ('' se ACF non c'è)
- `starter_imm_immobile_data_rows()` — Righe della tabella dati di un immobile: array di array( etichetta, valore ). Usata dalla scheda e dal PDF.
- `starter_imm_immobile_facts()` — Riquadri principali di un immobile: array di array( icona, valore, nota ). Usata dalla scheda e dal PDF.
- `starter_imm_immobile_extra_items()` — Voci di "Altri dati" di un immobile: array di array( label, value, highlight ). Per le voci da elenco con valore a scelta (Ascensore, Arredamento, Balcone…) vale la scelta.
- `starter_imm_immobile_highlights()` — Etichette in risalto sopra l'elenco: le voci di "Altri dati" con "In risalto" attivo (non quelle con valore "No").
- `starter_imm_default_feature_rows()` — Righe predefinite di "Caratteristiche" per un nuovo immobile (Cucina, Riscaldamento, Climatizzazione, Spese condominio).

### `inc/modules/immobili/immobili-pdf.php`
Scheda dell'immobile in PDF (libreria Dompdf in inc/modules/immobili/dompdf, caricata solo quando si crea il PDF). Colori dalle opzioni del tema.
- `starter_imm_pdf_url()` — Indirizzo del PDF di un immobile
- `starter_imm_pdf_image()` — Immagine come data URI (JPEG ridimensionato, con ritaglio opzionale), così il PDF non dipende da percorsi o permessi dei file.
- `starter_imm_pdf_hex()` — Colore delle opzioni del tema (Opzioni → Colori) come #rrggbb, oppure il valore di ripiego.
- `starter_imm_pdf_mix()` — Mescola due colori #rrggbb: $amount = quota del primo (0-1).
- `starter_imm_pdf_palette()` — Colori del PDF: derivati da testo e colore principale del tema.
- `starter_imm_pdf_icon()` — Icona del tema come immagine SVG per il PDF
- `starter_imm_pdf_html()` — HTML del PDF
- `starter_imm_pdf_build()` — Genera (o riusa dalla cache) il PDF e restituisce il percorso del file.
- `starter_imm_pdf_download()` — Scarica il PDF all'indirizzo della scheda con ?scheda-pdf=1

### `inc/modules/immobili/immobili-taxonomies.php`
Tassonomie dell'Immobile e termini iniziali.
- `starter_imm_immobile_taxonomies()` — Elenco delle tassonomie
- `starter_imm_immobile_initial_terms()` — Termini creati quando il modulo si accende, solo se non esistono già.
- `starter_imm_register_immobile_taxonomies()` — Registra le tassonomie collegate al CPT "immobile".
- `starter_imm_seed_initial_terms()` — Crea i termini iniziali mancanti, una volta sola (la prima volta che il modulo è acceso).

### `inc/modules/immobili/immobili.php`
Modulo Immobili (si accende da Personalizza → Opzioni → Moduli (CPT); spento, nessun file di questa cartella viene caricato). Portato da Di Guida Real Estate (ultima versione) con lo stile del tema Starter: colori, font e dimensioni sono quelli delle opzioni del tema, nessun colore o font del sito di origine. Contiene: - il tipo di contenuto Immobile (solo titolo e immagine; il resto sono campi ACF) e le sue tassonomie (Tipologia, Contratto, Zona, Consulente, Evidenza); - la scheda dell'immobile (templates/single-immobile.php): galleria con lightbox, dati, planimetria, tour virtuale 3D, video, mappa, consulente e modulo; - la scheda in PDF (immobili-pdf.php + libreria Dompdf in dompdf/, caricata solo quando si crea un PDF); - i filtri dell'elenco (immobili-filters.php) e i blocchi Elenco immobili, Immobili in evidenza e Hero home (template-parts/); - la pagina "Immobili" (creata all'accensione) e un immobile di esempio (sample/).
- `starter_imm_icon_paths()` — Icone dell'immobiliare (superficie, locali, bagni, punti d'interesse…): si aggiungono a quelle del tema, che hanno la precedenza.
- `starter_imm_needs_assets()` — Vero se la pagina ha bisogno degli stili e degli script degli immobili.
- `starter_imm_asset()` — File compilato (CSS/dist, JS/dist) se aggiornato rispetto al sorgente, altrimenti il sorgente.
- `starter_imm_request_form_create()` — Modulo CF7 "Richiedi informazioni immobile" (nome, email, telefono, note, privacy), creato una volta e collegato nelle opzioni.
- `starter_immobili_activate()` — Accensione: crea il modulo "Richiedi informazioni" se manca (la pagina Immobili la crea il registro dei moduli).
- `starter_immobili_page_content()` — Contenuto della pagina Immobili: il blocco "Elenco immobili" (ha la sua intestazione).
- `starter_immobili_demo()` — Immobile di esempio (da Di Guida, con 4 foto e una planimetria): si importa una volta sola, con i suoi campi e le sue categorie.
- `starter_imm_demo_variants()` — Altri due immobili di esempio, copie dell'immobile base con dati diversi (contratto, tipologia, zona, locali, bagni, prezzo…): servono a mostrare cosa fanno i filtri dell'elenco. Riusano le stesse foto, quindi non pesano.

### `inc/modules/immobili/templates/single-immobile.php`
Scheda singolo immobile

### `template-parts/blog/list.php`
Elenco articoli condiviso da pagina Blog (home.php), archivi (archive.php) e ricerca (search.php): intestazione della sezione, griglia di card, paginazione e aside. Opzioni in Opzioni → Blog.

### `template-parts/blog/share.php`
Pulsanti "Condividi" di un articolo: Facebook, X, LinkedIn e WhatsApp. Solo link di condivisione (nessun plugin, nessuno script di terze parti). Si attivano da Opzioni → Blog.

### `template-parts/contatti/render.php`
Blocco "Contatti": a sinistra i recapiti scritti nel blocco (icona + testo; email e telefoni diventano link da soli) oppure, se il blocco non ne ha, quelli di Opzioni → Footer; a destra il modulo (shortcode di Contact Form 7 scelto nel blocco).

### `template-parts/content/card-immobile.php`
Card di un immobile

### `template-parts/content/card-product.php`
Card prodotto: foto quadrata (seconda foto della galleria all'hover), cuore "preferiti", "Aggiungi al carrello" che compare all'hover, categorie, nome, descrizione breve, prezzo. Usa il $product globale. Cosa mostrare si sceglie in Opzioni → Shop → Card prodotto.

### `template-parts/content/card.php`
Card di un elemento nella griglia (".blog-grid"/".blog-card"): usata da pagina Blog, archivi, ricerca e dal "Continua a leggere" a fine articolo. Cosa mostrare (data, autore, categorie, tag) si sceglie in Opzioni → Blog.

### `template-parts/elenco-immobili/render.php`
Render del blocco ACF "starter-theme/elenco-immobili".

### `template-parts/elenco-servizi/render.php`
Render del blocco ACF "starter-theme/elenco-servizi".

### `template-parts/example-block/render.php`
Render del blocco ACF "starter-theme/example-block". Variabili disponibili (passate da ACF): $block, $content, $is_preview, $block_id, $context.

### `template-parts/footer/footer-style-1.php`
Footer — Stile 1. Struttura e stile ispirati al footer di GoCanopy (via footer.design): riga intro + colonne menu, riga social + spazio libero (shortcode o contenuto), logo enorme, riga finale con copyright e link legali. Il layout cambia ordine tra mobile e desktop (vedi CSS/starter.css, ".footer-*"). Tutti i testi passano da Personalizza → Opzioni → Footer e da starter_wpml_translate_string(), così risultano già pronti per WPML (String Translation) quando verrà installato — vedi functions.php.

### `template-parts/footer/footer-style-2.php`
Footer — Stile 2 (editoriale). Ispirato a un footer con logo+descrizione in alto, poi tre colonne (menu voci grandi in grassetto + social, dati di contatto, form newsletter) e riga finale con crediti. Riusa gli stessi componenti gia' pronti di "Stile 1" (colonna contatti ".footer-info", form ".footer-cta-wrap", icone ".social-links" dell'header, riga finale ".footer-row--bottom") solo riorganizzati in questo nuovo layout, cosi' non serve nessun nuovo campo in Personalizza. Tutti i testi passano da Personalizza → Opzioni → Footer e da starter_wpml_translate_string(), così risultano già pronti per WPML (String Translation) quando verrà installato — vedi functions.php.

### `template-parts/footer/footer-style-3.php`
Footer — Stile 3 (compatto). Una sola riga (logo + menu inline + social, con le stesse icone a bottone dell'header: riusa ".social-links") più la riga finale copyright/link legali, identica a quella di "Stile 1" (".footer-row--bottom", vedi CSS/starter.css). Pensato per pagine o siti che non hanno bisogno delle colonne di "Stile 1"/"Stile 2"; non ha lo spazio libero (newsletter). Tutti i testi passano da Personalizza → Opzioni → Footer e da starter_wpml_translate_string(), così risultano già pronti per WPML (String Translation) quando verrà installato — vedi functions.php.

### `template-parts/footer/footer-style-4.php`
Footer — Stile 4 (4 colonne), ripreso da Symbolikamente. Riga logo (versione chiara/negativa) + frase, poi 4 colonne: Contatti e social, menu "Link", menu "Riferimenti", spazio libero (es. form di Contact Form 7); riga finale con copyright e Credits. Campi già presenti in Opzioni → Footer e riusati qui: Descrizione, Titolo contatti, Contatti (telefono, email, indirizzo), Copyright (con {anno} e {sito}), Credits (testo e link), Mostra i social nel footer, Logo chiaro (Identità del sito / Opzioni → Header). Campi nuovi, solo per questo stile (Opzioni → Footer): - Immagine metodi di pagamento: opzionale, sotto i social (utile per i negozi). L'ultima colonna è lo "Spazio libero" (Opzioni → Footer, comune a tutti gli stili): titolo facoltativo e contenuto a piacere, di solito uno shortcode come [contact-form-7 id="12"]. Se è vuoto la colonna non compare. Le due colonne menu usano le posizioni "Footer: colonna Link" e "Footer: colonna Riferimenti" (Aspetto → Menu): il titolo della colonna è il nome del menu assegnato.

### `template-parts/footer/footer-style-5.php`
Footer — Stile 5 (titolo grande). Nome del sito gigante a tutta larghezza (si adatta da solo), frase grande, contatti e social a sinistra, sedi a destra e riga finale con crediti. Colori di sfondo e testo: quelli del footer (Opzioni → Colori). Testi: Opzioni → Footer (Testo = riga sopra il nome, Frase grande, Contatti, Sedi).

### `template-parts/footer/whatsapp-button.php`
Pulsante WhatsApp fisso in basso a destra (campi in Opzioni → Generale).

### `template-parts/hero-media/render.php`
Blocco "Hero con foto e video": apertura di una pagina (es. Chi siamo). A sinistra occhiello (H1 piccolo, con la parola chiave), titolo (H2 grande), testo, due pulsanti e una fila di piccole foto di clienti (max 6) con una frase; a destra una foto e un video verticale, ognuno con la sua etichetta (parole chiave). Il video è facoltativo: senza, resta la copertina.

### `template-parts/hero-slider/render.php`
Blocco "Hero con slider": foto a tutta larghezza che cambiano in dissolvenza (CSS + poche righe in JS/scripts.js, nessuna libreria), velo scuro, titolo H1 con parte in corsivo (<em>), testo e pulsante. La prima foto è precaricata (fetchpriority high), le altre in lazy. Colori e font dalle opzioni del tema.

### `template-parts/hero-split/render.php`
Blocco "Hero testo + foto": prima sezione della pagina con occhiello, titolo grande (H1), testo, fino a due pulsanti e una foto a lato. Ripreso dalla hero della home di Di Guida Real Estate, senza la parte degli immobili. Se la pagina parte con questo blocco l'H1 è il suo titolo.

### `template-parts/hero-video/render.php`
Blocco "Hero con video": come la hero con slider ma con un video a tutta larghezza (muto, in ciclo, parte da solo) al posto delle foto. Velo scuro, titolo H1 con parte in corsivo (<em>), testo e pulsante. La copertina è l'immagine mostrata finché il video carica (e a chi ha attivato "riduci animazioni"). Colori e font dalle opzioni del tema.

### `template-parts/immobili-hero-home/render.php`
Render del blocco ACF "starter-theme/immobili-hero-home".

### `template-parts/immobili-in-evidenza/render.php`
Render del blocco ACF "starter-theme/immobili-in-evidenza".

### `template-parts/instagram/render.php`
Blocco "Instagram": titolo, nome account, griglia di foto quadrate statiche e pulsante al profilo. Nessun collegamento automatico (niente cookie di Instagram né rallentamenti): le foto si aggiornano a mano.

### `template-parts/intestazione-pagina/render.php`
Render del blocco ACF "starter-theme/intestazione-pagina".

### `template-parts/mappa/render.php`
Render del blocco ACF "starter-theme/mappa".

### `template-parts/missione-visione/render.php`
Render del blocco ACF "starter-theme/missione-visione".

### `template-parts/portfolio/card.php`
Card di un progetto: immagine, titolo e categorie. Usata dal blocco "Elenco progetti" e da "Altri progetti". $args['cats']: stile delle categorie (testo | tab_sotto | tab_sopra | nessuna, predefinito testo); $args['reveal']: animazione logo (vedi starter_portfolio_reveal_html()); $args['video']: video al passaggio del mouse (predefinito sì); $args['titles']: riassunto e cliente (ordine, tag, misura, colore), vedi starter_portfolio_titles_html(); $args['cats_opts']: simbolo, tab piene/vuote, forma, maiuscole e posizione (prima/dopo il titolo), vedi starter_portfolio_cats_html(). Si sceglie nel blocco "Elenco progetti".

### `template-parts/portfolio/project-head.php`
Testata del progetto: copertina a tutto schermo (se scelta), titolo con categorie, dati del progetto, copertina. Usata dalla scheda sul sito (single-portfolio.php) e dall'anteprima nell'editor (blocco "Progetto — testata"). Argomenti: post_id, preview (true nell'editor: niente rivelazione allo scroll, briciole e modifica dal vivo).

### `template-parts/prodotti-in-evidenza/render.php`
Blocco "Prodotti in evidenza": griglia di prodotti con la stessa card del negozio (woocommerce/content-product.php). Serve WooCommerce. Quali prodotti: in evidenza (stellina), più venduti, più recenti, di una categoria, scelti a mano.

### `template-parts/progetti/render.php`
Blocco "Elenco progetti": griglia dei progetti del Portfolio con schede per categoria e paginazione. Si mette in una pagina normale (es. "Portfolio"): titolo, testi e altri blocchi sono liberi. Filtro e pagine usano ?categoria=slug e ?pagina=N sulla stessa pagina, quindi funzionano senza JavaScript.

### `template-parts/progetto-crediti/render.php`
Blocco "Progetto — crediti": titolo e coppie ruolo / nome dei collaboratori.

### `template-parts/progetto-media/render.php`
Blocco "Progetto — media": immagini e video in 1, 2 o 3 colonne. Ogni elemento è un'immagine o un video (muto, in ciclo).

### `template-parts/progetto-testata/render.php`
Blocco "Progetto — testata": anteprima nell'editor di copertina, titolo e dati del progetto. Sul sito non stampa niente: la testata la scrive già single-portfolio.php.

### `template-parts/progetto-testo/render.php`
Blocco "Progetto — testo": occhiello (es. "Contesto") e titolo a sinistra, paragrafi a destra (oppure tutto in una colonna).

### `template-parts/recensioni/render.php`
Render del blocco ACF "starter-theme/recensioni".

### `template-parts/sezione-affiancata/render.php`
Blocco "Sezione affiancata": foto (1 o 2) accanto a un testo scritto direttamente in Gutenberg con i blocchi nativi (titolo, paragrafi, elenchi, pulsanti): più libero di "Testo affiancato", che ha campi fissi. Con 2 foto la seconda, più piccola, si sovrappone in basso a sinistra. Ripreso da Symbolikamente.

### `template-parts/sezione-voci/render.php`
Render del blocco ACF "starter-theme/sezione-voci".

### `template-parts/slider/render.php`
Blocco "Slider": slider singolo o carosello di immagini. Funzionamento in JS/scripts.js (initSliders), stile in CSS/starter.css (".slider-block"). Senza JS resta una fila di immagini scorrevole a dito/rotella.

### `template-parts/striscia-valori/render.php`
Blocco "Striscia valori": riga di voci con icona sopra e titolo (4 su desktop, 2 su tablet, 1 su mobile).

### `template-parts/team/render.php`
Blocco "Team": griglia delle persone (4 colonne, 3 sotto 1280px, 2 sotto 1024px, 1 sotto 640px) con foto quadrata, nome, ruolo e breve bio.

### `template-parts/testo-affiancato/render.php`
Render del blocco ACF "starter-theme/testo-affiancato".

### `template-parts/ultimi-articoli/render.php`
Blocco "Ultimi articoli": gli articoli più recenti (anche di una sola categoria) con la card del blog. Cosa mostrare nelle card (data, autore, categorie) segue Opzioni → Blog.

### `woocommerce/archive-product.php`
Archivio prodotti (override di WooCommerce): pagina Shop e pagine delle categorie. Testata, filtri, ordinamento e griglia nella larghezza dei contenuti (Opzioni → Generale). I filtri sono quelli attivi in Opzioni → Shop (inc/filters.php).

### `woocommerce/content-product.php`
Card prodotto del negozio (override di WooCommerce): la stessa card in shop, categorie, ricerca, preferiti e nel blocco "Prodotti in evidenza". Il markup vero è in template-parts/content/card-product.php.


## 3. CSS

### `CSS/filters.css`
- (riga 1) Filtri e ordinamento degli elenchi: UN SOLO stile per tutto il sito (Shop, Immobili e ogni futuro tipo di contenuto con filtri). Non si crea mai un CSS dei filtri per un singolo tipo di contenuto: si usa questo markup (vedi docs/GUIDA-TEMA.md, "Filtri degli elenchi"): .filters-wrap contenitore (classe is-drawer / is-open aggiunte dal JS su tablet e mobile) .filters__overlay sfondo scuro dietro al pannello (data-filters-close) form.filters riquadro dei filtri .filters__head titolo + pulsante di chiusura (solo nel pannello) .filters__grid griglia a 12 colonne: ogni .filters__field occupa 4 colonne (3 per riga) .filters__field etichetta .filters__label + campo (select, input, .multiselect, .filters__range) .filters__actions pulsanti "Cerca" e "Azzera i filtri" .filters-toolbar barra sotto ai filtri: pulsante .filters-toggle, conteggio, ordinamento Solo variabili del tema (colori, font, raggi): niente valori propri.
- (riga 17) Barra con il pulsante "Filtri", il conteggio e l'ordinamento
- (riga 40) Campi: stessa altezza, bordo e raggio dei campi del sito
- (riga 69) Tendine native (Categoria, Ordina per, Tipologia…): stessa freccia degli altri campi
- (riga 81) Riquadro dei filtri
- (riga 95) Il pulsante "Filtri" serve solo dove i filtri diventano un pannello (tablet e mobile): sul desktop sono già visibili
- (riga 100) Griglia a 12 colonne: 3 campi per riga, senza vuoti. I pulsanti stanno nell'ultima colonna della loro riga.
- (riga 133) La legenda dei campi a intervallo (Prezzo, Superficie) sta sulla stessa linea delle etichette degli altri campi
- (riga 147) Pulsanti: alti come i campi, Cerca a sinistra e Azzera a contorno
- (riga 167) Variante larga (Shop): i pulsanti occupano tutta l'ultima riga, Cerca più largo di Azzera
- (riga 177) Azzera filtri: pulsante secondario = "Contorno 1" di Opzioni → Pulsanti (cambia da lì); i filtri usano "Pieno 1" per il principale. Hover come gli altri pulsanti: riempimento che sale dal basso (.button--outline::before) e testo che ruota. Selettori lunghi perché ".woocommerce a.button" (CSS dello shop) altrimenti lo riempie di colore pieno; opaco e non cliccabile senza filtri attivi.
- (riga 200) Ordinamento: su desktop dentro ai filtri (se il tipo di contenuto lo prevede), su tablet/mobile accanto al pulsante "Filtri"
- (riga 213) Scelta multipla (attributi, caratteristiche)
- (riga 214) Selezione multipla (.multiselect): menu a tendina con spunte
- (riga 292) Filtri su tablet e mobile: pannello che entra da sinistra
- (riga 332) "Filtri" e "Ordina per" sempre affiancati: il pulsante prende il suo spazio, l'ordinamento il resto della riga
- (riga 333) Filtri a cassetto (.is-drawer): barra, titolo e griglia nel pannello
- (riga 416) Titolo in alto, filtri che riempiono il resto: così i pulsanti stanno sempre in fondo al pannello, anche con pochi filtri
- (riga 430) Pulsanti sempre raggiungibili in fondo al pannello (con pochi filtri in fondo allo schermo, con tanti filtri fissi mentre si scorre), larghi quanto il pannello. L'ombra sopra dice che i filtri continuano sotto; il vuoto sopra (margin-top auto + gap della griglia) evita che coprano l'ultimo filtro a fine scorrimento.
- (riga 445) Nel pannello la tendina si apre in linea, non sopra ai campi seguenti

### `CSS/gutenberg.css`
- (riga 1) Stili validi SOLO nell'area admin (editor a blocchi). Caricato tramite add_editor_style() in functions.php. Serve a far apparire nell'editor lo stesso stile che i blocchi (core e i custom block ACF in /template-parts) hanno nel frontend, cosi' chi scrive i contenuti vede un'anteprima fedele. Duplica qui solo le regole visive necessarie: non serve reimportare tutto style.css.
- (riga 16) Esempio: stile del blocco ACF "example-block" replicato per l'editor.
- (riga 23) Larghezza del contenuto nell'editor: come sul sito (--content-width, e --gutter ai lati), così il testo non tocca i bordi del browser. Il valore di --gutter/--content-width arriva da CSS/starter.css (caricato anche nell'editor) e dalle opzioni del tema.
- (riga 34) I blocchi ACF a tutta larghezza annullano il padding laterale e arrivano ai bordi come sul sito.
- (riga 39) Blocchi con testo in Gutenberg dentro (Sezione affiancata): il contenitore di ACF si comporta come ".split__text--free" sul sito
- (riga 49) Sezioni fatte con il blocco Gruppo (tag section, a tutta larghezza): spaziature e colori come sul sito
- (riga 54) Blocchi ACF: l'anteprima non deve mai essere più larga della colonna dell'editor
- (riga 60) Nell'editor non c'è lo scroll: le sezioni "a comparsa" restano visibili
- (riga 66) Campo trappola dei moduli e icone dell'header non servono nell'editor
- (riga 71) Gruppi a tutta larghezza: contenuto nella larghezza del sito, come sul frontend
- (riga 77) Pulsanti del blocco "Pulsanti": nell'editor sono <div> (testo modificabile) e non <a>, quindi le regole "a.wp-block-button__link" del sito non li raggiungono. Stesso aspetto del sito (senza l'animazione del passaggio del mouse): usano le stesse variabili --b-* delle 4 varianti di Opzioni → Pulsanti (Pieno, Contorno, Pieno 2, Contorno 2 sono definite in CSS/starter.css), così nell'editor il pulsante è quello che si vede fuori.
- (riga 104) Il riempimento dell'hover (sfondo a gradiente) non c'è nell'editor: lo stile del sito lo tiene a 0 di altezza, qui non esiste proprio
- (riga 118) Moduli di Contact Form 7 nell'anteprima: il foglio di stile del plugin non c'è nell'editor, quindi i campi nascosti e il messaggio d'esito vuoto restano visibili (riquadri vuoti). Si nascondono come sul sito.
- (riga 127) Nell'editor i link non devono funzionare: un clic su un pulsante o un link dell'anteprima di un blocco (ACF) non porta alla pagina collegata, ma seleziona il blocco. Restano attivi i link dentro i testi che si stanno scrivendo (campi modificabili) e i pulsanti del core, che sono campi di testo editabili.
- (riga 135) Anteprima della testata del progetto (blocco "Progetto — testata"): solo lettura; la copertina a tutto schermo si limita ad altezza editor
- (riga 145) Titolo di pagine, articoli e CPT nell'editor: largo come il contenuto, più piccolo, con una riga che lo separa dal resto
- (riga 161) Nell'editor non c'è l'header fisso: lo spazio che il sito lascia sotto di lui (--header-height) qui è solo vuoto sopra il titolo
- (riga 166) Anteprima della testata: con la copertina a tutto schermo il titolo parte più sotto, come sul sito (body.project-cover-full .project-head)

### `CSS/icon-picker.css`
- (riga 1) Selettore di icone (campo ACF "Icona"): solo backend.
- (riga 26) Finestra di scelta
- (riga 27) Selettore icone (.sip): finestra, sfondo e intestazione
- (riga 77) Barra strumenti: ricerca e schede
- (riga 124) Griglia delle icone e singola icona
- (riga 172) Messaggio "nessun risultato"
- (riga 190) Blocca lo scroll della pagina mentre il selettore è aperto
- (riga 195) L'attributo hidden deve vincere sugli stili dei pulsanti di WordPress.

### `CSS/immobili.css`
- (riga 1) Modulo Immobili (inc/modules/immobili): card, scheda dell'immobile, filtri, elenco e hero. Caricato solo dove serve (scheda, pagine con i blocchi Elenco immobili / Immobili in evidenza / Hero home). Solo variabili del tema: colori (Opzioni → Colori), font e dimensioni (Opzioni → Tipografia / Stile testi).
- (riga 7) Etichette (tag) usate nelle card e nella scheda
- (riga 25) Griglia delle card immobile (elenco e "Immobili in evidenza")
- (riga 32) Card immobile: foto, badge, prezzo e dati principali
- (riga 125) Scheda immobile: contenitore e intestazione (apertura, badge, titolo, indirizzo, prezzo)
- (riga 206) Galleria foto a scorrimento e pulsante PDF
- (riga 284) Corpo della scheda: dati chiave, blocchi di testo, riquadri e chip
- (riga 302) Dati chiave in griglia (metri quadri, locali, bagni…)
- (riga 340) Blocchi di contenuto (testo, riquadri, chip delle caratteristiche)
- (riga 433) Tabella dei dati tecnici, planimetria e mappa
- (riga 490) Colonna laterale: contatto, immobili correlati e modulo di richiesta
- (riga 537) Modulo di richiesta (Contact Form 7) dentro la scheda
- (riga 640) Tablet e mobile: la scheda passa a una colonna
- (riga 669) Hero della home immobili (.imm-hero): griglia, titolo, foto e card
- (riga 793) Card dell'immobile in evidenza dentro la hero
- (riga 828) Paginazione dell'elenco immobili
- (riga 879) Badge dell'hero (h1 con aspetto da etichetta)
- (riga 905) Scorrimento orizzontale di galleria e planimetria (trascinamento con il mouse)
- (riga 921) Punti di forza (highlights) e icone
- (riga 996) Luoghi vicini (POI)
- (riga 1038) Video e mappa incorporati
- (riga 1048) Consulente: foto, nome, ruolo e contatti
- (riga 1105) Ritocchi finali: testo introduttivo della hero, pulsante di invio del modulo, animazione del puntino

### `CSS/preset-starter.css`
- (riga 1) Stile di base per i blocchi Gutenberg nativi non ancora coperti da starter.css; riusa le stesse variabili del tema, nessuna nuova. Caricato prima di starter.css, che vince in caso di conflitto.
- (riga 3) --- Testo ---
- (riga 5) Citazioni e citazioni evidenziate
- (riga 35) Codice e testo preformattato
- (riga 52) Liste
- (riga 62) Tabelle
- (riga 85) Dettagli a fisarmonica
- (riga 102) --- Media ---
- (riga 149) --- Design ---
- (riga 195) L'intestazione dell'accordion è un h3: azzera il margin-bottom globale dei titoli, qui non serve.
- (riga 230) --- Widget ---
- (riga 232) Ricerca: campo e pulsante
- (riga 250) Calendario
- (riga 268) Tag cloud
- (riga 285) Elenchi di categorie, archivi, ultimi articoli, commenti e pagine
- (riga 305) --- Tema ---
- (riga 343) --- Card generiche (prezzo, recensioni) ---
- (riga 368) Stelle delle recensioni
- (riga 375) Modulo

### `CSS/shop.css`
- (riga 1) Shop (WooCommerce): card prodotto, filtri, archivio, scheda prodotto, carrello/cassa/account Caricato solo dove serve (vedi starter_needs_shop_css() in inc/woocommerce.php; in produzione CSS/dist/shop.min.css). Colori e font arrivano dalle opzioni del tema: - colori: --color-primary / --color-text / --color-background / --color-border (Opzioni → Colori); - testi: --wp--preset--font-size--* (Opzioni → Stile testi). Niente font-size scritti a mano qui.
- (riga 9) Grigio dei testi secondari e linee morbide: derivati dai colori del tema, non colori nuovi.
- (riga 12) Esito (aggiunto al carrello / errore): colori di stato fissi, uguali in ogni progetto.
- (riga 18) ----- Negozio (pagine WooCommerce) -----
- (riga 20) Pagine Woo con il contenitore del tema (scheda prodotto): stessa larghezza e distanza laterale dei contenuti
- (riga 25) Archivio (shop e categorie): testata nella larghezza dei contenuti, filtri e griglia nella larghezza "wide"
- (riga 56) Griglia prodotti: titolo della sezione, filtri, ordinamento
- (riga 62) Ricerca con suggerimenti (prodotti mentre scrivi)
- (riga 64) Ricerca live: campo con suggerimenti mentre si digita
- (riga 154) Paginazione
- (riga 212) Pulsanti di WooCommerce con l'aspetto del tema (anche dentro .woocommerce)
- (riga 261) Avvisi
- (riga 282) Scheda prodotto
- (riga 310) Foto principale nel riquadro bianco; miniature sotto, staccate di 24px
- (riga 323) Miniature: fino a 5 per riga (il prodotto con più foto ne ha 5), grandi e con un po' d'aria
- (riga 324) Scheda prodotto: miniature della galleria, descrizione breve e titolo
- (riga 397) Quantità massima 1 (pezzo unico o venduto singolarmente): Woo mette un campo nascosto, il contenitore vuoto spostava il pulsante
- (riga 425) Niente frecce del browser: ci sono i pulsanti − e +
- (riga 451) Prodotti raggruppati (set): l'elenco dei prodotti sta sopra e il pulsante "Aggiungi al carrello" va sotto, non accanto alla prima riga
- (riga 463) Prodotti variabili: menu delle varianti (taglia, colore…) con lo stesso aspetto degli altri campi del tema.
- (riga 464) Scheda prodotto: varianti e modulo "Aggiungi al carrello"
- (riga 626) Carrello, cassa, account (blocchi di WooCommerce)
- (riga 638) Carrello e cassa: sempre chiari e con angoli fissi (4px), come il riquadro "Opzioni di spedizione", a prescindere da "Bordi del sito". Solo i pulsanti seguono lo stile pulsanti del sito (Opzioni → Pulsanti).
- (riga 646) Dentro la pagina (non nell'header e nel footer) gli angoli sono quelli classici, leggeri e fissi, a prescindere da "Bordi del sito": campi, menu a tendina, quantità, riquadri, avvisi, miniature. I pulsanti seguono ancora Opzioni → Pulsanti.
- (riga 659) Pulsanti: stessi colori, bordo, maiuscolo e animazione ("riempimento dal basso") dei .button del sito
- (riga 709) Campi, menu a tendina, area di testo, riquadri e miniature: stesso angolo e stesso filo
- (riga 738) Nota all'ordine: alta il doppio del predefinito e ridimensionabile in altezza dal bordo in basso a destra
- (riga 739) Cassa e carrello a blocchi: campi di testo
- (riga 765) Area "Il mio account": menu di navigazione
- (riga 817) ----- Card prodotto (negozio, collezioni, home) -----
- (riga 818) Elenco prodotti: griglia e card prodotto (.product-card)
- (riga 925) Scheda prodotto: lente dello zoom e cuore dei preferiti, in alto a destra sulla foto.
- (riga 930) Stesso selettore di WooCommerce (che mette top/right .5em), altrimenti non vince.
- (riga 939) 16px + lente 36px + 8px di spazio
- (riga 941) come la lente dello zoom
- (riga 946) tratto 2.2 su viewBox 24 = 2px
- (riga 976) Stato dell'aggiunta al carrello: in corso / fatto (niente link "Visualizza carrello", c'è il numero nell'header)
- (riga 987) "Visualizza carrello": prende per qualche secondo il posto del pulsante (stessa misura, sopra di lui) e poi sparisce: il pulsante non si sposta mai
- (riga 1048) Pallino centrale tra le categorie
- (riga 1064) Il CSS di WooCommerce rimpicciolisce gli h3 delle card: il titolo prende la misura della classe .title-h5 (segue Stile testi)
- (riga 1107) Pagina Preferiti ([starter_preferiti])
- (riga 1116) L'attributo hidden vince sul display della griglia: con almeno un prodotto il messaggio non si vede
- (riga 1129) Colonne della griglia (Opzioni → Shop → Colonne): solo pagine Shop e categorie; di base 4
- (riga 1147) Responsive griglia prodotti
- (riga 1162) Su touch non c'è hover: il pulsante "Aggiungi" resta sempre visibile sotto la foto
- (riga 1172) Sotto i 480px i prodotti vanno su una colonna (anche con le colonne scelte in Opzioni → Shop)
- (riga 1180) Il pulsante CTA dell'header ha sempre l'aspetto del tema: nelle pagine del negozio il CSS "general" di WooCommerce stilizza qualunque "a.button" della pagina (grigio, quadrato), qui lo si riporta com'è altrove.
- (riga 1189) come in tutte le pagine: si stringe nelle finestre strette (vedi starter.css)
- (riga 1212) Pulsante "Vedi tutti i risultati" del pannello di ricerca: stesso aspetto delle altre pagine anche nel negozio
- (riga 1241) Cassa a blocchi: partita IVA, codice fiscale, SDI e PEC servono solo alla fatturazione, non alla spedizione

### `CSS/starter.css`
- (riga 1) Foglio di stile principale del sito; style.css resta riservato all'header di tema WP.
- (riga 3) Reset minimo
- (riga 12) .alignfull usa 100vw: su desktop include la scrollbar, causando scroll orizzontale
- (riga 28) <picture> (sorgente AVIF + img WebP) non deve creare un livello in più: l'<img> si comporta come figlio diretto del genitore
- (riga 33) Margine sopra i titoli gestito dal contenitore, non titolo per titolo, per uniformità
- (riga 43) Scala dei testi: dimensioni da theme.json (clamp, si adattano da sole a ogni larghezza), le stesse che compaiono compilate in Opzioni → Stile testi. Chi cambia un valore lì lo cambia ovunque; qui ci sono solo i valori standard (nessun font-size a mano).
- (riga 95) Variabili base: allinearle a theme.json se in futuro si passa a block theme
- (riga 105) Larghezza colonna di lettura, sovrascrivibile da Personalizza -> Opzioni -> Generale
- (riga 107) Larghezza "wide": sempre content-width + 200px, non un campo separato
- (riga 114) Colori hover bottoni: uno per la CTA, uno per il pulsante Menu
- (riga 119) Bottone secondario: aspetto di default finché non personalizzato da Colori
- (riga 123) Sfondo bottone Menu agganciato a --color-text finché non personalizzato da Colori
- (riga 126) Colori testo bottoni separati da sfondo/bordo per personalizzazione autonoma da Colori
- (riga 134) Nessun --color-social di default: resta legato al contesto (bianco su hero, scuro altrove)
- (riga 137) Link semplici: aspetto di default finché non personalizzati da Colori
- (riga 141) Tag/categorie: hover con accento acceso, testo passa a --color-text per restare leggibile
- (riga 144) Bordo hover segue lo sfondo, o resta fisso se "mantieni colore bordo" è spuntato
- (riga 147) Usate dai blocchi ACF in template-parts/ (pannelli, pulsanti a pillola, card...)
- (riga 153) Animazioni morbide: una sola curva e durate non troppo brevi per i cambi di colore (hover)
- (riga 163) Elementi piccoli (numeri della paginazione, etichette, badge): l'angolo segue "Bordi del sito" ma non supera una misura proporzionata, così non diventano cerchi
- (riga 168) Sotto 480px spacing-unit ridotto: più spazio utile per logo/pulsanti header
- (riga 180) Spazio per header fixed; non serve su has-hero, dove galleggia trasparente sopra
- (riga 188) Colore link non tocca header/footer/bottoni (regole più specifiche); transition vale per tutti gli <a>
- (riga 198) Layout base
- (riga 200) Il contenuto è largo esattamente --content-width (Opzioni → Generale): il max-width comprende i due margini laterali
- (riga 206) Distanza fissa dall'header, uniforme ora che i titoli non hanno margine proprio
- (riga 214) Pagine con blocchi: nessuno spazio tra contenuto e footer; lo decidi tu con le classi pb/mb (es. "pb3", "mb2-tab")
- (riga 227) Header (header.php, inc/header.php) — struttura e versioni Una riga flex: logo | menu | CTA | .site-header__tools (social, divisore, icone) | pulsante "Menu". Versione (Opzioni → Header): Centrato (menu al centro), Standard (menu vicino al logo), Ridotto (solo "Menu"). "Flottante" (card staccata dai bordi) e "A tutta larghezza" si combinano con tutte le versioni. Il pannello a tendina del menu ridotto è nella sezione "Menu ridotto" più sotto.
- (riga 235) Distanza tra le icone (social e icone uguali); "semplici" ne hanno di più, "vicine" nessuna
- (riga 237) Lato delle icone con bordo (come i social): si stringono un po' dove il menu ha poco spazio
- (riga 248) Logo scuro: mostrato solo sopra hero trasparente, prima dello scroll. I due loghi (classico e scuro) stanno nella stessa cella della griglia e si alternano con visibility, non con display:none: lo spazio riservato al logo è sempre quello del più largo, quindi menu e icone restano nella stessa posizione in home e nelle pagine interne.
- (riga 255) Header flottante ha sempre sfondo chiaro: usa sempre il logo classico
- (riga 265) Header: fisso in alto, trasparente su has-hero finché non si scrolla
- (riga 277) Blur su ::before dedicato, non sull'header, per non creare un containing block per il pannello mobile fixed
- (riga 301) Nascondi/mostra su scroll solo per header non flottante
- (riga 306) Effetto vetro solo mentre risale (classe via JS), non mentre scende o resta in cima
- (riga 320) Sposta l'header sotto l'admin bar di WP, stesse altezze responsive del core
- (riga 325) Header flottante: card staccata dai bordi, sempre a sfondo chiaro anche su hero. Scrollando si "attacca" in alto (niente margine sopra) e diventa vetro.
- (riga 331) Sopra 1440px la card resta centrata invece di allargarsi (salvo "A tutta larghezza")
- (riga 342) "A tutta larghezza": anche la card flottante prende tutta la larghezza (meno i margini laterali)
- (riga 347) Sfondo pieno solo in cima; poi diventa vetro/blur come il pannello mobile, attaccato al bordo alto
- (riga 386) "A tutta larghezza": l'header è attaccato in alto e ai lati del browser, normale o flottante, a riposo e scrollando
- (riga 416) Spazio extra sopra header solo su pagine senza hero
- (riga 431) Card flottante: il contenuto riempie la card
- (riga 438) Header a tutta larghezza (Opzioni → Header): il contenuto arriva a 1920px invece di --content-width
- (riga 457) Logo classico e logo scuro sovrapposti nella stessa cella; l'icona dello scroll accanto
- (riga 471) Cambio logo → icona allo scroll: dissolvenza e restringimento, non un cambio secco
- (riga 485) Logo allo scroll (Opzioni → Header): più piccolo, oppure sostituito da un'icona caricata da te
- (riga 498) L'icona dello scroll non occupa spazio finché non serve (senza questa regola "body.logo-scroll-icon .custom-logo-link" le dava 40px anche invisibile)
- (riga 519) Menu: la voce occupa tutta l'altezza della barra, così il sottomenu si apre sotto la barra senza "buchi" per il mouse
- (riga 527) Nav mobile ad hoc: nascosta finché non è assegnato un menu alla posizione Mobile
- (riga 551) Voci del primo livello: link e pulsante (voci con sottomenu) hanno la stessa altezza e lo stesso allineamento
- (riga 565) Standard: il menu sta a destra, accanto a CTA e icone
- (riga 573) Finestre strette (1181–1439px): la riga dell'header si stringe (spazi tra logo, voci, pulsante e icone) così non esce dalla finestra e l'icona del carrello resta sempre visibile.
- (riga 591) Variabile (non padding diretto): il CSS dello shop, caricato solo su alcune pagine, ridefinisce il padding del pulsante e così resta uguale ovunque
- (riga 605) Social, divisore e icone: l'ordine dei due gruppi si sceglie in Opzioni → Header
- (riga 642) Senza sfondo le icone hanno un po' più di aria; "vicine" nessuna
- (riga 660) Varianti di pulsante (Opzioni → Pulsanti). Ogni variante riempie le stesse 6 variabili locali --b-*: sfondo, sfondo hover, testo, testo hover, bordo, bordo hover. .button e il pulsante Gutenberg normale sono "Pieno 1"; .button--outline e Gutenberg "Contorno" sono "Contorno 1"; .button--fill-2 / .button--outline-2 (e gli stessi stili di Gutenberg) le varianti 2. I valori --btn-* arrivano da Personalizza (starter_colors_css); se vuoti valgono quelli di base qui sotto.
- (riga 720) Lo sfondo non passa sotto il bordo: altrimenti, al passaggio del mouse, ai bordi arrotondati si vede un anello sfumato tra il colore di partenza e il riempimento
- (riga 727) spazio tra testo e icona (freccia) quando c'è
- (riga 735) Hover: colore che sale dal basso + testo che ruota in 3D, ispirato a beetogreen.com
- (riga 742) Il riempimento in hover ha gli stessi angoli del pulsante
- (riga 744) Sale dal basso verso l'alto.
- (riga 752) Il riempimento sta dentro il bordo da 1px: il suo angolo è un pixel più piccolo di quello del pulsante, così non restano spicchi del colore di partenza agli angoli
- (riga 770) Colore hover testo/icona: uno per tipo di bottone, personalizzabile da Colori
- (riga 773) A riempimento finito (0.4s) lo sfondo sotto prende lo stesso colore: sui bordi curvi non resta nemmeno un filo del colore di partenza
- (riga 787) Icone header bianche su hero trasparente, a prescindere dal colore scelto in Colori
- (riga 793) Le icone (frecce, social, menu) stanno sopra il riempimento dell'hover (::before), come il testo: senza questo il riempimento le copre e al passaggio del mouse spariscono.
- (riga 845) Copia icone social per il pannello mobile, visibile solo dove le altre non ci stanno
- (riga 850) Icone social come pulsanti quadrati, alti quanto la CTA "Contattami".
- (riga 857) Bordo dà forma all'icona a riposo; superfluo in hover con sfondo pieno
- (riga 858) var(--color-social) adatta al contesto se non personalizzato da Colori
- (riga 868) Icona sdoppiata come il testo CTA/Menu: overflow hidden nasconde il duplicato fuori asse
- (riga 881) Altezza fissa, larghezza libera: evita pesi visivi diversi tra icone con proporzioni diverse
- (riga 912) Sottomenu: dropdown hover su desktop, elenco statico indentato su mobile
- (riga 922) Azzera il gap ereditato da ".main-navigation ul", altrimenti resta vuoto tra le voci
- (riga 950) Freccina indica le voci con sottomenu (hover desktop, sempre aperta mobile)
- (riga 964) Su desktop il wrapper sparisce: menu e azioni restano figli diretti della riga dell'header
- (riga 969) Bottone Menu mobile in stile vetro, ispirato a madebythread.com; nascosto su desktop
- (riga 970) Nessun width/height fisso: si dimensiona da solo col padding (px, non em)
- (riga 976) width: max-content evita che il bottone si allarghi a riempire lo spazio
- (riga 1005) Menu ridotto: sotto i 1180px e nella versione "Ridotto" (a qualunque larghezza) Nella barra restano logo, icone e pulsante "Menu"; menu, CTA e social stanno nel pannello a tendina. Le stesse regole sono scritte due volte (versione Ridotto, poi media query) perché CSS non permette di unirle.
- (riga 1010) Versione "Ridotto"
- (riga 1038) Visibile solo a pannello aperto; altrimenti il contenitore ha width/height 0
- (riga 1070) Sottomenu: elenco statico (con "si aprono col click" restano chiusi, vedi sotto)
- (riga 1083) CTA e copia dei social affiancate se c'è spazio, altrimenti vanno a capo
- (riga 1104) Social solo dentro il pannello (nella barra non ci sono); niente divisore
- (riga 1117) Scambia menu desktop/mobile solo se è assegnato un menu alla posizione Mobile
- (riga 1126) Sottomenu col click: CHIUSI, si aprono toccando la voce (niente hover)
- (riga 1153) Sotto i 1180px
- (riga 1182) Visibile solo a pannello aperto; altrimenti il contenitore ha width/height 0
- (riga 1214) Sottomenu: elenco statico (con "si aprono col click" restano chiusi, vedi sotto)
- (riga 1227) CTA e copia dei social affiancate se c'è spazio, altrimenti vanno a capo
- (riga 1248) Social solo dentro il pannello (nella barra non ci sono); niente divisore
- (riga 1261) Scambia menu desktop/mobile solo se è assegnato un menu alla posizione Mobile
- (riga 1270) Sottomenu col click: CHIUSI, si aprono toccando la voce (niente hover)
- (riga 1277) Sottomenu dentro un sottomenu: più rientrato ai lati
- (riga 1308) Versione Ridotto (e sotto i 1180px): la riga ha meno spazio sopra e sotto
- (riga 1335) Allineamenti standard blocchi core (necessari per align-wide/full)
- (riga 1336) alignwide sfonda oltre la colonna normale restando centrato: margin-left 50% + transform, non max-width da solo
- (riga 1350) Contenuto di un Gruppo resta leggibile anche se il Gruppo è wide/full; .wp-block-group--fullscreen è l'opt-out
- (riga 1363) Hero: altezza/overlay gestiti dal blocco Cover, qui solo centriamo il contenuto
- (riga 1378) Logo del sito
- (riga 1379) Altezza del logo: una sola variabile, più bassa su tablet e mobile. Il logo "più piccolo" e l'icona dello scroll partono da questa.
- (riga 1395) Blog: intestazione della sezione (occhiello = che sezione è, titolo = il suo nome)
- (riga 1425) Articolo singolo: cover poi meta poi contenuto; .entry-meta base condivisa con la card
- (riga 1459) Respiro verticale automatico per ogni sezione; clamp() scala senza scendere/salire oltre i limiti
- (riga 1465) Prima sezione senza spazio extra sopra, eccetto la hero che gestisce la propria altezza
- (riga 1480) Aside spenta da Opzioni → Blog: la griglia occupa tutta la larghezza
- (riga 1485) Pulsante che apre l'aside: solo dove l'aside diventa pannello (tablet/mobile)
- (riga 1494) Aside a destra; resta ferma mentre si scorre solo se è acceso Opzioni → Blog → "Aside fissa" (sopra i 900px, dove non è un pannello)
- (riga 1495) Colonna laterale del blog (aside) e widget
- (riga 1512) Titoli e liste dei widget (categorie, ultimi articoli)
- (riga 1572) Modulo di ricerca in sidebar e nella pagina vuota del blog
- (riga 1608) Tag cloud nella sidebar
- (riga 1622) Sotto 900px la colonna sparisce: l'aside diventa un pannello che entra da destra
- (riga 1684) Griglia articoli: colonne da Opzioni → Blog (2/3/4), gap stretto
- (riga 1741) L'unico effetto della card: passando in un punto qualsiasi della card (non solo sui link: così muovendosi tra titolo e pulsante non si ferma e riparte) l'immagine si ingrandisce con una piccola rotazione; anche con la tastiera (focus su un link)
- (riga 1756) Data e autore: etichetta con lo sfondo del sito sopra l'angolo in alto a sinistra dell'immagine
- (riga 1804) "Leggi tutto": pulsante a contorno (Contorno 1 di Opzioni → Pulsanti), largo quanto la card
- (riga 1810) Card: data/autore a sinistra, categorie come etichette a destra; tag sotto il testo, prima di "Leggi tutto"
- (riga 1859) 1 colonna (Opzioni → Blog) e articolo in evidenza: immagine a sinistra, testo e informazioni a destra
- (riga 1906) "Continua a leggere" in fondo all'articolo: sempre 3 colonne (2 su tablet, 1 su mobile) con schede in colonna, qualunque sia la griglia dell'elenco
- (riga 1951) Articolo: tag a fine testo, riquadro autore, "Continua a leggere", condividi
- (riga 2030) Paginazione: numeri + frecce (the_posts_pagination)
- (riga 2063) Home "presentazione": riga fiducia, griglia portfolio, form contatto
- (riga 2099) Maiuscola iniziale solo visiva per tag/categorie, non tocca il nome salvato in WP
- (riga 2108) Tag degli articoli: voci singole di testo (le categorie sono le etichette con bordo), separate da un punto centrato
- (riga 2133) Pill tag/categorie con colori propri, non --color-link, per restare leggibili in hover
- (riga 2190) Bottoni Gutenberg: stessa identità visiva di .button; rise via background-image, non ::before (niente markup extra)
- (riga 2191) Selettore "a.wp-block-button__link" per battere in specificità il CSS core iniettato su is-style-outline
- (riga 2208) stessa misura di .button
- (riga 2223) Le varianti di Gutenberg (Contorno, Pieno 2, Contorno 2) cambiano solo le variabili --b-*: le regole sopra restano le stesse. Il core inietta colori propri su is-style-outline: qui si riprende il controllo con la specificità giusta.
- (riga 2242) Footer ispirato a GoCanopy (footer.design); colori riusano le variabili del :root
- (riga 2244) Riusa la palette del :root, non colori a sé
- (riga 2250) Variabili proprie per il form newsletter, separate da --footer-accent
- (riga 2255) Colore condiviso da testo/link legali/menu footer, default = --footer-accent
- (riga 2267) Pagina corrente nel menu footer: specificità più alta vince sempre
- (riga 2273) Icone social forzate al colore testo footer: un colore scuro fisso sparirebbe su sfondo scuro
- (riga 2279) Link footer con colore esplicito scritti come "a.classe", non solo ".classe", per vincere su .site-footer a
- (riga 2314) grid-column rilevante solo dentro .footer-row--columns (Stile 2), ignorato altrove
- (riga 2348) Titoletti delle colonne (Contatti, Menu, Newsletter…) uguali in tutti gli stili; il titolo dell'introduzione resta normale
- (riga 2368) Stile 1: le due righe condividono le colonne; la newsletter occupa lo spazio di Contatti + Menu
- (riga 2433) Su telefono Contatti e Menu una sotto l'altro: in due colonne l'email andava a capo a metà parola
- (riga 2467) Pila social allineata in basso rispetto al form accanto, non centrata
- (riga 2475) Icona e testo centrati fra loro.
- (riga 2480) Icona+etichetta si muovono opposte al mouse (magnetico); transition solo per il rientro, il movimento live è via JS
- (riga 2501) Icona più grande, il cerchio resta della stessa dimensione, solo qui
- (riga 2525) Icona e testo centrati; il bottone fa eccezione e si allinea in basso
- (riga 2528) Più largo solo a sinistra per dare respiro all'icona
- (riga 2535) Checkbox privacy collegato al form via attributo "form", pur stando fuori dal tag <form>
- (riga 2588) È un <button>, non un <a>: niente conflitto di specificità con .site-footer a
- (riga 2591) Allineato in basso, non centrato come icona/testo (vedi .footer-cta)
- (riga 2609) Chiaro di base, non l'accento standard, così il testo resta leggibile in hover
- (riga 2630) Stesso bordo della riga finale, qui separa il logo dagli elementi sopra
- (riga 2673) Link legali vanno a capo su mobile; classe condivisa dai 3 stili footer
- (riga 2681) Stile 2: riga logo+descrizione poi 3 colonne poi riga finale; riusa classi di Stile 1
- (riga 2682) Logo e riga sotto condividono la stessa griglia 3 colonne, così i bordi sinistri coincidono sempre
- (riga 2762) Sovrascrive la larghezza fissa di Stile 1: qui riempie la sua colonna
- (riga 2772) Breakpoint 782px/1023px allineati a quelli scelti per tutto il sito
- (riga 2774) Sotto 1023px righe tornano flex: la griglia serviva solo per allinearle affiancate
- (riga 2780) Menu e contatti restano affiancati; il form va sempre a capo da solo
- (riga 2793) width:100% forza il vai-a-capo in un contenitore flex-wrap
- (riga 2800) Gap va sottratto dai flex-basis in proporzione 40/60, altrimenti si supera il 100%
- (riga 2817) Sotto 782px logo+descrizione si separano, come menu/contatti/form
- (riga 2824) flex-direction:column rende l'asse verticale: un flex-basis per la larghezza diventerebbe un'altezza
- (riga 2833) Sotto 782px anche menu e contatti si separano, tutte le colonne impilate
- (riga 2840) Fix: stesso bug del flex-basis diventato altezza, ora impostato ad auto per tutte le colonne
- (riga 2848) Stile 3: footer compatto a una riga (logo+menu+social) sopra la riga finale
- (riga 2912) Ordine desktop è già quello naturale del markup, serve solo sistemare le colonne
- (riga 2923) Ordine mobile diverso: logo prima di social+CTA, CTA prima dei social
- (riga 2937) Logo non esce dal contenitore su mobile come fa su desktop
- (riga 2955) flex-direction:column: il flex-basis di 560px diventerebbe l'altezza, ignorando height:80px
- (riga 2966) Fascia tablet 601-899px: icone+etichette in riga, spazio sufficiente per non impilarle
- (riga 2983) HELPER!!!
- (riga 2989) Un Gruppo con dentro un Media+Testo che deve riempire tutta l'altezza del contenitore (es. abbinata a .min-h-screen), immagine in cover. Altezza propagata con flex:1 ad ogni livello, non con height:100% (inaffidabile dentro un flex con min-height).
- (riga 2996) Stessa specificità della regola padding alignfull più in alto: vince perché dichiarata dopo.
- (riga 2999) Blocchi ACF riutilizzabili (template-parts/) e pulsante WhatsApp
- (riga 3003) Ritmo verticale di questi blocchi quando sono in pagina uno dopo l'altro.
- (riga 3018) Contenitore centrato di sezione
- (riga 3020) Stessa larghezza e stessi bordi del contenuto delle pagine (.starter-container)
- (riga 3026) "Sfondo della sezione" (scheda Avanzate dei blocchi ACF): fascia a tutta larghezza. Il colore (un colore della palette) arriva da style inline; la classe dice se è scuro (testi chiari) o chiaro (testi del tema)
- (riga 3048) Box colorato (accento/chiaro)
- (riga 3077) Intestazione di sezione
- (riga 3094) Il paragrafo sotto il titolo non ha margini suoi: la distanza la dà solo il gap qui sopra
- (riga 3115) Occhiello del titolo di pagina: peso 700. Valore esplicito, indipendente dall'ordine delle regole (sorgente e CSS compilato devono coincidere).
- (riga 3136) Pulsanti a pillola: NON più usati dai blocchi del tema (tutti i bottoni sono `.button`, vedi sopra). Restano solo per compatibilità con contenuti/CSS di vecchi progetti.
- (riga 3174) Pulsante rotondo (frecce carosello, chiusura)
- (riga 3206) Cerchio con icona
- (riga 3218) Animazione "flip" già usata sul blocco Pulsante nativo (vedi starter_wp_block_button_flip_animation()), riusata qui
- (riga 3259) Briciole di pane (blocco Intestazione pagina)
- (riga 3281) Comparsa allo scroll (attributo data-reveal, vedi JS/scripts.js initReveal)
- (riga 3300) Caroselli trascinabili col mouse (attributo data-drag-scroll, vedi JS/scripts.js initDragScroll; su touch scorrono già col dito)
- (riga 3315) Testo affiancato
- (riga 3316) Blocco "Testo affiancato" (.split): griglia, titolo, pulsante e foto rotonde
- (riga 3400) Elenco con spunte (.checklist)
- (riga 3428) Sezione voci: layout "numerato" (elenco + testo introduttivo a fianco)
- (riga 3494) Sezione voci: layout a card (griglia, icone, passi)
- (riga 3580) Il cerchio dell'icona ha lo stesso colore dell'hover della card (accento): al passaggio diventa chiaro, così resta visibile
- (riga 3581) Blocco "Elenco" (.list-block): card con icona, variante a icone, riga con pulsante e passi numerati
- (riga 3639) Pulsante finale (CTA) sotto l'elenco
- (riga 3691) Variante a passi numerati
- (riga 3739) Recensioni
- (riga 3838) Mappa
- (riga 3863) Intestazione pagina
- (riga 3875) Opzione "Posizione del testo: a destra" del blocco Intestazione pagina
- (riga 3888) Occhiello e titolo stanno vicini; solo le briciole hanno un distacco
- (riga 3921) Due riquadri (Missione e visione)
- (riga 3960) Pulsante WhatsApp fisso
- (riga 4000) Arrivati al footer il pulsante diventa rotondo, solo icona (vedi JS/scripts.js initWhatsappCompact)
- (riga 4038) Lightbox generica: qualunque galleria può usarla aggiungendo alle sue miniature gli attributi data-lightbox-index/data-full/data-alt/data-caption (vedi JS/scripts.js initLightbox e il markup in footer.php). Nessun blocco del tema la usa ancora di default.
- (riga 4102) Larghezze e distanze condivise --gutter: distanza dai bordi della pagina (12px su mobile, fino a 24px su desktop), uguale ovunque (contenuto delle pagine, shop, blog, editor di Gutenberg). La larghezza massima dei contenuti è --content-width (Opzioni → Generale → Larghezza del sito); --wide-width = content + 200px.
- (riga 4122) Contenuto delle pagine: stessa distanza laterale ovunque
- (riga 4128) Barra delle briciole: fascia sotto l'header, testo nella larghezza dei contenuti (Opzioni → Generale)
- (riga 4158) Sopra una hero a tutta pagina le briciole non si mostrano
- (riga 4163) Con le briciole attive il contenuto parte subito sotto la barra: meno spazio sopra
- (riga 4168) Header: versione a tutta larghezza, hamburger animato, sottomenu col click, icone shop
- (riga 4172) Hamburger: tre linee accanto al testo "Menu"; con il menu aperto diventano una X
- (riga 4219) Voci con sottomenu come pulsanti (Opzioni → Header → "si aprono col click"): freccia a sinistra
- (riga 4250) La freccina a destra della modalità "link" non serve più
- (riga 4255) Sottomenu: si apre all'hover (desktop) o col click (classe is-open, vedi initSubmenus in JS); il focus da solo non lo tiene aperto
- (riga 4265) Chiuso col click mentre il mouse è ancora sopra: resta chiuso finché il mouse non esce
- (riga 4280) Icone dell'header: ricerca, account, preferiti, carrello (Opzioni → Header → Icone). Due stili, uguali ai social così sono uniformi: "con bordo" (bordo + riempimento e icona che ruota al passaggio) o "semplici".
- (riga 4303) Icona sdoppiata come i social: overflow nascosto, il duplicato aspetta fuori asse
- (riga 4327) Stile "con bordo" (di base): stesso aspetto e stessa animazione dei social
- (riga 4377) Stile "semplici": i social dell'header perdono bordo e animazione e si comportano come le altre icone
- (riga 4403) Icone "semplici": solo l'icona, un po' d'aria uguale tra social e icone (--header-icon-gap)
- (riga 4410) Icone "semplici": al passaggio niente sfondo, cambia solo il colore dell'icona (il colore hover dei social, Colori → Menu, footer e social)
- (riga 4428) Sopra una hero trasparente icone e divisore sono bianchi, come i social
- (riga 4443) Numero nel carrello (aggiornato dai "fragments" di WooCommerce) e nei preferiti (scritto dal JS)
- (riga 4479) Tooltip delle icone: pannello al passaggio del mouse o col focus (solo dove c'è l'hover)
- (riga 4508) Ponte invisibile: il pannello non si chiude andando dall'icona al pannello
- (riga 4547) Fisso, come le miniature di carrello e pagamento: non segue "Bordi del sito"
- (riga 4584) Link account/preferiti nel pannello: non servono, le icone restano nella barra anche su mobile
- (riga 4598) Schermi stretti: "Menu" più compatto e meno spazio tra gli elementi, così logo + icone + Menu stanno nella riga
- (riga 4627) Con le icone con bordo il numero del carrello/preferiti esce un po' dall'angolo
- (riga 4636) Spaziature riutilizzabili (margini e padding, con varianti tablet e mobile) Numeri = rem (mt05 = 0,5rem, mt10 = 10rem), 20 = 20px. Suffisso -tab: fino a 1180px, -sma: fino a 781px. Esempio: "mt4 mt2-tab mt1-sma" = 4rem sopra su desktop, 2rem su tablet, 1rem su mobile. Si usano nel campo "Classi CSS aggiuntive" dei blocchi: niente CSS da scrivere per ogni spazio.
- (riga 4666) Varianti del pulsante (.button): primario (di base), contorno (usato da shop e blog) Colori da Opzioni → Colori (pulsante primario / secondario).
- (riga 4670) .button--primary = Pieno 1 (è già l'aspetto di base di .button); .button--outline = Contorno 1; .button--fill-2 / .button--outline-2: vedi le variabili --b-* in alto, vicino a .button.
- (riga 4672) Pulsante chiaro per i riquadri con sfondo colorato (es. Sezione voci su fondo accent).
- (riga 4679) Footer Stile 4 (4 colonne con newsletter) — ripreso da Symbolikamente Colori da Opzioni → Colori → Footer (variabili --footer-*, già definite in .site-footer); testi dalle classi di sistema (title-h4, eyebrow, has-small-font-size): niente font-size qui.
- (riga 4692) Stessa larghezza dei contenuti della pagina (--content-width)
- (riga 4698) Footer Stile 4: link, logo, colonne, elenco, icone social e nota finale
- (riga 4785) Icone social del footer
- (riga 4811) Form newsletter (Contact Form 7) nel footer
- (riga 4915) Con la riga in colonna il flex-basis (360px) diventerebbe un'altezza: lascia lo spazio vuoto sotto la descrizione
- (riga 4937) Blocchi aggiuntivi (hero con slider, striscia valori, ultimi articoli, prodotti in evidenza, Instagram, team, contatti) — importati da Symbolikamente e Di Guida, resi generici. Colori da Opzioni → Colori; dimensioni dalle classi di sistema (title-*, eyebrow, has-*-font-size).
- (riga 4958) Spazio sopra e sotto le sezioni a contenitore
- (riga 4967) Solo il riquadro del modulo ha il colore scelto in Opzioni → Colori (principale): titoli, etichette e link lo seguono
- (riga 4989) Hero con slider: foto in dissolvenza, velo scuro, testo in basso a sinistra
- (riga 5044) Occhiello e titolo vicini, come nelle altre sezioni: nessuno spazio in mezzo
- (riga 5073) Uno o due pulsanti (il secondo, facoltativo, è "outline" chiaro sul velo scuro)
- (riga 5103) Striscia valori (come Symbolikamente): 4 colonne con una linea tra l'una e l'altra; sotto i 1024px 2 colonne, anche sul telefono, con la linea tra sinistra e destra e tra le righe
- (riga 5136) Ultimi articoli e prodotti: griglie
- (riga 5145) Instagram: griglia di foto quadrate
- (riga 5172) Team: 4 colonne, 3 sotto 1280px, 2 sotto 1024px, 1 sotto 640px
- (riga 5207) Spazio sotto il nome: valore esplicito. Prima dipendeva dall'ordine delle regole, diverso tra il CSS sorgente e quello compilato (npm run build).
- (riga 5212) Contatti: recapiti a sinistra, modulo a destra
- (riga 5284) Ricerca nell'header (inc/search.php, initSearchPanel in JS/scripts.js) Foglio a tutta larghezza che scende dall'alto sopra uno sfondo scuro: campo grande, "Cancella" e anteprima dei risultati divisi per tipo (prodotti, pagine, articoli) mentre si scrive.
- (riga 5290) Pannello di ricerca a tendina (header): intestazione e campo
- (riga 5427) Contenitore dei risultati della ricerca
- (riga 5435) I prodotti hanno una riga a tutta larghezza (griglia di miniature)
- (riga 5504) Prodotti: griglia di miniature; pagine e articoli: elenchi con miniatura piccola
- (riga 5566) Blocchi: Hero testo + foto, Sezione affiancata (foto + testo in Gutenberg)
- (riga 5671) Due foto: la seconda, più piccola, si sovrappone in basso a sinistra
- (riga 5705) Spazio libero del footer (Opzioni → Footer): titolo facoltativo e contenuto a piacere
- (riga 5719) Moduli di Contact Form 7 (pagine e blocco Contatti); nel footer valgono le regole ".footer-form"
- (riga 5722) Campo trappola anti-spam (inc/cf7.php): nascosto a tutti, visibile ai soli robot
- (riga 5737) Moduli a griglia (Contatti): campi a coppie su due colonne, quelli "full" a tutta larghezza; sotto i 600px una colonna sola
- (riga 5758) Scelta multipla "Come posso aiutarti": opzioni a pillole che vanno a capo
- (riga 5792) Rotellina d'invio di Contact Form 7: sta fuori dal flusso (absolute) e si vede solo mentre il modulo sta inviando, così non lascia spazio vuoto. Sta in basso a destra, all'altezza del pulsante; il vecchio messaggio di esito si nasconde durante l'invio per non spostarla.
- (riga 5892) Lingua (WPML): sempre l'ultima voce, dopo le icone, con la sua linea di divisione; tooltip come il carrello
- (riga 5955) Schermi molto stretti: logo, icone e lingua si stringono e il pulsante Menu resta con la sola icona (il testo è nell'aria-label)
- (riga 5979) Sezione finale con sfondo in parallasse: testo sempre bianco sul velo scuro
- (riga 5985) Mappa: attaccata alla sezione sopra e al footer; gli spazi li decidi con le classi pt/pb/mt/mb
- (riga 5990) Sezione finale con sfondo (cta-cover): ha un po' di spazio dal blocco sopra ed è attaccata al footer (con "mt0" lo togli)
- (riga 6001) Gruppi di Gutenberg a tutta larghezza: un minimo di spazio ai lati (il testo non tocca il bordo del browser) e contenuto nella larghezza del sito (--content-width). La copertina finale (cta-cover) resta a filo.
- (riga 6012) Titolo di pagina di Blog, archivi, ricerca e Shop: stesso del blocco "Intestazione pagina"
- (riga 6025) Descrizione di categorie e tag (WooCommerce e blog) avvolta in un contenitore: stessi margini del testo introduttivo dello Shop (nessuno sopra il primo e sotto l'ultimo paragrafo).
- (riga 6036) Briciole nel titolo (stile "inline") sopra scheda prodotto e articolo
- (riga 6049) Briciole sopra pagine senza il blocco "Intestazione pagina" (pagine normali, articolo, scheda prodotto): stessa posizione e distanze del titolo di pagina
- (riga 6058) Barra annunci (Opzioni → Barra annunci, inc/topbar.php): fascia in cima alla pagina, sopra l'header, non fissa (scorre via). Il corpo della pagina scende dell'altezza della barra e l'header (fisso) segue finché la barra è visibile (--topbar-h e --topbar-offset li aggiorna initTopBar in JS/scripts.js). I testi scorrono in continuazione: la pista è fatta di due gruppi identici e l'animazione la sposta di un gruppo (-50%).
- (riga 6120) Con "riduci animazioni" il testo sta fermo: un solo giro di annunci, a capo e centrato
- (riga 6148) Con la barra di WordPress la barra annunci parte sotto di lei
- (riga 6159) Avviso "negozio in test" (Opzioni → Shop): in fondo alla pagina, sotto il footer. Giallo fisso di proposito: deve vedersi su qualunque palette.
- (riga 6170) Sommario dell'articolo (Opzioni → Blog → Sommario): riquadro con elenco numerato dei titoli, link che scorrono al titolo
- (riga 6193) Pulsante apri/chiudi: con bordo, testo e freccia (la freccia gira quando il sommario è aperto)
- (riga 6277) I titoli raggiunti dal sommario non finiscono sotto l'header fisso
- (riga 6292) Hero con foto e video (Chi siamo): testo a sinistra con foto dei clienti, a destra una foto e un video verticale
- (riga 6364) Foto e video affiancati, il video più alto e più in basso; l'etichetta della foto sta sopra, quella del video sotto (testo bianco)
- (riga 6381) la tabbetta della foto parte all'altezza dell'immagine del video: la foto resta sotto di lei
- (riga 6408) Etichetta con le parole chiave sopra la foto/video: pillola colorata, colore scelto nel blocco tra quelli di Opzioni → Colori
- (riga 6412) centrata rispetto alla foto o al video
- (riga 6414) più spazio dall'immagine: girando (12px) non deve avvicinarsi troppo
- (riga 6417) gira su un piccolo cerchio, come la card della hero degli immobili
- (riga 6434) le due etichette non girano in sincronia
- (riga 6488) Pagina 404: campo di ricerca come nel resto del sito (stesso aspetto della ricerca dell'aside) e link per ripartire
- (riga 6546) Pannello del menu su mobile: lista e sottomenu occupano tutta la larghezza, e ogni voce è un'area di tocco lunga come il blocco
- (riga 6619) Animazioni morbide di base: ogni link, pulsante e campo cambia colore con dolcezza (le regole più specifiche hanno già la loro)
- (riga 6625) Video dei moduli hero: mai controlli (play, volume, schermo intero) nemmeno quando il browser mobile li proporrebbe
- (riga 6646) Sommario dell'articolo: su desktop largo quanto il suo contenuto, su mobile a tutta larghezza
- (riga 6654) Icone dell'header su mobile (account, preferiti, carrello, ricerca): sempre senza bordo e senza sfondo al passaggio, stesso colore dei social, al massimo 34px l'una e senza spazio in mezzo, così il gruppo è compatto
- (riga 6694) Portfolio (inc/portfolio.php): archivio con schede, card, scheda del progetto e blocchi "Progetto — …"
- (riga 6698) Filtro nella stessa pagina: mentre carica, l'elenco si attenua
- (riga 6705) Schede delle categorie (link semplici, funzionano senza JavaScript)
- (riga 6744) Griglia dei progetti: colonne dal campo del blocco "Elenco progetti" (2/3/4)
- (riga 6747) predefinita (es. "Altri progetti"): nel blocco si sceglie con --gap-x / --gap-y
- (riga 6780) ---- Visualizzazioni dell'elenco (scheda Layout del blocco): le colonne arrivano dal blocco in --pc-d / --pc-t / --pc-m ----
- (riga 6797) Classica: immagini della stessa altezza, proporzione scelta nel blocco
- (riga 6802) Masonry: colonne CSS e formato originale della copertina
- (riga 6827) A blocchi: gruppi da 3 (2 orizzontali a sinistra + 1 verticale a destra, poi invertiti) e quarto elemento facoltativo a tutta larghezza
- (riga 6879) L'elemento verticale riempie l'altezza delle due card orizzontali
- (riga 6893) via di mezzo tra una riga e due righe
- (riga 6922) Sfalsata: sempre 2 colonne, la seconda più in basso; ogni immagine alterna larghezza piena e ridotta, allineate ai lati opposti; formato originale
- (riga 6971) Il link della card non cambia mai colore (il tema colora i link con l'accento al passaggio del mouse): i testi che lo ereditano restano fermi, senza lampi di colore all'entrata e all'uscita. I colori al passaggio del mouse si scelgono solo nel blocco (scheda Titolo).
- (riga 7003) stessa distanza dai bordi dei testi sopra l'immagine
- (riga 7011) Riassunto e cliente: nessun margine, solo 4px tra le due righe (in qualunque ordine)
- (riga 7025) Riassunto/cliente sopra l'immagine: in basso a sinistra, stessa distanza dai bordi delle categorie (12px). Testo nel colore di sfondo del sito, con sfumatura dal colore del testo per leggerlo sulle foto; se scegli un colore vale quello.
- (riga 7048) Colore al passaggio del mouse sulla card (facoltativo, dal blocco: scheda Titolo)
- (riga 7061) Video al passaggio del mouse: sopra l'immagine, compare quando parte (classe is-playing, messa da JS/scripts.js)
- (riga 7078) Animazione logo (blocco → Titolo): logo al centro dell'immagine; al passaggio del mouse sparisce e il nome del cliente scorre da destra a sinistra (senso di lettura). Dimensioni in cqw (larghezza della card), colore: quello scelto per il cliente o il colore di sfondo del sito.
- (riga 7102) Il logo non è una foto della card: le regole di ".project-card__media img" (riempire, ritagliare, ingrandire, posizionarsi) non lo toccano, e si vede sempre intero
- (riga 7116) Il nome (scritta che scorre, o testo al posto del logo) è sempre in maiuscolo
- (riga 7201) Categorie: testo con simbolo, tab sotto l'immagine (piene/vuote), tab sopra l'immagine (singole o unica). Solo colori del tema.
- (riga 7215) Angoli come i pulsanti (Opzioni → Pulsanti), font e dimensione del tema. Maiuscole e colore si scelgono nel blocco (Categorie): --cat-color / --cat-color-text
- (riga 7270) Categorie prima dei titoli: più spazio, così non sono appiccicate
- (riga 7289) Testata del progetto: titolo a sinistra, dati a destra
- (riga 7314) Telefono: i dati del progetto (servizi, agenzia, partner, visita il progetto) vanno uno sotto l'altro
- (riga 7346) Testo editoriale sotto il titolo: le righe andate a capo restano tali, larghezza da lettura
- (riga 7352) Niente margine sotto, mai: più specifica di ".txt" (margin-bottom: 1rem), che altrimenti vince
- (riga 7357) Riassunto del progetto: è l'H1, con la formattazione del titolo H5, tutto in maiuscolo e nel colore degli occhielli
- (riga 7367) Elenchi dei dati del progetto: niente margine sotto le voci; i nomi di agenzie e partner hanno un filo di spazio tra loro (non sotto l'ultimo)
- (riga 7373) tra l'H6 (13px) e l'H5 (18–20px)
- (riga 7385) Servizi: tab come le categorie degli articoli, su tutta la larghezza dei dati del progetto e a capo se non ci stanno
- (riga 7408) Copertina a tutta larghezza (video o immagine)
- (riga 7423) Blocco "Progetto — testo"
- (riga 7459) Blocco "Progetto — media": 1, 2 o 3 colonne, immagini e video
- (riga 7516) Blocco "Progetto — crediti"
- (riga 7557) Altri progetti
- (riga 7582) Elenco servizi (blocco "Elenco servizi", template-parts/elenco-servizi) Menu laterale che segue lo scroll + una scheda per servizio. Su tablet e mobile il menu diventa una barra di sole icone fissa in basso (JS: initServicesSpy / initServicesDock / initServicesLabelScroll in JS/scripts.js).
- (riga 7588) Blocco "Elenco servizi" (.servizi): griglia, menu laterale e voci
- (riga 7615) Menu dei servizi (link e icone)
- (riga 7663) Singolo servizio: corpo, testo e spunte
- (riga 7760) Mobile e tablet: solo icone, fissa in basso finché si scorrono i servizi. Superata la fine dell'elenco, JS aggiunge "is-docked" e la ferma nello spazio prima della sezione successiva; risalendo torna a seguire lo scroll.
- (riga 7767) Spazio tra l'elenco e il blocco sotto, dove si ferma la barra di icone
- (riga 7778) Larga quanto le sue icone (non quanto lo spazio che resta a destra del 50%): così nessuna icona viene tagliata, né fissa né ferma
- (riga 7821) Mobile: senza WhatsApp la barra di icone sta vicino al bordo basso; con il pulsante WhatsApp (barra incollata in basso) sta sopra di lei
- (riga 7832) Tablet: il pulsante WhatsApp è un pulsante fluttuante, la barra resta alla stessa altezza
- (riga 7839) Slider / carosello (blocco ACF "Slider"). Variabili impostate dal blocco: --sld-d/t/m (immagini visibili su desktop/tablet/mobile), --sld-gap, --sld-peek (px delle vicine che si intravedono), --sld-ratio
- (riga 7925) Frecce: quadrate come gli altri pulsanti (Opzioni → Pulsanti), solo icona
- (riga 7947) Sull'immagine, ai lati: le due frecce stanno agli estremi, centrate in altezza
- (riga 7963) Barra sopra (frecce a destra) e sotto (paginazione a sinistra, frecce a destra)
- (riga 7977) Frecce sotto: la paginazione resta al centro, le frecce a destra (la barra di avanzamento occupa lo spazio libero)
- (riga 8004) Puntino attivo allungato (a pillola)
- (riga 8047) Una sola schermata (poche slide): niente comandi
- (riga 8060) A tutto schermo le barre (frecce, paginazione) restano dentro i margini della pagina
- (riga 8066) Header "Pillole" (Opzioni → Header → Versione): senza sfondo, sempre visibile mentre il contenuto scorre sotto. Logo a sinistra; menu e pulsante in due pillole separate a destra. Larghezza: quella del sito, o a tutta larghezza se è attiva l'opzione "A tutta larghezza". Colori e angoli: quelli del tema (primario, sfondo, arrotondamento dei pulsanti).
- (riga 8098) Due pillole tonde della stessa altezza (menu e pulsante), vicine, come nel riferimento
- (riga 8110) Bordi del sito (Generale), non l'opzione dei pulsanti
- (riga 8132) Footer "Stile 5" (titolo grande): nome del sito gigante, frase, contatti e sedi. Colori: quelli del footer.
- (riga 8190) misura del paragrafo normale
- (riga 8216) come i contatti: paragrafo normale
- (riga 8247) Filtri dell'elenco progetti: stili (scheda Filtri del blocco). Il filtro attivo cambia colore; su telefono i filtri stanno su una riga e scorrono di lato (tranne la frase, che va a capo).
- (riga 8255) Testo grande con barre: "Tutti / Branding / Web design", gli altri più chiari
- (riga 8257) spazio tra il numero di un filtro e la barra del successivo
- (riga 8269) la barra sta attaccata all'iniziale del nome che segue (B, G, L, W)
- (riga 8289) Pillole: una per categoria, piena la attiva. Angoli come i pulsanti.
- (riga 8314) Frase: i filtri sono parole sottolineate dentro il testo
- (riga 8346) Telefono: una riga con scorrimento laterale (la frase va a capo)
- (riga 8366) Transizione tra le pagine dei progetti (blocco Elenco progetti → Animazioni; Opzioni → Portfolio). Un velo disegnato su <html> (::before = immagine, tendina o sfondo; ::after = nome, scritta o percentuale), guidato da JS/scripts.js (initProjectTransition) con le classi: pt-cover | pt-tendina | pt-dissolvenza (tipo), pt-counter (percentuale), pt-go (velo acceso), pt-in (pagina di arrivo), pt-land / pt-leave (uscita). Colori: --pt-bg / --pt-fg se scelti, altrimenti quelli del tema.
- (riga 8385) --- Copertina: l'immagine della card cresce fino a riempire lo schermo; nel progetto si ridimensiona sulla copertina. Riquadro, dimensione e posizione dell'immagine cambiano insieme, con la stessa curva: movimento uniforme. ---
- (riga 8418) Copertina con video: il velo, arrivato sul video che sta già andando, svanisce in dissolvenza
- (riga 8429) --- Tendina: un pannello sale dal basso con il nome del cliente; nel progetto continua a salire e libera la pagina ---
- (riga 8473) --- Caricamento con dissolvenza: sfondo che appare, scritta (o percentuale) in basso a sinistra; quando finisce la scritta sale e sparisce, poi lo sfondo svanisce ---
- (riga 8530) Colori dei filtri (scheda Filtri del blocco): normale, hover, attivo. Per le pillole sono lo sfondo; il testo è chiaro o scuro in base al colore.
- (riga 8562) Progetto con la copertina a tutto schermo in cima (campo "Copertina a tutto schermo" nei dettagli del progetto)
- (riga 8563) Esce dal contenitore come tutti i blocchi a tutta larghezza (.alignfull): qui si toglie solo il margine sotto
- (riga 8588) Numero dei progetti nei filtri: piccolo, in un cerchio. Il colore del numero resta quello del testo (non cambia con hover e attivo), tranne nelle pillole.
- (riga 8610) Testo grande con barre: il cerchio sta in alto, come un'appendice sopra l'ultima lettera, e non occupa spazio: la barra si avvicina al nome
- (riga 8625) Numero dei progetti nei filtri, versione Classica: il cerchio è alto come il nome del filtro, il numero più piccolo
- (riga 8634) Testo grande: tutte le voci sulla stessa riga (la barra non spinge più in basso le altre)
- (riga 8647) Sottomenu di un sottomenu: su desktop si apre a destra, non sopra le altre voci; su telefono si apre come il sottomenu principale
- (riga 8658) Larghezza massima del menu a tendina: una voce più lunga non lo allarga all'infinito, il suo testo scorre (vedi "Voci lunghe dei sottomenu")
- (riga 8688) Voci lunghe dei sottomenu (JS: initMenuLabelScroll). Il testo sta in un riquadro che non lo lascia uscire; se non entra (classe is-overflowing) scorre fino alla fine: con il mouse al passaggio sopra (a riposo sfuma sul bordo), su touch in automatico, andata e ritorno con una pausa a ogni estremo. Una voce che entra non scorre. Con "riduci animazioni" il testo va semplicemente a capo.
- (riga 8762) Preloader del sito (Opzioni → Generale), tendina con la percentuale
- (riga 8769) Caricamento con il logo del sito (Opzioni → Generale): dissolvenza = in basso a sinistra, tenda = al centro. Di base il logo prende il colore del testo scelto; "Logo nel colore del testo" spento = colori originali.


## 4. JavaScript

### `JS/animations.js`
- `initSmoothScroll()` — Scroll fluido con Lenis.
- `raf()` — Fa avanzare Lenis a ogni frame

### `JS/customize-controls.js`
- `initRevealGroup()` — Un "gruppo" è un bottone "+ Aggiungi..." più un elenco di "slot" da rivelare uno alla volta. Ogni slot ha: - ids: TUTTI gli ID controllo che appartengono allo slot (nascosti/ rivelati insieme). - valueIds: il sottoinsieme di "ids" da guardare per capire se lo slot ha già un valore reale al caricamento della pagina (quindi va mostrato subito, non nascosto) — VOLUTAMENTE più corto di "ids": esclude i menu a tendina peso/stile, che hanno sempre un valore di default non vuoto ("400"/"normal") anche quando nessun file è stato caricato, e quindi darebbero un falso positivo. - removeButtonId: (facoltativo) l'ID del bottone "Rimuovi..." di questo slot, se presente — al click svuota tutti gli "ids" (tranne se stesso) e nasconde di nuovo lo slot.
- `customFontSlot()` — Lo slot "sempre insieme" di un font personalizzato (Font 3 o Font 4), rivelato dal proprio bottone dedicato ".starter-add-custom-font-button-{n}" (non condiviso con l'altro font — ciascuno ha titolo + bottone sempre visibili, il resto nascosto finché non si preme il bottone): nome, la prima variante (peso/stile + 5 upload), il bottone "+ Aggiungi variante", il gruppo di checkbox "Applica a" e il bottone "Rimuovi questo font". Le varianti 2-4 NON sono incluse: restano gestite (e nascoste) dal proprio slot, vedi variantSlots() più sotto.
- `variantSlots()` — Un font caricato (Font 3/Font 4) può avere fino a 4 varianti peso/stile: solo la prima è visibile di default (vedi customFontSlot() sopra), le altre 3 (v2..v4) sono rivelate una alla volta dal bottone ".starter-add-font-variant-button-{n}", e ciascuna ha il proprio bottone "Rimuovi questa variante".
- `initColorGroups()` — Sezione "Colori": 4 gruppi (Tavolozza, Sfondi/testi/link, Menu/footer/social, Tag) che si aprono e chiudono come una fisarmonica. I titoli dei gruppi sono i controlli "starter_heading_cgroup_*"; i controlli che li seguono (fino al titolo successivo) sono il gruppo. Si nasconde con una classe (non con style.display) per non disturbare gli slot che il pulsante "+ Aggiungi colore" rivela da sé.
- `build()` — Crea i gruppi a fisarmonica dei colori

### `JS/editor-blocks.js`
- Editor di blocchi: mostra sui blocchi ACF del tema lo "Sfondo della sezione" e le "Classi CSS personalizzate" (scheda Avanzate), come sul sito. Il frontend le applica in PHP (inc/acf-blocks.php, filtro render_block); qui si replica sul contenitore del blocco. window.starterEditorBg = { slots: { slot_N: { value, dark } }, legacy: { vecchiaChiave: 'slot_N' } }.

### `JS/icon-picker.js`
- `svg()` — Disegna un'icona (della libreria o SVG caricato) come stringa SVG
- `el()` — Crea un elemento con classe e testo
- `build()` — Costruisce la finestra del selettore (una volta sola)
- `more()` — Aggiunge un altro gruppo di icone alla griglia
- `refresh()` — Filtra per scheda e ricerca, ordina per pertinenza e ridisegna la griglia
- `uploadSvg()` — "Carica SVG": Libreria media filtrata sugli SVG (con la scheda di caricamento). L'SVG scelto diventa un'icona "svg-ID".
- `open()` — Apre il selettore per un campo
- `close()` — Chiude il selettore
- `choose()` — Scrive l'icona scelta nel campo e nell'anteprima

### `JS/immobili.js`
- `initPlanDownload()` — Scarica tutte le planimetrie (un file per volta) quando sono più di una.
- `init()` — Avvia le funzioni del modulo

### `JS/scripts.js`
- `initStickyHeader()` — Nascondersi/riapparire su scroll solo se l'header non è flottante.
- `initHamburgerMenu()` — Toggle hamburger: pannello mobile è ancora una base da rifinire, qui solo la logica.
- `initSearchPanel()` — Ricerca: la lente dell'header apre un pannello a tutta larghezza; mentre si scrive mostra l'anteprima dei risultati (prodotti, pagine, articoli) dall'endpoint REST del tema (inc/search.php). Si chiude con Esc, click sullo sfondo o la X, e il focus torna alla lente. Invio porta alla pagina dei risultati: il form funziona anche senza JS.
- `initLangSwitcher()` — Selettore di lingua (WPML): sul touch il tooltip si apre col tocco; si chiude toccando fuori o con Esc.
- `initTopBar()` — Barra annunci (inc/topbar.php): completa i gruppi di testi fino a coprire lo schermo, imposta la durata del giro in base alla velocità scelta, e tiene l'header (fisso) sotto la barra finché è visibile.
- `initBlogSidebarToggle()` — Sidebar blog: overlay a tutto schermo dal pulsante "Filtri e ricerca".
- `initFooterSocialMagnetic()` — Icona+etichetta footer si muovono opposte al mouse (magnetico); disattivato con reduced-motion.
- `initReveal()` — Comparsa allo scroll di qualunque elemento con attributo data-reveal (blocchi ACF in template-parts/).
- `initScrollTracks()` — Frecce di un carosello con scroll-snap: qualunque bottone con data-scroll-for="id-del-track".
- `initDragScroll()` — Caroselli trascinabili col mouse: qualunque contenitore con attributo data-drag-scroll (touch/trackpad scorrono già in modo nativo).
- `initReviewsLoop()` — Carosello recensioni in loop (clona le card ai due estremi per far sembrare lo scroll infinito).
- `initLightbox()` — Lightbox generica: qualunque galleria può usarla, basta che ogni miniatura sia un elemento con attributo data-lightbox-index (l'indice nella sequenza) e data-full/data-alt/data-caption. Il markup della finestra (#lightbox) è in footer.php, stampato una sola volta per pagina.
- `initWhatsappCompact()` — Pulsante WhatsApp: diventa rotondo (solo icona) quando il footer entra in vista.
- `isDesktopNav()` — Menu desktop (barra orizzontale) e non pannello: sopra 1180px e senza l'header "Ridotto".
- `isHoverNav()` — Desktop con mouse: i sottomenu si aprono anche all'hover.
- `initWishlist()` — Cuore "preferiti" delle card: per ora solo visivo, ricordato in questo browser (localStorage). La lista dei desideri vera è da decidere.
- `initWishlistTip()` — Tooltip del cuore nell'header: i prodotti salvati (l'elenco sta nel browser, il server dà foto, nome e prezzo). Si riscrive solo se l'elenco è cambiato dall'ultima volta.
- `initWishlistPage()` — Pagina Preferiti ([starter_preferiti]): chiede al server le card dei prodotti salvati (le stesse del negozio) e le toglie quando si spegne il cuore; senza preferiti mostra l'invito a scoprire i prodotti.
- `initCartBadge()` — Carrello nell'header: WooCommerce aggiorna il numero con i "fragments" dopo ogni aggiunta; qui il badge fa un piccolo "salto" e il pulsante della card mostra la conferma per un paio di secondi.
- `initSubmenus()` — Voci con sottomenu (pulsante .submenu-toggle): il click apre e richiude il sottomenu (classe is-open sul <li>). Su desktop si apre anche all'hover: se lo chiudi col click mentre il mouse è sopra, resta chiuso finché il mouse non esce (classe is-closed). Esc o click fuori chiudono tutto.
- `initServicesSpy()` — Elenco servizi: voce attiva nel menu laterale (segue lo scroll)
- `initServicesLabelScroll()` — Menu servizi: se il titolo non entra nel pulsante, al passaggio del mouse scorre fino alla fine.
- `initMenuLabelScroll()` — Voci dei sottomenu (anche sottomenu di sottomenu): il testo va in un riquadro che non lo lascia uscire. Se non entra, scorre fino alla fine (classe is-overflowing): con il mouse al passaggio sopra, su touch in automatico. Se entra, resta fermo. Stile in CSS/starter.css ("Voci lunghe dei sottomenu").
- `initServicesDock()` — Barra di icone dei servizi (mobile/tablet): fissa in basso finché si scorre l'elenco, poi si ferma prima della sezione successiva
- `initQuantityButtons()` — Pulsanti − e + del campo quantità (markup da inc/woocommerce.php): cambiano il valore nei limiti min/max e avvisano gli altri script con "change".
- `initFilters()` — Filtri degli elenchi (Shop, Immobili e ogni altro tipo di contenuto con lo stesso markup .filters-wrap): vedi CSS/filters.css.
- `initLiveSearch()` — Ricerca con suggerimenti: mentre scrivi (da 2 lettere) mostra fino a 6 prodotti sotto al campo, presi dalla Store API di WooCommerce (pubblica, già nel plugin). Frecce su/giù per scegliere, Invio apre il prodotto o cerca, Esc chiude. Senza JS il campo resta una normale ricerca.
- `initFlipButtons()` — Pulsanti di WooCommerce stampati senza l'animazione del tema (es. "Aggiungi al carrello" della scheda prodotto): stesso testo che ruota delle card.
- `initProductWish()` — Scheda prodotto: il cuore dei preferiti va dentro la galleria, accanto alla lente dello zoom.
- `initHeroSlider()` — Hero con slider: dissolvenza tra le foto ([data-hero-slider] = millisecondi per foto); ferma con "riduci animazioni" e a scheda nascosta.
- `initSliders()` — Blocco "Slider": frecce, paginazione (puntini, contatore, barra), autoplay, trascinamento col mouse. La traccia scorre da sola (scroll-snap): qui si calcola solo il passo (larghezza di una slide + distanza) e si aggiornano i comandi.
- `initAutoplayVideos()` — Video decorativi (hero, slider, testata e media dei progetti: ogni <video autoplay>): senza controlli e con avvio automatico anche su mobile (muto + playsinline; se il browser lo blocca si riprova al primo tocco).
- `initProjectVideos()` — Card del Portfolio: il video parte al passaggio del mouse (o con la tastiera) e si ferma uscendo; su touch parte da solo quando la card è in vista e si ferma uscendo dallo schermo. Mai con "riduci animazioni".
- `initProjectVideosInView()` — Touch (nessun hover): il video di ogni card parte quando la card è per metà in vista e si ferma quando esce, così sul telefono ne gira solo qualcuno.
- `centerActiveFilter()` — Filtri dell'elenco progetti su telefono: la riga scorre di lato, quindi il filtro attivo si porta al centro (senza muovere la pagina).
- `initFooterFit()` — Footer "Stile 5": il nome grande riempie la larghezza del footer, qualunque sia la lunghezza del nome o il font.
- `initProjectTransition()` — Transizione tra le pagine dei progetti (scheda Animazioni del blocco Elenco progetti; tra un progetto e l'altro, Opzioni → Portfolio): cover | tendina | dissolvenza. Niente View Transitions: al clic si disegna un velo sulla pagina (classi "pt-…" su <html>, vedi CSS), si salva cosa mostrare in sessionStorage e si apre il progetto; la pagina di arrivo ha già il velo (script in <head>, vedi inc/portfolio.php) e lo toglie quando è pronta. L'animazione non dipende dalla velocità di caricamento.
- `initProjectFilters()` — Elenco progetti con "Resta nella stessa pagina": schede delle categorie e paginazione scaricano la stessa pagina e sostituiscono solo il contenuto del blocco, senza ricaricare. L'indirizzo si aggiorna (indietro/avanti funzionano); se qualcosa non va si apre il link normale.

---

*Generato il 2026-10-24 dai commenti presenti nei file del tema.*
