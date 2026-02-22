<?php
/**
 * Plugin Name:       KDM Premium Vibrant Form
 * Description:       A premium dark-themed contact form block with Cloudflare Turnstile, honeypot, rate limiting, and wp_mail() support.
 * Version:           0.1.0
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            WordPress Telex
 * License:           GPLv2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       telex-kdm-premium-form
 *
 * @package TelexKdmPremiumForm
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 */
if ( ! function_exists( 'telex_kdm_premium_form_block_init' ) ) {
	function telex_kdm_premium_form_block_init() {
		register_block_type( __DIR__ . '/build/' );
	}
}
add_action( 'init', 'telex_kdm_premium_form_block_init' );

/**
 * Enqueue Google Fonts for the front end and editor.
 */
if ( ! function_exists( 'telex_kdm_premium_form_enqueue_fonts' ) ) {
	function telex_kdm_premium_form_enqueue_fonts() {
		wp_enqueue_style(
			'kdm-premium-form-fonts',
			'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@400;500;700&display=swap',
			array(),
			'1.0.0'
		);
	}
}
add_action( 'wp_enqueue_scripts', 'telex_kdm_premium_form_enqueue_fonts' );
add_action( 'enqueue_block_editor_assets', 'telex_kdm_premium_form_enqueue_fonts' );

/**
 * Enqueue Turnstile script on the front end if site key is set.
 */
