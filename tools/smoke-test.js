/**
 * Prova rapida di un sito dopo un aggiornamento di WordPress, plugin o tema.
 *
 *   node tools/smoke-test.js https://starter.local
 *
 * Scarica home, pagine e articoli (dalla sitemap di WordPress), più negozio, ricerca e 404, e controlla che
 * rispondano bene e che nell'HTML non compaiano errori PHP. Esce con codice 1 se qualcosa non va.
 * Non sostituisce una guardata a mano al sito, ma intercetta i problemi grossi in pochi secondi.
 */

'use strict';

const base = ( process.argv[ 2 ] || '' ).replace( /\/$/, '' );
const limit = 25;

if ( ! base ) {
	console.error( 'Uso: node tools/smoke-test.js https://sito.local' );
	process.exit( 1 );
}

// Il certificato dei siti locali non è riconosciuto.
process.env.NODE_TLS_REJECT_UNAUTHORIZED = '0';

const phpErrors = /(Fatal error|Parse error|Uncaught|Warning:|Notice:|Deprecated:|Stack trace:)/;
let failures = 0;

async function check( url, expect = 200 ) {
	try {
		const res = await fetch( url, { redirect: 'follow' } );
		const body = await res.text();
		const issues = [];

		if ( res.status !== expect ) {
			issues.push( `HTTP ${ res.status } (atteso ${ expect })` );
		}

		const found = body.match( phpErrors );

		if ( found ) {
			issues.push( `errore PHP nel HTML: "${ found[ 1 ] }"` );
		}

		if ( 200 === expect && body.length < 500 ) {
			issues.push( 'risposta quasi vuota' );
		}

		console.log( `${ issues.length ? '✘' : '✔' } ${ url.replace( base, '' ) || '/' }${ issues.length ? '  ← ' + issues.join( '; ' ) : '' }` );
		failures += issues.length ? 1 : 0;

		return body;
	} catch ( error ) {
		console.log( `✘ ${ url }  ← ${ error.message }` );
		failures++;

		return '';
	}
}

async function sitemapUrls() {
	const urls = [];

	for ( const index of [ '/wp-sitemap.xml', '/sitemap_index.xml' ] ) {
		const res = await fetch( base + index ).catch( () => null );
		const xml = res && res.ok ? await res.text() : '';
		const maps = [ ...xml.matchAll( /<loc>([^<]+)<\/loc>/g ) ].map( ( m ) => m[ 1 ] ).filter( ( u ) => /post|page|product/.test( u ) );

		for ( const map of maps ) {
			const sub = await ( await fetch( map ) ).text();
			urls.push( ...[ ...sub.matchAll( /<loc>([^<]+)<\/loc>/g ) ].map( ( m ) => m[ 1 ] ) );
		}

		if ( urls.length ) {
			return urls;
		}
	}

	// Nessuna sitemap: si seguono i link interni della home.
	const home = await ( await fetch( base + '/' ) ).text();

	return [ ...home.matchAll( /href="(https?:\/\/[^"#]+)"/g ) ].map( ( m ) => m[ 1 ] ).filter( ( u ) => u.startsWith( base ) && ! /\.(css|js|png|jpe?g|svg|webp|ico|xml)(\?|$)/i.test( u ) && ! /wp-(json|admin|login|content)|\?/.test( u ) );
}

( async () => {
	await check( base + '/' );
	await check( base + '/wp-json/starter/v1/search?q=prova' );
	await check( base + '/?s=prova' );
	await check( base + '/pagina-che-non-esiste-' + Date.now() + '/', 404 );

	const urls = [ ...new Set( await sitemapUrls() ) ].filter( ( u ) => u.replace( /\/$/, '' ) !== base ).slice( 0, limit );

	for ( const url of urls ) {
		await check( url );
	}

	console.log( `\n${ failures ? failures + ' problemi trovati' : 'Tutto a posto' } (${ urls.length + 4 } pagine controllate)` );
	process.exit( failures ? 1 : 0 );
} )();
