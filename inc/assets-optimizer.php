<?php
/** Compressione: unisce i CSS e i JS consecutivi in un solo file e comprime l'HTML su una riga (opzioni Prestazioni → "Unisci CSS e JS" e "Comprimi il codice HTML"). */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Cartella dei file uniti (uploads/starter-cache): si ricrea da sola, non va copiata online. */
function starter_cache_dir() {
	$upload = wp_get_upload_dir();

	return array(
		'path' => $upload['basedir'] . '/starter-cache',
		'url'  => $upload['baseurl'] . '/starter-cache',
	);
}

/* ============================================================ Minificazione */

/* CSS: toglie commenti e spazi inutili, lasciando intatti stringhe e url(). Se il risultato non torna (graffe diverse) restituisce l'originale. */
function starter_minify_css( $css ) {
	$keep = array();
	$min  = preg_replace_callback(
		'~/\*(!?).*?\*/|"(?:\\\\.|[^"\\\\])*"|\'(?:\\\\.|[^\'\\\\])*\'|url\([^)]*\)~s',
		function ( $m ) use ( &$keep ) {
			if ( '/*' === substr( $m[0], 0, 2 ) ) {
				return '';
			}

			$keep[] = $m[0];

			return "\x01" . ( count( $keep ) - 1 ) . "\x02";
		},
		$css
	);

	$min = preg_replace( '/\s+/', ' ', $min );
	$min = preg_replace( '/\s*([{};,>])\s*/', '$1', $min );
	$min = preg_replace( '/:\s+/', ':', $min );
	$min = str_replace( ';}', '}', trim( $min ) );
	$min = preg_replace_callback(
		'/\x01(\d+)\x02/',
		function ( $m ) use ( $keep ) {
			return $keep[ (int) $m[1] ];
		},
		$min
	);

	return substr_count( $css, '{' ) === substr_count( $min, '{' ) && substr_count( $css, '}' ) === substr_count( $min, '}' ) ? $min : $css;
}

/**
 * JS inline su una riga sola: legge il codice (stringhe, espressioni regolari, commenti) e al posto di ogni a capo mette uno spazio, ma solo dove
 * il codice non ne dipende (dopo ; { , ( [ : o un operatore, oppure prima di } ) ] , ; . ? :). Toglie i commenti. Se trova un a capo "ambiguo"
 * (una riga senza punto e virgola seguita da un'altra istruzione), un apice inverso o un caso che non sa leggere, restituisce null e lo script resta com'è.
 */
