<?php
/**
 * Tipo di campo ACF "Icona" (libreria Starter): un pulsante apre una finestra con tutte le icone, con ricerca.
 * Il valore salvato è il nome dell'icona (es. "shield-check", "brand-instagram"), lo stesso che legge starter_icon().
 * Chi usava il vecchio campo "Selezione" con poche icone non deve cambiare nulla: i valori già salvati restano validi.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'acf/include_field_types',
	function () {
		if ( ! class_exists( 'acf_field' ) || class_exists( 'Starter_ACF_Field_Icon' ) ) {
			return;
		}

		class Starter_ACF_Field_Icon extends acf_field {

			/* Nome, etichetta e categoria del campo */
			public function initialize() {
				$this->name     = 'starter_icon';
				$this->label    = __( 'Icona (libreria Starter)', 'slug-theme' );
				$this->category = 'choice';
				$this->defaults = array(
					'allow_null'    => 0,
					'default_value' => '',
				);
			}

			/* Impostazioni del campo: "nessuna icona" consentita e icona predefinita */
			public function render_field_settings( $field ) {
				acf_render_field_setting(
					$field,
					array(
						'label' => __( 'Consenti "nessuna icona"', 'slug-theme' ),
						'name'  => 'allow_null',
						'type'  => 'true_false',
						'ui'    => 1,
					)
				);
				acf_render_field_setting(
					$field,
					array(
						'label' => __( 'Icona predefinita', 'slug-theme' ),
						'name'  => 'default_value',
						'type'  => 'text',
					)
				);
			}

			/* Disegna il campo: anteprima, nome e pulsanti Scegli/Rimuovi */
			public function render_field( $field ) {
				$value = (string) $field['value'];
				?>
				<div class="starter-icon-field" data-allow-null="<?php echo $field['allow_null'] ? '1' : '0'; ?>">
					<input type="hidden" class="starter-icon-field__input" name="<?php echo esc_attr( $field['name'] ); ?>" value="<?php echo esc_attr( $value ); ?>">
					<button type="button" class="button starter-icon-field__open">
						<span class="starter-icon-field__preview"><?php echo starter_icon( $value, 24, 1.75 ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<span class="starter-icon-field__name"><?php echo '' !== $value ? esc_html( $value ) : esc_html__( 'Scegli un\'icona', 'slug-theme' ); ?></span>
					</button>
					<?php if ( $field['allow_null'] ) : ?>
						<button type="button" class="button-link starter-icon-field__clear"<?php echo '' === $value ? ' hidden' : ''; ?>><?php esc_html_e( 'Rimuovi', 'slug-theme' ); ?></button>
					<?php endif; ?>
				</div>
				<?php
			}

			/* Errore se manca l'icona e il campo non può restare vuoto */
			public function validate_value( $valid, $value, $field, $input ) {
				if ( '' === (string) $value ) {
					return $field['allow_null'] ? $valid : __( 'Scegli un\'icona.', 'slug-theme' );
				}

				return starter_icon_exists( $value ) ? $valid : __( 'Icona non valida.', 'slug-theme' );
			}

			/* Carica stile e script del selettore icone */
			public function input_admin_enqueue_scripts() {
				$ver = wp_get_theme( get_template() )->get( 'Version' ); // Il padre: con un tema figlio attivo wp_get_theme() darebbe la versione del figlio.

				wp_enqueue_style( 'starter-icon-picker', get_template_directory_uri() . '/CSS/icon-picker.css', array(), $ver );
				wp_enqueue_script( 'starter-icon-picker', get_template_directory_uri() . '/JS/icon-picker.js', array(), $ver, true );

				// "Carica SVG": stessa regola dell'upload SVG del tema (solo chi può modificare le opzioni del tema).
				$can_svg = current_user_can( 'upload_files' ) && current_user_can( 'edit_theme_options' );

				if ( $can_svg ) {
					wp_enqueue_media();
				}
				wp_localize_script(
					'starter-icon-picker',
					'StarterIconPicker',
					array(
						'rest'    => esc_url_raw( rest_url( 'starter/v1/icons' ) ),
						'restSvg' => esc_url_raw( rest_url( 'starter/v1/icons/svg/' ) ),
						'canSvg'  => $can_svg,
						'nonce' => wp_create_nonce( 'wp_rest' ),
						'i18n'  => array(
							'title'    => __( 'Scegli un\'icona', 'slug-theme' ),
							'search'   => __( 'Cerca (es. casa, telefono, instagram…)', 'slug-theme' ),
							'all'      => __( 'Tutte', 'slug-theme' ),
							'lines'    => __( 'Icone', 'slug-theme' ),
							'brands'   => __( 'Loghi / social', 'slug-theme' ),
							'none'     => __( 'Nessuna icona', 'slug-theme' ),
							'empty'    => __( 'Nessuna icona trovata.', 'slug-theme' ),
							'loading'  => __( 'Caricamento…', 'slug-theme' ),
							'close'    => __( 'Chiudi', 'slug-theme' ),
							'mine'     => __( 'I miei SVG', 'slug-theme' ),
							'upload'   => __( 'Carica SVG', 'slug-theme' ),
							'uploadTitle' => __( 'Carica o scegli un SVG', 'slug-theme' ),
							'useSvg'   => __( 'Usa questo SVG', 'slug-theme' ),
							'choose'   => __( 'Scegli un\'icona', 'slug-theme' ),
							'count'    => __( 'icone', 'slug-theme' ),
						),
					)
				);
			}
		}

		acf_register_field_type( 'Starter_ACF_Field_Icon' );
	}
);
