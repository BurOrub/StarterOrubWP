/**
 * Genera i dati della libreria icone del tema (inc/icons-data/).
 *
 * Sorgenti (devDependencies): "lucide-static" (icone lineari, ISC), "simple-icons" (loghi dei brand, CC0) e "bootstrap-icons" (MIT: loghi che mancano
 * in Simple Icons, es. LinkedIn).
 * Uso:  npm i -D lucide-static simple-icons bootstrap-icons && node tools/build-icons.js
 * (oppure LIB_DIR=/percorso/node_modules node tools/build-icons.js)
 *
 * Output:
 *  - inc/icons-data/{a..z,_}.json   markup interno degli SVG Lucide, a blocchi per lettera (il tema legge solo il blocco che serve)
 *  - inc/icons-data/brands.json     markup dei loghi: { "instagram": "<path d=…/>" }  (nome nel tema: "brand-instagram"; sempre su viewBox 24)
 *  - inc/icons-data/index.json      ricerca: { "nome": "parole chiave inglesi e italiane" }
 */

const fs = require( 'fs' );
const path = require( 'path' );

const root = path.resolve( __dirname, '..' );
const lib = process.env.LIB_DIR || path.join( root, 'node_modules' );
const out = path.join( root, 'inc', 'icons-data' );

const nodes = JSON.parse( fs.readFileSync( path.join( lib, 'lucide-static', 'icon-nodes.json' ), 'utf8' ) );
const tags = JSON.parse( fs.readFileSync( path.join( lib, 'lucide-static', 'tags.json' ), 'utf8' ) );