function starter_js_single_line( $js ) {
	if ( false !== strpos( $js, '`' ) ) {
		return null;
	}

	$len    = strlen( $js );
	$out    = '';
	$last   = ''; // Ultimo carattere significativo (non spazio) scritto.
	$i      = 0;
	$before = '(,=:[!&|?{};+-*%<>~^'; // Dopo questi un "/" apre una regex, non è una divisione.

	while ( $i < $len ) {
		$c = $js[ $i ];
		$n = $i + 1 < $len ? $js[ $i + 1 ] : '';

		if ( '"' === $c || "'" === $c ) {
			$j = $i + 1;

			while ( $j < $len && $c !== $js[ $j ] ) {
				if ( '\\' === $js[ $j ] ) {
					++$j;
				} elseif ( "\n" === $js[ $j ] ) {
					return null;
				}

				++$j;
			}

			if ( $j >= $len ) {
				return null;
			}

			$out .= substr( $js, $i, $j - $i + 1 );
			$i    = $j + 1;
			$last = $c;
			continue;
		}

		if ( '/' === $c && '/' === $n ) {
			while ( $i < $len && "\n" !== $js[ $i ] ) {
				++$i;
			}
			continue;
		}

		if ( '/' === $c && '*' === $n ) {
			$end = strpos( $js, '*/', $i + 2 );

			if ( false === $end ) {
				return null;
			}

			$i    = $end + 2;
			$out .= ' ';
			continue;
		}

		if ( '/' === $c ) {
			if ( '' === $last || false !== strpos( $before, $last ) || preg_match( '/(?:return|typeof|case|void|delete|throw|new|in|of)$/', $out ) ) {
				$j   = $i + 1;
				$set = false;

				while ( $j < $len ) {
					$d = $js[ $j ];

					if ( "\n" === $d ) {
						return null;
					}

					if ( '\\' === $d ) {
						$j += 2;
						continue;
					}

					if ( '[' === $d ) {
						$set = true;
					} elseif ( ']' === $d ) {
						$set = false;
					} elseif ( '/' === $d && ! $set ) {
						break;
					}

					++$j;
				}

				if ( $j >= $len ) {
					return null;
				}

				++$j;

				while ( $j < $len && ctype_alpha( $js[ $j ] ) ) {
					++$j;
				}

				$out .= substr( $js, $i, $j - $i );
				$i    = $j;
				$last = '/';
				continue;
			}

			$out .= '/';
			$last = '/';
			++$i;
			continue;
		}

		if ( ctype_space( $c ) ) {
			$has_break = false;
			$k         = $i;

			while ( $k < $len && ctype_space( $js[ $k ] ) ) {
				if ( "\n" === $js[ $k ] || "\r" === $js[ $k ] ) {
					$has_break = true;
				}

				++$k;
			}

			if ( $has_break && '' !== $last ) {
				$next = $k < $len ? $js[ $k ] : '';
				$tail = substr( rtrim( $out ), -2 );
				$safe = ( false !== strpos( ';{,([:+-*%=<>&|^!?', $last ) && '++' !== $tail && '--' !== $tail ) || ( '' !== $next && false !== strpos( '})],;.?:', $next ) );

				if ( ! $safe ) {
					return null;
				}
			}

			if ( '' !== $out && ' ' !== substr( $out, -1 ) ) {
				$out .= ' ';
			}

			$i = $k;
			continue;
		}

		$out .= $c;
		$last = $c;
		++$i;
	}

	return trim( $out );
}

/* JS inline: su una riga sola se è sicuro (vedi sopra); altrimenti toglie solo gli spazi a inizio e fine riga e le righe vuote (e, senza apici inversi, le righe di solo commento). */
function starter_minify_inline_js( $js ) {
	$js     = trim( $js );
	$single = starter_js_single_line( $js );

	if ( null !== $single ) {
		return $single;
	}

	if ( false !== strpos( $js, '`' ) || false === strpos( $js, "\n" ) ) {
		return $js;
	}

	$lines = array();

	foreach ( preg_split( '/\R/', $js ) as $line ) {
		$line = trim( $line );

		if ( '' !== $line && 0 !== strpos( $line, '//' ) ) {
			$lines[] = $line;
		}
	}

	return implode( "\n", $lines );
}

/* Una <script>…</script> o <style>…</style> già completa → versione compressa (JSON-LD compatto, CSS minificato, JS ripulito). */
function starter_minify_block( $block ) {
	if ( ! preg_match( '~^(<(script|style)\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>)(.*?)(</\2>)$~is', $block, $m ) ) {
		return $block;
	}

	$open = preg_replace( '~\s+(/?>)$~', '$1', preg_replace_callback( '~("[^"]*"|\'[^\']*\')|\s+~', function ( $x ) {
		return isset( $x[1] ) ? $x[1] : ' ';
	}, $m[1] ) );
	$body = $m[3];

	if ( '' === trim( $body ) ) {
		return $open . $m[4];
	}

	if ( 'style' === strtolower( $m[2] ) ) {
		$body = starter_minify_css( $body );
	} elseif ( preg_match( '/\stype\s*=\s*["\']?(?:application\/(?:ld\+)?json|importmap|speculationrules)/i', $m[1] ) ) {
		$data = json_decode( $body, true );
		$body = null === $data ? trim( $body ) : wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	} elseif ( ! preg_match( '/\stype\s*=\s*["\']?+(?!text\/javascript|application\/javascript|module)/i', $m[1] ) ) {
		$body = starter_minify_inline_js( $body );
	} else {
		$body = trim( $body ); // Modelli (text/template…): contenuto intatto.
	}

	return $open . $body . $m[4];
}

