/**
 * Controlla e aggiorna le librerie JS servite dal tema (JS/vendor), elencate in JS/vendor/vendor.json (sta accanto ai file, quindi va online con loro).
 *
 *   node tools/update-vendor.js                  controlla soltanto: mostra versione attuale e ultima disponibile
 *   node tools/update-vendor.js --apply          scarica gli aggiornamenti minori e di patch
 *   node tools/update-vendor.js --apply --major  scarica anche quelli di versione maggiore (da provare prima in locale)
 *   node tools/update-vendor.js --set gsap@3.12.5   installa una versione precisa (anche per tornare indietro)
 *
 * Le versioni nel JSON sono quelle che functions.php usa per il parametro ?ver= (cache del browser).
 * File scaricati da jsDelivr (registro npm); si scrive solo se il file sembra davvero JavaScript.
 */

'use strict';

const fs = require( 'fs' );
const path = require( 'path' );

const root = path.resolve( __dirname, '..' );
const manifestPath = path.join( root, 'JS/vendor/vendor.json' );
const manifest = JSON.parse( fs.readFileSync( manifestPath, 'utf8' ) );

const args = process.argv.slice( 2 );
const apply = args.includes( '--apply' );
const allowMajor = args.includes( '--major' );
const setIndex = args.indexOf( '--set' );
const setSpec = setIndex > -1 ? args[ setIndex + 1 ] : '';

const major = ( v ) => parseInt( v.split( '.' )[ 0 ], 10 );

function newer( a, b ) {
	const pa = a.split( '.' ).map( Number );
	const pb = b.split( '.' ).map( Number );

	for ( let i = 0; i < 3; i++ ) {
		if ( ( pa[ i ] || 0 ) !== ( pb[ i ] || 0 ) ) {
			return ( pa[ i ] || 0 ) > ( pb[ i ] || 0 );
		}
	}

	return false;
}

async function latest( pkg ) {
	const res = await fetch( `https://registry.npmjs.org/${ pkg }/latest` );

	if ( ! res.ok ) {
		throw new Error( `registro npm: HTTP ${ res.status } per ${ pkg }` );
	}

	return ( await res.json() ).version;
}

async function install( name, lib, version ) {
	const url = `https://cdn.jsdelivr.net/npm/${ lib.npm }@${ version }/${ lib.file }`;
	const res = await fetch( url );

	if ( ! res.ok ) {
		throw new Error( `${ url }: HTTP ${ res.status }` );
	}

	const body = await res.text();

	if ( body.length < 5000 || /^\s*<(!doctype|html)/i.test( body ) ) {
		throw new Error( `${ name }@${ version }: il file scaricato non sembra JavaScript (${ body.length } byte)` );
	}

	const dest = path.join( root, lib.dest );
	const tmp = dest + '.tmp';

	fs.writeFileSync( tmp, body );
	fs.renameSync( tmp, dest );

	lib.version = version;
	fs.writeFileSync( manifestPath, JSON.stringify( manifest, null, '\t' ) + '\n' );
	console.log( `  ✔ ${ name } ${ version } scritto in ${ lib.dest } (${ Math.round( body.length / 1024 ) } KB)` );
}

( async () => {
	if ( setSpec ) {
		const [ name, version ] = setSpec.split( '@' );

		if ( ! manifest[ name ] || ! /^\d+\.\d+\.\d+$/.test( version || '' ) ) {
			console.error( 'Uso: --set nome@x.y.z (nomi: ' + Object.keys( manifest ).join( ', ' ) + ')' );
			process.exit( 1 );
		}

		await install( name, manifest[ name ], version );
		return;
	}

	let pending = 0;

	for ( const [ name, lib ] of Object.entries( manifest ) ) {
		const last = await latest( lib.npm );
		let note = 'aggiornata';

		if ( newer( last, lib.version ) ) {
			const isMajor = major( last ) > major( lib.version );
			note = isMajor ? `disponibile ${ last } (VERSIONE MAGGIORE: può cambiare il comportamento)` : `disponibile ${ last }`;

			if ( apply && ( ! isMajor || allowMajor ) ) {
				console.log( `${ name }: ${ lib.version } → ${ last }` );
				await install( name, lib, last );
				continue;
			}

			pending++;
		}

		console.log( `${ name.padEnd( 8 ) } attuale ${ lib.version.padEnd( 8 ) } ${ note }` );
	}

	if ( pending && ! apply ) {
		console.log( '\nPer scaricare: node tools/update-vendor.js --apply  (con --major anche le versioni maggiori)' );
	}
} )().catch( ( error ) => {
	console.error( 'Errore: ' + error.message );
	process.exit( 1 );
} );