/* Parole chiave in italiano per le icone più comuni (chi cerca "casa" deve trovare "house"). */
const it = {
	house: 'casa home abitazione', home: 'casa home', building: 'edificio palazzo azienda', 'building-2': 'edificio palazzo azienda ufficio', store: 'negozio bottega',
	shop: 'negozio', 'shopping-cart': 'carrello spesa acquisti', 'shopping-bag': 'borsa shopping acquisti', 'shopping-basket': 'cestino spesa',
	user: 'utente persona profilo', users: 'persone utenti team gruppo', 'user-round': 'utente persona', 'circle-user': 'utente profilo account',
	phone: 'telefono chiamata', smartphone: 'cellulare telefono', mail: 'email posta lettera', 'mail-open': 'email posta', 'message-circle': 'messaggio chat commento',
	'message-square': 'messaggio chat commento', send: 'invia spedisci', clock: 'orologio ora tempo orario', calendar: 'calendario data appuntamento',
	'map-pin': 'posizione luogo indirizzo mappa dove', map: 'mappa cartina', globe: 'mondo globo internet sito web', link: 'collegamento link catena',
	search: 'cerca ricerca lente', heart: 'cuore amore preferiti piace', star: 'stella preferito valutazione recensione', award: 'premio riconoscimento medaglia',
	trophy: 'trofeo coppa premio vittoria', medal: 'medaglia premio', crown: 'corona re premium', gift: 'regalo dono omaggio', package: 'pacco prodotto confezione',
	truck: 'camion spedizione consegna trasporto', 'package-check': 'pacco consegnato ordine', 'credit-card': 'carta di credito pagamento', wallet: 'portafoglio pagamento',
	'badge-check': 'verificato garanzia certificato', 'shield-check': 'scudo sicurezza garanzia protezione', shield: 'scudo sicurezza protezione', lock: 'lucchetto sicurezza privato',
	key: 'chiave accesso', 'check': 'spunta ok conferma', 'circle-check': 'spunta ok conferma completato', 'check-check': 'spunta doppia ok',
	x: 'chiudi croce elimina annulla', plus: 'piu aggiungi', minus: 'meno togli', 'arrow-right': 'freccia destra avanti', 'arrow-left': 'freccia sinistra indietro',
	'arrow-up': 'freccia su', 'arrow-down': 'freccia giu', 'arrow-up-right': 'freccia diagonale esterno', 'chevron-right': 'freccia destra avanti', 'chevron-down': 'freccia giu menu',
	menu: 'menu hamburger', settings: 'impostazioni ingranaggio configurazione', wrench: 'chiave inglese riparazione manutenzione assistenza', hammer: 'martello lavori costruzione',
	'hard-hat': 'casco cantiere edilizia', paintbrush: 'pennello pittura decorazione', palette: 'tavolozza colori design', brush: 'pennello', pen: 'penna scrivere', pencil: 'matita scrivere modifica',
	'pen-tool': 'penna grafica design', scissors: 'forbici taglio', ruler: 'righello misura', camera: 'fotocamera foto', image: 'immagine foto', video: 'video filmato', film: 'film cinema',
	music: 'musica nota', mic: 'microfono audio podcast', headphones: 'cuffie ascolto musica assistenza', 'volume-2': 'volume audio altoparlante', play: 'play avvia riproduci',
	'file-text': 'documento file testo', file: 'file documento', folder: 'cartella', download: 'scarica download', upload: 'carica upload', printer: 'stampante stampa',
	'book-open': 'libro leggere guida', book: 'libro', 'graduation-cap': 'laurea cappello formazione corso scuola', library: 'biblioteca libri', newspaper: 'giornale notizie news blog',
	lightbulb: 'lampadina idea', zap: 'fulmine energia veloce rapido', flame: 'fuoco fiamma caldo', sun: 'sole luce', moon: 'luna notte', cloud: 'nuvola', droplet: 'goccia acqua', leaf: 'foglia natura ecologia bio',
	sprout: 'germoglio crescita natura', trees: 'alberi bosco natura', tree: 'albero natura', flower: 'fiore', mountain: 'montagna', 'tree-pine': 'pino albero natura', recycle: 'riciclo ambiente',
	coffee: 'caffe bar pausa', utensils: 'posate ristorante cibo mangiare', 'utensils-crossed': 'posate ristorante cibo', pizza: 'pizza cibo', wine: 'vino bicchiere', beer: 'birra', cake: 'torta dolce',
	'chef-hat': 'cappello cuoco chef cucina', soup: 'zuppa cibo', apple: 'mela frutta', carrot: 'carota verdura', croissant: 'cornetto colazione', 'ice-cream-cone': 'gelato',
	car: 'auto macchina', bus: 'autobus', bike: 'bicicletta bici', plane: 'aereo viaggio volo', ship: 'nave barca', train: 'treno', fuel: 'carburante benzina', 'parking-circle': 'parcheggio',
	stethoscope: 'stetoscopio medico salute', 'heart-pulse': 'salute cuore battito medico', pill: 'pillola farmaco medicina', syringe: 'siringa vaccino', activity: 'attivita battito grafico',
	dumbbell: 'manubrio palestra fitness allenamento', 'baby': 'bambino neonato', dog: 'cane animale', cat: 'gatto animale', bird: 'uccello animale', fish: 'pesce animale', paw: 'zampa animali',
	'scissors-line-dashed': 'taglio forbici parrucchiere', sparkles: 'scintille stelle magia novita pulizia', sparkle: 'scintilla stella novita', wand: 'bacchetta magica', 'party-popper': 'festa coriandoli evento',
	'chart-line': 'grafico crescita statistiche', 'chart-bar': 'grafico barre statistiche', 'chart-pie': 'grafico torta statistiche', 'trending-up': 'crescita aumento successo', 'trending-down': 'calo diminuzione',
	target: 'obiettivo bersaglio', flag: 'bandiera', rocket: 'razzo lancio startup', 'puzzle': 'puzzle soluzione', handshake: 'stretta di mano accordo partnership', 'hand-heart': 'aiuto solidarieta cuore',
	'thumbs-up': 'pollice su mi piace ok', 'thumbs-down': 'pollice giu', smile: 'sorriso felice', 'badge-percent': 'sconto offerta percentuale', percent: 'percentuale sconto', tag: 'etichetta prezzo', tags: 'etichette',
	'receipt': 'scontrino ricevuta fattura', 'banknote': 'banconota soldi contanti', coins: 'monete soldi', 'piggy-bank': 'salvadanaio risparmio', euro: 'euro soldi', 'circle-dollar-sign': 'dollaro soldi',
	'briefcase': 'valigetta lavoro ventiquattrore', 'clipboard-list': 'lista controllo elenco', 'clipboard-check': 'checklist controllo', 'list-checks': 'checklist elenco spunte', 'list': 'elenco lista',
	'layout-grid': 'griglia layout', 'layout-dashboard': 'dashboard pannello', 'monitor': 'schermo computer monitor', 'laptop': 'portatile computer', 'tablet': 'tablet', 'server': 'server hosting',
	'database': 'database dati', 'code': 'codice sviluppo', 'terminal': 'terminale console', 'cpu': 'processore tecnologia', 'wifi': 'wifi internet connessione', 'bluetooth': 'bluetooth', 'battery': 'batteria',
	'plug': 'spina presa elettricita', 'power': 'accensione spegni', 'bell': 'campanella notifica avviso', 'info': 'informazioni info', 'circle-help': 'aiuto domanda supporto', 'triangle-alert': 'attenzione avviso pericolo',
	'eye': 'occhio vedi visibile', 'eye-off': 'nascosto occhio', 'thermometer': 'termometro temperatura', 'umbrella': 'ombrello pioggia', 'anchor': 'ancora nautica', 'compass': 'bussola direzione',
	'sofa': 'divano arredamento casa', 'bed': 'letto camera hotel', 'bath': 'bagno vasca', 'lamp': 'lampada illuminazione', 'armchair': 'poltrona arredamento', 'door-open': 'porta ingresso', 'fence': 'recinzione',
	'warehouse': 'magazzino deposito', 'factory': 'fabbrica industria produzione', 'landmark': 'banca monumento istituzione', 'school': 'scuola', 'hospital': 'ospedale clinica', 'church': 'chiesa',
	'quote': 'citazione virgolette', 'languages': 'lingue traduzione', 'badge-help': 'aiuto assistenza', 'life-buoy': 'salvagente assistenza supporto', 'megaphone': 'megafono annuncio promozione',
	'share-2': 'condividi social', 'thumbs': 'pollice', 'qr-code': 'codice qr', 'scan': 'scansione', 'fingerprint': 'impronta digitale sicurezza', 'infinity': 'infinito', 'hourglass': 'clessidra attesa tempo',
	'timer': 'timer cronometro', 'alarm-clock': 'sveglia', 'history': 'cronologia storia', 'refresh-cw': 'aggiorna ricarica', 'rotate-ccw': 'annulla ripeti ruota', 'repeat': 'ripeti ciclo', 'shuffle': 'casuale',
	'filter': 'filtro', 'sliders-horizontal': 'filtri regolazioni', 'bookmark': 'segnalibro salva', 'pin': 'puntina fissa', 'paperclip': 'graffetta allegato', 'copy': 'copia', 'trash-2': 'cestino elimina',
	'save': 'salva', 'edit': 'modifica', 'external-link': 'link esterno apri', 'log-in': 'accedi entra', 'log-out': 'esci',
};