/* Tag che non mostrano nulla o vanno a capo da soli: gli spazi e gli a capo accanto a loro non servono. */
function starter_html_block_tag( $tag ) {
	static $set = null;

	if ( null === $set ) {
		$set = array_flip( explode( ' ', '!doctype html head body title meta link base script style noscript template div p section article aside header footer main nav ul ol dl dt dd h1 h2 h3 h4 h5 h6 form table thead tbody tfoot tr caption fieldset legend figure figcaption blockquote address details summary hr br select optgroup pre iframe' ) );
	}

	return isset( $set[ $tag ] );
}

/**
 * HTML: toglie commenti, a capo e spazi doppi, e gli spazi accanto ai tag di blocco (div, p, section…). Non tocca <pre> e <textarea>,
 * lascia uno spazio tra elementi di testo (a, span, li…) e comprime <style>, JSON-LD e, con cautela, gli script inline.
 */
function starter_minify_html( $html ) {
	$keep = array();

	// 0. Codici e script scritti dall'utente (Opzioni → Codici e script): passano identici, senza compressione.
	$html = preg_replace_callback(
		'~<!--starter-code-->(.*?)<!--/starter-code-->~s',
		function ( $m ) use ( &$keep ) {
			$keep[] = $m[1];

			return "\x03" . ( count( $keep ) - 1 ) . "\x04";
		},
		$html
	);

	// 1. Parti da non toccare o da trattare a parte, sostituite da segnaposto (\x03 = invisibile, \x01 = contenuto da lasciare com'è).
	$html = preg_replace_callback(
		'~<(pre|textarea)\b.*?</\1>~is',
		function ( $m ) use ( &$keep ) {
			$keep[] = $m[0];

			return "\x01" . ( count( $keep ) - 1 ) . "\x02";
		},
		$html
	);
	$html = preg_replace_callback(
		'~<script\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>.*?</script>|<style\b[^>]*>.*?</style>~is',
		function ( $m ) use ( &$keep ) {
			$keep[] = starter_minify_block( $m[0] );

			return "\x03" . ( count( $keep ) - 1 ) . "\x04";
		},
		$html
	);

	$html = preg_replace_callback(
		'~<!--(?!\s*\[if|\s*<!\[endif).*?-->~s',
		function () {
			return '';
		},
		$html
	);

	// 2. Tag e testo a turno.
	$parts = preg_split( '~(<(?:[^>"\']|"[^"]*"|\'[^\']*\')*>)~', $html, -1, PREG_SPLIT_DELIM_CAPTURE );
	$out   = array();
	$total = count( $parts );

	for ( $i = 0; $i < $total; $i++ ) {
		$part = $parts[ $i ];

		if ( $i % 2 ) { // Tag.
			$part  = preg_replace_callback( '~("[^"]*"|\'[^\']*\')|\s+~', function ( $m ) {
				return isset( $m[1] ) ? $m[1] : ' ';
			}, $part );
			$part  = preg_replace( '~\s+(/?>)$~', '$1', $part );
			$out[] = $part;
			continue;
		}

		// Testo: spazi doppi → uno; nessuno spazio accanto a tag di blocco o segnaposto invisibili.
		$text = preg_replace( '/\s+/', ' ', $part );
		$prev = $i > 0 && preg_match( '~^</?([a-z0-9!]+)~i', $parts[ $i - 1 ], $pm ) ? strtolower( $pm[1] ) : '';
		$next = $i < $total - 1 && preg_match( '~^</?([a-z0-9!]+)~i', $parts[ $i + 1 ], $nm ) ? strtolower( $nm[1] ) : '';

		if ( '' === $prev || starter_html_block_tag( $prev ) ) {
			$text = ltrim( $text );
		}

		if ( '' === $next || starter_html_block_tag( $next ) ) {
			$text = rtrim( $text );
		}

		$text = preg_replace( '/ ?(\x03\d+\x04) ?/', '$1', $text );

		if ( '' !== $text ) {
			$out[] = $text;
		}
	}

	$html = implode( '', $out );

	return preg_replace_callback(
		'/[\x01\x03](\d+)[\x02\x04]/',
		function ( $m ) use ( $keep ) {
			return $keep[ (int) $m[1] ];
		},
		$html
	);
}