if ( ! function_exists( 'telex_kdm_premium_form_enqueue_turnstile' ) ) {
	function telex_kdm_premium_form_enqueue_turnstile() {
		$site_key = get_option( 'kdm_turnstile_site_key', '' );
		if ( ! empty( $site_key ) ) {
			wp_enqueue_script(
				'cloudflare-turnstile',
				'https://challenges.cloudflare.com/turnstile/v0/api.js',
				array(),
				null,
				true
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'telex_kdm_premium_form_enqueue_turnstile' );

/**
 * Pass AJAX URL, nonce, turnstile site key, and minimum delay to the front end
 * using wp_add_inline_script for CSP compatibility.
 */
if ( ! function_exists( 'telex_kdm_premium_form_inline_script' ) ) {
	function telex_kdm_premium_form_inline_script() {
		$handle = 'telex-block-telex-kdm-premium-form-view-script';
		$data   = array(
			'ajaxUrl'          => admin_url( 'admin-ajax.php' ),
			'nonce'            => wp_create_nonce( 'kdm_form_nonce' ),
			'turnstileSiteKey' => get_option( 'kdm_turnstile_site_key', '' ),
			'minInputDelay'    => absint( get_option( 'kdm_min_input_delay', 3 ) ),
		);
		$json   = wp_json_encode( $data );
		wp_add_inline_script( $handle, 'var kdmFormData = ' . $json . ';', 'before' );
	}
}
add_action( 'wp_enqueue_scripts', 'telex_kdm_premium_form_inline_script' );

/**
 * Register the settings page.
 */
if ( ! function_exists( 'telex_kdm_premium_form_add_settings_page' ) ) {
	function telex_kdm_premium_form_add_settings_page() {
		add_options_page(
			__( 'KDM Form Settings', 'telex-kdm-premium-form' ),
			__( 'KDM Form Settings', 'telex-kdm-premium-form' ),
			'manage_options',
			'kdm-form-settings',
			'telex_kdm_premium_form_render_settings_page'
		);
	}
}
add_action( 'admin_menu', 'telex_kdm_premium_form_add_settings_page' );

/**
 * Register settings.
 */
if ( ! function_exists( 'telex_kdm_premium_form_register_settings' ) ) {
	function telex_kdm_premium_form_register_settings() {
		register_setting( 'kdm_form_settings_group', 'kdm_turnstile_site_key', array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		) );
		register_setting( 'kdm_form_settings_group', 'kdm_turnstile_secret_key', array(
			'type'              => 'string',
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		) );
		register_setting( 'kdm_form_settings_group', 'kdm_send_delay', array(
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'default'           => 30,
		) );
		register_setting( 'kdm_form_settings_group', 'kdm_min_input_delay', array(
			'type'              => 'integer',
			'sanitize_callback' => 'absint',
			'default'           => 3,
		) );
		register_setting( 'kdm_form_settings_group', 'kdm_enable_logging', array(
			'type'              => 'boolean',
			'sanitize_callback' => 'rest_sanitize_boolean',
			'default'           => false,
		) );
	}
}
add_action( 'admin_init', 'telex_kdm_premium_form_register_settings' );

/**
 * Render the settings page.
 */
if ( ! function_exists( 'telex_kdm_premium_form_render_settings_page' ) ) {
	function telex_kdm_premium_form_render_settings_page() {
		$ts_site_key     = get_option( 'kdm_turnstile_site_key', '' );
		$ts_secret       = get_option( 'kdm_turnstile_secret_key', '' );
		$send_delay      = get_option( 'kdm_send_delay', 30 );
		$min_input_delay = get_option( 'kdm_min_input_delay', 3 );
		$enable_logging  = get_option( 'kdm_enable_logging', false );
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'KDM Form Settings', 'telex-kdm-premium-form' ); ?></h1>
			<p class="description">
				<?php esc_html_e( 'Email delivery is handled by wp_mail(). If you have an SMTP plugin installed (e.g. WP Mail SMTP, Post SMTP, FluentSMTP), it will automatically route all form emails through your configured SMTP provider.', 'telex-kdm-premium-form' ); ?>
			</p>
			<form method="post" action="options.php">
				<?php settings_fields( 'kdm_form_settings_group' ); ?>
				<h2><?php esc_html_e( 'Bot Protection (Cloudflare Turnstile)', 'telex-kdm-premium-form' ); ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="kdm_turnstile_site_key"><?php esc_html_e( 'Turnstile Site Key', 'telex-kdm-premium-form' ); ?></label></th>
						<td><input type="text" id="kdm_turnstile_site_key" name="kdm_turnstile_site_key" value="<?php echo esc_attr( $ts_site_key ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="kdm_turnstile_secret_key"><?php esc_html_e( 'Turnstile Secret Key', 'telex-kdm-premium-form' ); ?></label></th>
						<td><input type="text" id="kdm_turnstile_secret_key" name="kdm_turnstile_secret_key" value="<?php echo esc_attr( $ts_secret ); ?>" class="regular-text" /></td>
					</tr>
				</table>
				<h2><?php esc_html_e( 'Spam Protection', 'telex-kdm-premium-form' ); ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="kdm_send_delay"><?php esc_html_e( 'Rate Limit (seconds)', 'telex-kdm-premium-form' ); ?></label></th>
						<td>
							<input type="number" id="kdm_send_delay" name="kdm_send_delay" value="<?php echo esc_attr( $send_delay ); ?>" class="small-text" min="0" step="1" />
							<p class="description"><?php esc_html_e( 'Minimum seconds between submissions from the same IP address. Set to 0 to disable.', 'telex-kdm-premium-form' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="kdm_min_input_delay"><?php esc_html_e( 'Minimum Input Delay (seconds)', 'telex-kdm-premium-form' ); ?></label></th>
						<td>
							<input type="number" id="kdm_min_input_delay" name="kdm_min_input_delay" value="<?php echo esc_attr( $min_input_delay ); ?>" class="small-text" min="0" step="1" />
							<p class="description"><?php esc_html_e( 'Submissions faster than this many seconds after page load are rejected as bot activity. Set to 0 to disable.', 'telex-kdm-premium-form' ); ?></p>
						</td>
					</tr>
				</table>
				<h2><?php esc_html_e( 'Logging', 'telex-kdm-premium-form' ); ?></h2>
				<table class="form-table">
					<tr>
						<th scope="row"><label for="kdm_enable_logging"><?php esc_html_e( 'Enable Submission Logging', 'telex-kdm-premium-form' ); ?></label></th>
						<td>
							<input type="checkbox" id="kdm_enable_logging" name="kdm_enable_logging" value="1" <?php checked( $enable_logging ); ?> />
							<p class="description"><?php esc_html_e( 'Log rejected and successful submissions to the PHP error log for monitoring and debugging. IP addresses are anonymized.', 'telex-kdm-premium-form' ); ?></p>
						</td>
					</tr>
				</table>
				<?php submit_button(); ?>
			</form>
		</div>
		<?php
	}
}

/**
 * Helper: Anonymize an IP address for logging (strips last octet for IPv4, last 80 bits for IPv6).
 */
if ( ! function_exists( 'telex_kdm_anonymize_ip' ) ) {
	function telex_kdm_anonymize_ip( $ip ) {
		if ( strpos( $ip, ':' ) !== false ) {
			// IPv6: zero out last 80 bits.
			return preg_replace( '/:[0-9a-fA-F]{0,4}:[0-9a-fA-F]{0,4}:[0-9a-fA-F]{0,4}:[0-9a-fA-F]{0,4}:[0-9a-fA-F]{0,4}$/', ':0:0:0:0:0', $ip );
		}
		// IPv4: zero out last octet.
		return preg_replace( '/\.\d+$/', '.0', $ip );
	}
}

/**
 * Helper: Log form events when logging is enabled.
 */
if ( ! function_exists( 'telex_kdm_log' ) ) {
	function telex_kdm_log( $type, $message, $ip = '' ) {
		if ( ! get_option( 'kdm_enable_logging', false ) ) {
			return;
		}
		$anon_ip = $ip ? telex_kdm_anonymize_ip( $ip ) : 'unknown';
		error_log( sprintf(
			'[KDM Form][%s] %s | IP: %s',
			strtoupper( $type ),
			$message,
			$anon_ip
		) );
	}
}

/**
 * Helper: Strip newlines and carriage returns to prevent email header injection.
 */
if ( ! function_exists( 'telex_kdm_sanitize_header_value' ) ) {
	function telex_kdm_sanitize_header_value( $value ) {
		return preg_replace( '/[\r\n]/', '', $value );
	}
}

/**
 * Helper: Hash an IP with a daily rotating salt for rate-limit transient keys.
 */
if ( ! function_exists( 'telex_kdm_hash_ip' ) ) {
	function telex_kdm_hash_ip( $ip ) {
		$salt = wp_salt( 'nonce' ) . gmdate( 'Y-m-d' );
		return hash( 'sha256', $ip . $salt );
	}
}

/**
 * Helper: Validate Origin/Referer against site URL.
 */
if ( ! function_exists( 'telex_kdm_validate_origin' ) ) {
	function telex_kdm_validate_origin() {
		$site_host = wp_parse_url( home_url(), PHP_URL_HOST );
		$origin    = '';

		if ( ! empty( $_SERVER['HTTP_ORIGIN'] ) ) {
			$origin = wp_parse_url( sanitize_url( wp_unslash( $_SERVER['HTTP_ORIGIN'] ) ), PHP_URL_HOST );
		} elseif ( ! empty( $_SERVER['HTTP_REFERER'] ) ) {
			$origin = wp_parse_url( sanitize_url( wp_unslash( $_SERVER['HTTP_REFERER'] ) ), PHP_URL_HOST );
		}

		if ( empty( $origin ) ) {
			return false;
		}

		return strtolower( $origin ) === strtolower( $site_host );
	}
}

/**
 * Helper: Extract block attributes from post content by form instance ID.
 */
if ( ! function_exists( 'telex_kdm_find_block_attributes' ) ) {
	function telex_kdm_find_block_attributes( $post_id, $form_id ) {
		$post = get_post( $post_id );
		if ( ! $post || empty( $post->post_content ) ) {
			return null;
		}

		$blocks = parse_blocks( $post->post_content );
		return telex_kdm_search_blocks( $blocks, $form_id );
	}
}

if ( ! function_exists( 'telex_kdm_search_blocks' ) ) {
	function telex_kdm_search_blocks( $blocks, $form_id ) {
		foreach ( $blocks as $block ) {
			if (
				'telex/block-telex-kdm-premium-form' === $block['blockName'] &&
				isset( $block['attrs']['formInstanceId'] ) &&
				$block['attrs']['formInstanceId'] === $form_id
			) {
				return $block['attrs'];
			}
			if ( ! empty( $block['innerBlocks'] ) ) {
				$found = telex_kdm_search_blocks( $block['innerBlocks'], $form_id );
				if ( $found ) {
					return $found;
				}
			}
		}
		return null;
	}
}

/**
 * Generate and store a one-time submission token in a short-lived transient.
 */
if ( ! function_exists( 'telex_kdm_generate_submit_token' ) ) {
	function telex_kdm_generate_submit_token() {
		$token = wp_generate_password( 32, false );
		set_transient( 'kdm_token_' . $token, '1', 600 ); // Valid for 10 minutes.
		return $token;
	}
}

/**
 * Verify and consume a one-time submission token.
 */
if ( ! function_exists( 'telex_kdm_verify_submit_token' ) ) {
	function telex_kdm_verify_submit_token( $token ) {
		if ( empty( $token ) ) {
			return false;
		}
		$key = 'kdm_token_' . sanitize_text_field( $token );
		if ( get_transient( $key ) ) {
			delete_transient( $key );
			return true;
		}
		return false;
	}
}

/**
 * Field length constants.
 */
if ( ! defined( 'KDM_MAX_NAME_LENGTH' ) ) {
	define( 'KDM_MAX_NAME_LENGTH', 100 );
}
if ( ! defined( 'KDM_MAX_EMAIL_LENGTH' ) ) {
	define( 'KDM_MAX_EMAIL_LENGTH', 254 );
}
if ( ! defined( 'KDM_MAX_PHONE_LENGTH' ) ) {
	define( 'KDM_MAX_PHONE_LENGTH', 30 );
}
if ( ! defined( 'KDM_MAX_MESSAGE_LENGTH' ) ) {
	define( 'KDM_MAX_MESSAGE_LENGTH', 5000 );
}

/**
 * AJAX handler for form submission.
 */
if ( ! function_exists( 'telex_kdm_premium_form_handle_submission' ) ) {
	function telex_kdm_premium_form_handle_submission() {
		$ip = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0' ) );

		// Origin / Referer validation.
		if ( ! telex_kdm_validate_origin() ) {
			telex_kdm_log( 'blocked', 'Origin/Referer validation failed', $ip );
			wp_send_json_error( array( 'message' => __( 'Invalid request origin. Please submit the form from the website.', 'telex-kdm-premium-form' ) ), 403 );
		}

		// Verify nonce.
		if ( ! check_ajax_referer( 'kdm_form_nonce', 'nonce', false ) ) {
			telex_kdm_log( 'blocked', 'Nonce verification failed', $ip );
			wp_send_json_error( array( 'message' => __( 'Security verification failed. Please refresh the page and try again.', 'telex-kdm-premium-form' ) ), 403 );
		}

		// Verify one-time submission token (double-submit prevention).
		$submit_token = isset( $_POST['_kdm_token'] ) ? sanitize_text_field( wp_unslash( $_POST['_kdm_token'] ) ) : '';
		if ( ! telex_kdm_verify_submit_token( $submit_token ) ) {
			telex_kdm_log( 'blocked', 'Duplicate or invalid submission token', $ip );
			wp_send_json_error( array( 'message' => __( 'This form has already been submitted. Please refresh the page to submit again.', 'telex-kdm-premium-form' ) ), 403 );
		}

		// Honeypot check — the hidden field must be empty.
		$honeypot = isset( $_POST['website_url'] ) ? sanitize_text_field( wp_unslash( $_POST['website_url'] ) ) : '';
		if ( ! empty( $honeypot ) ) {
			telex_kdm_log( 'spam', 'Honeypot field triggered', $ip );
			// Silently fail with a generic success to not tip off bots.
			wp_send_json_success( array( 'message' => __( 'Thank you! Your message has been sent successfully.', 'telex-kdm-premium-form' ) ) );
		}

		// Minimum input delay check — reject submissions that are too fast.
		$min_input_delay = absint( get_option( 'kdm_min_input_delay', 3 ) );
		if ( $min_input_delay > 0 ) {
			$form_loaded_at = isset( $_POST['_kdm_loaded'] ) ? absint( $_POST['_kdm_loaded'] ) : 0;
			$now            = time();
			if ( $form_loaded_at <= 0 || ( $now - $form_loaded_at ) < $min_input_delay ) {
				telex_kdm_log( 'spam', 'Minimum input delay not met (too fast)', $ip );
				// Silently fail — bots won't know they were caught.
				wp_send_json_success( array( 'message' => __( 'Thank you! Your message has been sent successfully.', 'telex-kdm-premium-form' ) ) );
			}
		}

		// Rate limiting by hashed IP.
		$send_delay = absint( get_option( 'kdm_send_delay', 30 ) );
		if ( $send_delay > 0 ) {
			$ip_hash       = telex_kdm_hash_ip( $ip );
			$transient_key = 'kdm_rate_' . substr( $ip_hash, 0, 40 );
			if ( get_transient( $transient_key ) ) {
				telex_kdm_log( 'rate_limit', 'Rate limit exceeded', $ip );
				wp_send_json_error( array(
					'message' => sprintf(
						/* translators: %d: number of seconds */
						__( 'Please wait %d seconds before submitting again.', 'telex-kdm-premium-form' ),
						$send_delay
					),
				), 429 );
			}
			set_transient( $transient_key, true, $send_delay );
		}

		// Turnstile verification.
		$turnstile_secret = get_option( 'kdm_turnstile_secret_key', '' );
		if ( ! empty( $turnstile_secret ) ) {
			$turnstile_token = sanitize_text_field( wp_unslash( $_POST['cf-turnstile-response'] ?? '' ) );
			if ( empty( $turnstile_token ) ) {
				telex_kdm_log( 'blocked', 'Turnstile token missing', $ip );
				wp_send_json_error( array( 'message' => __( 'Bot verification failed. Please complete the challenge.', 'telex-kdm-premium-form' ) ), 403 );
			}
			$verify_response = wp_remote_post( 'https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
				'body' => array(
					'secret'   => $turnstile_secret,
					'response' => $turnstile_token,
					'remoteip' => $ip,
				),
			) );
			if ( is_wp_error( $verify_response ) ) {
				telex_kdm_log( 'error', 'Turnstile API request failed: ' . $verify_response->get_error_message(), $ip );
				wp_send_json_error( array( 'message' => __( 'Verification service unavailable. Please try again.', 'telex-kdm-premium-form' ) ), 500 );
			}
			$verify_body = json_decode( wp_remote_retrieve_body( $verify_response ), true );
			if ( empty( $verify_body['success'] ) ) {
				telex_kdm_log( 'blocked', 'Turnstile verification failed', $ip );
				wp_send_json_error( array( 'message' => __( 'Bot verification failed.', 'telex-kdm-premium-form' ) ), 403 );
			}
		}

		// Sanitize form fields.
		$first_name = sanitize_text_field( wp_unslash( $_POST['first_name'] ?? '' ) );
		$last_name  = sanitize_text_field( wp_unslash( $_POST['last_name'] ?? '' ) );
		$email      = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
		$phone      = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
		$message    = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

		// Enforce length limits.
		if ( mb_strlen( $first_name ) > KDM_MAX_NAME_LENGTH ) {
			$first_name = mb_substr( $first_name, 0, KDM_MAX_NAME_LENGTH );
		}
		if ( mb_strlen( $last_name ) > KDM_MAX_NAME_LENGTH ) {
			$last_name = mb_substr( $last_name, 0, KDM_MAX_NAME_LENGTH );
		}
		if ( mb_strlen( $email ) > KDM_MAX_EMAIL_LENGTH ) {
			wp_send_json_error( array( 'message' => __( 'Email address is too long.', 'telex-kdm-premium-form' ) ), 400 );
		}
		if ( mb_strlen( $phone ) > KDM_MAX_PHONE_LENGTH ) {
			$phone = mb_substr( $phone, 0, KDM_MAX_PHONE_LENGTH );
		}
		if ( mb_strlen( $message ) > KDM_MAX_MESSAGE_LENGTH ) {
			wp_send_json_error( array( 'message' => sprintf(
				/* translators: %d: maximum character count */
				__( 'Message is too long. Maximum %d characters allowed.', 'telex-kdm-premium-form' ),
				KDM_MAX_MESSAGE_LENGTH
			) ), 400 );
		}

		// Validate required fields.
		if ( empty( $first_name ) || empty( $last_name ) || empty( $email ) || empty( $message ) ) {
			telex_kdm_log( 'validation', 'Missing required fields', $ip );
			wp_send_json_error( array( 'message' => __( 'Please fill in all required fields.', 'telex-kdm-premium-form' ) ), 400 );
		}

		if ( ! is_email( $email ) ) {
			telex_kdm_log( 'validation', 'Invalid email format', $ip );
			wp_send_json_error( array( 'message' => __( 'Please provide a valid email address.', 'telex-kdm-premium-form' ) ), 400 );
		}

		// Resolve notification email and success message from block attributes (server-side only).
		$to_email        = '';
		$success_message = '';
		$post_id         = isset( $_POST['_kdm_post_id'] ) ? absint( $_POST['_kdm_post_id'] ) : 0;
		$form_id         = isset( $_POST['_kdm_form_id'] ) ? sanitize_text_field( wp_unslash( $_POST['_kdm_form_id'] ) ) : '';

		if ( $post_id > 0 && ! empty( $form_id ) ) {
			$block_attrs = telex_kdm_find_block_attributes( $post_id, $form_id );
			if ( $block_attrs ) {
				$to_email        = ! empty( $block_attrs['notificationEmail'] ) ? sanitize_email( $block_attrs['notificationEmail'] ) : '';
				$success_message = ! empty( $block_attrs['successMessage'] ) ? sanitize_text_field( $block_attrs['successMessage'] ) : '';
			}
		}

		// Fallback to admin email if not set or invalid.
		if ( empty( $to_email ) || ! is_email( $to_email ) ) {
			$to_email = get_option( 'admin_email' );
		}

		if ( empty( $success_message ) ) {
			$success_message = __( 'Thank you! Your message has been sent successfully.', 'telex-kdm-premium-form' );
		}

		// Sanitize values used in email headers to prevent header injection.
		$safe_first_name = telex_kdm_sanitize_header_value( $first_name );
		$safe_last_name  = telex_kdm_sanitize_header_value( $last_name );
		$safe_email      = telex_kdm_sanitize_header_value( $email );

		// Build the email.
		$subject = sprintf(
			/* translators: %s: sender's full name */
			__( 'New Form Submission from %s', 'telex-kdm-premium-form' ),
			$safe_first_name . ' ' . $safe_last_name
		);

		$body  = sprintf( __( 'Name: %s', 'telex-kdm-premium-form' ), $first_name . ' ' . $last_name ) . "\n";
		$body .= sprintf( __( 'Email: %s', 'telex-kdm-premium-form' ), $email ) . "\n";
		if ( ! empty( $phone ) ) {
			$body .= sprintf( __( 'Phone: %s', 'telex-kdm-premium-form' ), $phone ) . "\n";
		}
		$body .= "\n" . sprintf( __( 'Message:%s%s', 'telex-kdm-premium-form' ), "\n", $message );

		$headers = array(
			'Content-Type: text/plain; charset=UTF-8',
			'Reply-To: ' . $safe_first_name . ' ' . $safe_last_name . ' <' . $safe_email . '>',
		);

		$sent = wp_mail( $to_email, $subject, $body, $headers );

		if ( $sent ) {
			telex_kdm_log( 'success', 'Form submitted successfully by ' . $safe_email, $ip );
			wp_send_json_success( array( 'message' => $success_message ) );
		} else {
			telex_kdm_log( 'error', 'wp_mail() failed for submission from ' . $safe_email, $ip );
			wp_send_json_error( array( 'message' => __( 'Failed to send the message. Please try again later.', 'telex-kdm-premium-form' ) ), 500 );
		}
	}
}
add_action( 'wp_ajax_kdm_form_submit', 'telex_kdm_premium_form_handle_submission' );
add_action( 'wp_ajax_nopriv_kdm_form_submit', 'telex_kdm_premium_form_handle_submission' );