fs.mkdirSync( out, { recursive: true } );
for ( const f of fs.readdirSync( out ) ) {
	fs.unlinkSync( path.join( out, f ) );
}

const attrs = ( a ) => Object.entries( a )
	.filter( ( [ k ] ) => k !== 'key' )
	.map( ( [ k, v ] ) => ` ${ k }="${ v }"` )
	.join( '' );

const shards = {};
const index = {};

for ( const [ name, children ] of Object.entries( nodes ) ) {
	const markup = children.map( ( [ tag, a ] ) => `<${ tag }${ attrs( a ) }/>` ).join( '' );
	const key = /^[a-z]/.test( name ) ? name[ 0 ] : '_';
	( shards[ key ] = shards[ key ] || {} )[ name ] = markup;

	const words = [ name.replace( /-/g, ' ' ), ...( tags[ name ] || [] ), it[ name ] || '' ].join( ' ' ).toLowerCase().trim();
	index[ name ] = words;
}

for ( const [ key, data ] of Object.entries( shards ) ) {
	fs.writeFileSync( path.join( out, key + '.json' ), JSON.stringify( data ) );
}

/* Loghi dei brand: solo quelli utili a un sito di un'attività (social, mappe, pagamenti, piattaforme). */
const brandList = [
	'facebook', 'instagram', 'x', 'youtube', 'tiktok', 'whatsapp', 'telegram', 'pinterest', 'threads', 'linkedin', 'snapchat', 'reddit', 'discord', 'twitch', 'bluesky', 'mastodon',
	'messenger', 'signal', 'wechat', 'line', 'viber', 'tumblr', 'vimeo', 'soundcloud', 'spotify', 'applemusic', 'medium', 'behance', 'dribbble', 'figma', 'github', 'gitlab', 'codepen',
	'wordpress', 'woocommerce', 'shopify', 'google', 'googlemaps', 'googlechrome', 'googleads', 'googleanalytics', 'googlemeet', 'googledrive', 'gmail', 'apple', 'android', 'amazon',
	'paypal', 'stripe', 'visa', 'mastercard', 'americanexpress', 'applepay', 'googlepay', 'klarna', 'tripadvisor', 'airbnb', 'booking', 'ubereats', 'deliveroo', 'glovo', 'trustpilot',
	'yelp', 'etsy', 'ebay', 'mailchimp', 'notion', 'slack', 'zoom', 'skype', 'dropbox', 'canva', 'adobe', 'whatsappbusiness', 'meta', 'openai', 'wikipedia', 'flickr', 'vk', 'xing',
	'stackoverflow', 'npm', 'php', 'javascript', 'html5', 'css', 'react', 'vuedotjs', 'laravel', 'docker', 'wix', 'squarespace', 'webflow', 'elementor', 'yoast', 'cloudflare',
];
const brands = {};
const missing = [];