/* ============================================================ Unione di CSS e JS */

/* Percorso su disco di un CSS/JS del sito dato il suo URL; '' se è esterno, dinamico o illeggibile. */
function starter_local_asset_path( $url ) {
	$url = (string) $url;

	if ( 0 === strpos( $url, '/' ) && 0 !== strpos( $url, '//' ) ) {
		$home = wp_parse_url( home_url() );
		$url  = ( $home['scheme'] ?? 'http' ) . '://' . ( $home['host'] ?? '' ) . ( isset( $home['port'] ) ? ':' . $home['port'] : '' ) . $url;
	}

	$bare = preg_replace( '#^https?:#i', '', (string) strtok( $url, '?#' ) );

	if ( ! preg_match( '/\.(?:css|js)$/i', $bare ) ) {
		return '';
	}

	$bases = array(
		content_url()     => WP_CONTENT_DIR,
		includes_url()    => ABSPATH . WPINC,
		site_url()        => untrailingslashit( ABSPATH ),
	);

	foreach ( $bases as $base_url => $base_dir ) {
		$base = preg_replace( '#^https?:#i', '', untrailingslashit( $base_url ) );

		if ( 0 === strpos( $bare, $base . '/' ) ) {
			$path = $base_dir . rawurldecode( substr( $bare, strlen( $base ) ) );

			return false === strpos( $path, '..' ) && is_readable( $path ) ? $path : '';
		}
	}

	return '';
}

/* Vero se un file si può unire senza rischi (CSS senza @import, JS che non cerca da solo il proprio indirizzo). Il verdetto si ricorda finché il file non cambia. */
function starter_asset_mergeable( $path ) {
	static $index = null;

	if ( null === $index ) {
		$index = get_option( 'starter_bundle_index', array() );
		$index = is_array( $index ) ? $index : array();
	}

	$key = md5( $path . '|' . filemtime( $path ) . '|' . filesize( $path ) );

	if ( isset( $index[ $key ] ) ) {
		return (bool) $index[ $key ];
	}

	$code = (string) file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

	if ( preg_match( '/\.css$/i', $path ) ) {
		$ok = false === stripos( $code, '@import' );
	} else {
		$ok = false === strpos( $code, 'document.currentScript' ) && ! preg_match( '/\bimport\s*\(|^\s*(?:import|export)\s/m', $code );
	}

	$index[ $key ] = $ok;
	update_option( 'starter_bundle_index', array_slice( $index, -400, null, true ), false );

	return $ok;
}

/* Riscrive gli url() relativi di un CSS in indirizzi dalla radice del sito, così il file unito (che sta in un'altra cartella) trova ancora font e immagini. */
function starter_css_rebase( $css, $url ) {
	$base = dirname( preg_replace( '#^(?:https?:)?//[^/]+#i', '', (string) strtok( $url, '?#' ) ) );

	return preg_replace_callback(
		'~url\(\s*(["\']?)([^)"\']+)\1\s*\)~i',
		function ( $m ) use ( $base ) {
			$u = trim( $m[2] );

			if ( '' === $u || preg_match( '~^(?:data:|https?:|//|/|#)~i', $u ) ) {
				return $m[0];
			}

			$stack = array();

			foreach ( explode( '/', $base . '/' . $u ) as $piece ) {
				if ( '' === $piece || '.' === $piece ) {
					continue;
				}

				'..' === $piece ? array_pop( $stack ) : $stack[] = $piece;
			}

			return 'url(' . $m[1] . '/' . implode( '/', $stack ) . $m[1] . ')';
		},
		$css
	);
}

/* Crea (o riusa) il file unito di una serie di CSS o JS. Restituisce l'indirizzo, oppure '' se non riesce. */
function starter_build_bundle( $type, array $paths, array $urls ) {
	$dir = starter_cache_dir();
	$sig = '';

	foreach ( $paths as $path ) {
		$sig .= $path . '|' . filemtime( $path ) . '|' . filesize( $path ) . ';';
	}

	$name = substr( md5( $sig . STARTER_THEME_VERSION . 'b1' ), 0, 20 ) . '.' . $type;
	$file = $dir['path'] . '/' . $name;

	if ( is_file( $file ) ) {
		if ( time() - filemtime( $file ) > DAY_IN_SECONDS ) {
			@touch( $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged -- segna il file come usato di recente (vedi pulizia).
		}

		return $dir['url'] . '/' . $name;
	}

	if ( ! wp_mkdir_p( $dir['path'] ) ) {
		return '';
	}

	$out = '';

	foreach ( $paths as $i => $path ) {
		$code = (string) file_get_contents( $path ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents

		if ( 'css' === $type ) {
			$code = preg_replace( '/^\xEF\xBB\xBF|@charset\s+"[^"]*"\s*;/i', '', $code );
			$code = starter_minify_css( starter_css_rebase( $code, $urls[ $i ] ) );
			$out .= $code . "\n";
		} else {
			$code = preg_replace( '~^//[#@]\s*sourceMappingURL=.*$~m', '', $code );
			$out .= rtrim( $code ) . "\n;\n";
		}
	}

	$tmp = $file . '.' . wp_generate_password( 6, false ) . '.tmp';

	if ( false === file_put_contents( $tmp, $out ) || ! rename( $tmp, $file ) ) { // phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents, WordPress.WP.AlternativeFunctions.rename_rename
		@unlink( $tmp ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.unlink_unlink

		return '';
	}

	starter_cache_prune();

	return $dir['url'] . '/' . $name;
}

/* Una volta al giorno toglie i file uniti non usati da più di 45 giorni (e gli avanzi di scritture interrotte). */
function starter_cache_prune() {
	if ( get_transient( 'starter_cache_pruned' ) ) {
		return;
	}

	set_transient( 'starter_cache_pruned', 1, DAY_IN_SECONDS );

	$dir = starter_cache_dir();

	foreach ( (array) glob( $dir['path'] . '/*' ) as $file ) {
		if ( is_file( $file ) && time() - filemtime( $file ) > 45 * DAY_IN_SECONDS ) {
			@unlink( $file ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.unlink_unlink
		}
	}
}

/* Svuota i file uniti (si ricreano alla prima visita). Restituisce quanti ne ha tolti. */
function starter_cache_clear() {
	$dir   = starter_cache_dir();
	$count = 0;

	foreach ( (array) glob( $dir['path'] . '/*' ) as $file ) {
		if ( is_file( $file ) && @unlink( $file ) ) { // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged, WordPress.WP.AlternativeFunctions.unlink_unlink
			++$count;
		}
	}

	delete_option( 'starter_bundle_index' );

	return $count;
}
add_action( 'customize_save_after', 'starter_cache_clear' );
add_action( 'after_switch_theme', 'starter_cache_clear' );

/**
 * Nell'HTML finale cerca serie di <link rel="stylesheet"> o <script src> locali uno di seguito all'altro (ordine di pagina invariato) e le
 * sostituisce con un solo file. I <script> inline "…-js-extra" (dati di wp_localize_script) passano prima del file unito; ogni altro
 * elemento in mezzo (script inline, stile inline, file esterni, file con attributi diversi) interrompe la serie.
 */
function starter_bundle_assets( $html ) {
	$pattern = '~<!--.*?-->|<(noscript|template|textarea|pre)\b.*?</\1>|<style\b[^>]*>.*?</style>|<script\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>.*?</script>|<link\b(?:[^>"\']|"[^"]*"|\'[^\']*\')*>~is';

	if ( ! preg_match_all( $pattern, $html, $found, PREG_OFFSET_CAPTURE ) ) {
		return $html;
	}

	$exclude = (array) apply_filters( 'starter_bundle_exclude', array() ); // Pezzi di indirizzo da non unire mai.
	$tokens  = array();

	foreach ( $found[0] as $hit ) {
		$text  = $hit[0];
		$token = array(
			'kind'  => 'other',
			'start' => $hit[1],
			'end'   => $hit[1] + strlen( $text ),
			'text'  => $text,
		);

		if ( 0 === stripos( $text, '<link' ) || 0 === stripos( $text, '<script' ) ) {
			$is_link = 0 === stripos( $text, '<link' );
			$p       = new WP_HTML_Tag_Processor( $text );

			if ( $p->next_tag( $is_link ? 'link' : 'script' ) ) {
				$names = array_map( 'strtolower', (array) $p->get_attribute_names_with_prefix( '' ) );
				$src   = (string) $p->get_attribute( $is_link ? 'href' : 'src' );

				if ( $is_link ) {
					$extra = array_diff( $names, array( 'rel', 'id', 'href', 'media', 'type' ) );

					if ( 'stylesheet' === strtolower( (string) $p->get_attribute( 'rel' ) ) && ! $extra && '' !== $src ) {
						$path = starter_local_asset_path( $src );

						if ( $path && starter_asset_mergeable( $path ) ) {
							$token = array_merge(
								$token,
								array(
									'kind' => 'css',
									'sig'  => (string) ( $p->get_attribute( 'media' ) ?: 'all' ),
									'path' => $path,
									'url'  => $src,
								)
							);
						}
					}
				} elseif ( '' !== $src ) {
					$type = strtolower( (string) $p->get_attribute( 'type' ) );
					$bad  = array_intersect( $names, array( 'integrity', 'nomodule', 'data-no-bundle' ) );
					$path = ! $bad && ( '' === $type || 'text/javascript' === $type || 'application/javascript' === $type ) ? starter_local_asset_path( $src ) : '';

					if ( $path && starter_asset_mergeable( $path ) ) {
						$sig = array(
							$p->get_attribute( 'defer' ) ? 'defer' : '',
							$p->get_attribute( 'async' ) ? 'async' : '',
							$type,
							(string) $p->get_attribute( 'crossorigin' ),
						);

						$token = array_merge(
							$token,
							array(
								'kind' => 'js',
								'sig'  => implode( '|', $sig ),
								'path' => $path,
								'url'  => $src,
							)
						);
					}
				} elseif ( preg_match( '/-js-extra$/', (string) $p->get_attribute( 'id' ) ) && ! $p->get_attribute( 'type' ) ) {
					$token['kind'] = 'extra';
				}
			}

			foreach ( $exclude as $needle ) {
				if ( 'other' !== $token['kind'] && $needle && isset( $token['url'] ) && false !== strpos( $token['url'], $needle ) ) {
					$token['kind'] = 'other';
				}
			}
		}

		$tokens[] = $token;
	}

	$replace = array();
	$run     = null;
	$tail    = array(); // Dati "-js-extra" letti dopo l'ultimo file della serie aperta: entrano solo se segue un altro file compatibile.
	$prev    = null;
	$counter = 0;

	$flush = function () use ( &$run, &$replace, &$counter ) {
		if ( $run && count( $run['srcs'] ) >= 2 ) {
			$paths = array();
			$urls  = array();

			foreach ( $run['srcs'] as $src ) {
				$paths[] = $src['path'];
				$urls[]  = $src['url'];
			}

			$bundle = starter_build_bundle( 'css' === $run['type'] ? 'css' : 'js', $paths, $urls );

			if ( $bundle ) {
				++$counter;
				$first = new WP_HTML_Tag_Processor( $run['srcs'][0]['text'] );
				$first->next_tag();
				$first->set_attribute( 'css' === $run['type'] ? 'href' : 'src', $bundle );
				$first->set_attribute( 'id', 'starter-bundle-' . $counter . ( 'css' === $run['type'] ? '-css' : '-js' ) );

				$extras = '';

				foreach ( $run['extras'] as $extra ) {
					$extras .= $extra['text'] . "\n";
				}

				$replace[] = array( $run['start'], $run['end'], $extras . $first->get_updated_html() . "\n" );
			}
		}

		$run = null;
	};

	foreach ( $tokens as $token ) {
		$adjacent = null !== $prev && '' === trim( substr( $html, $prev, $token['start'] - $prev ) );
		$prev     = $token['end'];

		if ( ! $adjacent ) {
			$flush();
			$tail = array();
		}

		if ( 'extra' === $token['kind'] ) {
			$tail[] = $token;
			continue;
		}

		if ( 'css' === $token['kind'] || 'js' === $token['kind'] ) {
			if ( $run && $run['type'] === $token['kind'] && $run['sig'] === $token['sig'] ) {
				$run['extras'] = array_merge( $run['extras'], $tail );
			} else {
				$flush();
				$lead = 'js' === $token['kind'] ? $tail : array();
				$run  = array(
					'type'   => $token['kind'],
					'sig'    => $token['sig'],
					'srcs'   => array(),
					'extras' => $lead,
					'start'  => $lead ? $lead[0]['start'] : $token['start'],
					'end'    => $token['end'],
				);
			}

			$tail          = array();
			$run['srcs'][] = $token;
			$run['end']    = $token['end'];
			continue;
		}

		$flush();
		$tail = array();
	}

	$flush();

	foreach ( array_reverse( $replace ) as $item ) {
		$html = substr_replace( $html, $item[2], $item[0], $item[1] - $item[0] );
	}

	return $html;
}

/* ============================================================ Pagina → buffer → pagina ottimizzata */

/* Vero se la richiesta corrente è una normale pagina HTML del sito da ottimizzare. */
function starter_optimize_applies() {
	if ( is_admin() || wp_doing_ajax() || ( defined( 'REST_REQUEST' ) && REST_REQUEST ) || ( defined( 'DOING_CRON' ) && DOING_CRON ) || wp_is_json_request() || is_feed() || is_robots() || is_embed() || is_customize_preview() ) {
		return false;
	}

	// Per chi gestisce il sito: ?starter-raw=1 mostra la pagina senza compressione (utile per controllare il codice).
	return ! ( isset( $_GET['starter-raw'] ) && current_user_can( 'manage_options' ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

/* Applica unione CSS/JS e compressione HTML (se accese) alla pagina finale; in caso di errore restituisce la pagina com'era */
function starter_optimize_output( $html ) {
	if ( ! is_string( $html ) || strlen( $html ) < 200 || false === stripos( $html, '<html' ) ) {
		return $html;
	}

	try {
		$out = $html;

		if ( get_theme_mod( 'starter_perf_bundle', false ) ) {
			$res = starter_bundle_assets( $out );
			$out = is_string( $res ) && '' !== $res ? $res : $out;
		}

		if ( get_theme_mod( 'starter_perf_minify_html', false ) ) {
			$res = starter_minify_html( $out );
			$out = is_string( $res ) && '' !== $res ? $res : $out;
		}

		return $out;
	} catch ( Throwable $e ) {
		return $html;
	}
}

add_action(
	'template_redirect',
	function () {
		if ( ( get_theme_mod( 'starter_perf_bundle', false ) || get_theme_mod( 'starter_perf_minify_html', false ) ) && starter_optimize_applies() ) {
			ob_start( 'starter_optimize_output' );
		}
	},
	1
);