for ( const slug of brandList ) {
	const file = path.join( lib, 'simple-icons', 'icons', slug + '.svg' );

	if ( ! fs.existsSync( file ) ) {
		missing.push( slug );
		continue;
	}

	const svg = fs.readFileSync( file, 'utf8' );
	const title = ( svg.match( /<title>([^<]*)<\/title>/ ) || [ , slug ] )[ 1 ];
	const d = ( svg.match( /<path d="([^"]+)"/ ) || [] )[ 1 ];

	if ( ! d ) {
		missing.push( slug );
		continue;
	}

	brands[ slug ] = `<path d="${ d }"/>`;
	index[ 'brand-' + slug ] = `${ title } ${ slug } logo marchio brand social`.toLowerCase();
}

/* Loghi che Simple Icons non ha più (marchi rimossi per richiesta dei proprietari): si prendono da Bootstrap Icons (MIT), disegnati su viewBox 16 e quindi ingranditi a 24. */
const fromBootstrap = [ 'linkedin', 'amazon', 'slack', 'skype' ];
const stillMissing = [];

for ( const slug of fromBootstrap ) {
	const file = path.join( lib, 'bootstrap-icons', 'icons', slug + '.svg' );

	if ( brands[ slug ] || ! fs.existsSync( file ) ) {
		if ( ! brands[ slug ] ) { stillMissing.push( slug ); }
		continue;
	}

	const inner = ( fs.readFileSync( file, 'utf8' ).match( /<svg[^>]*>([\s\S]*)<\/svg>/ ) || [] )[ 1 ];

	if ( ! inner ) {
		stillMissing.push( slug );
		continue;
	}

	brands[ slug ] = `<g transform="scale(1.5)">${ inner.trim() }</g>`;
	index[ 'brand-' + slug ] = `${ slug } logo marchio brand social`;
}

fs.writeFileSync( path.join( out, 'brands.json' ), JSON.stringify( brands ) );
fs.writeFileSync( path.join( out, 'index.json' ), JSON.stringify( index ) );
fs.writeFileSync(
	path.join( out, 'LICENSE.txt' ),
	'Icone lineari: Lucide (https://lucide.dev), licenza ISC.\nLoghi dei brand: Simple Icons (https://simpleicons.org), licenza CC0; LinkedIn, Amazon, Slack e Skype da Bootstrap Icons (https://icons.getbootstrap.com), licenza MIT.\nI marchi appartengono ai rispettivi proprietari.\n'
);

console.log( `Lucide: ${ Object.keys( nodes ).length } icone in ${ Object.keys( shards ).length } blocchi; brand: ${ Object.keys( brands ).length } (mancanti: ${ missing.filter( ( m ) => ! brands[ m ] ).concat( stillMissing ).join( ', ' ) || 'nessuno' }).` );
