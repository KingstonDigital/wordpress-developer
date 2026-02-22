<?php
/**
 * Dynamic render callback for the KDM Premium Vibrant Form block.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 */

if ( ! function_exists( 'telex_kdm_typography_to_inline_style' ) ) {
	function telex_kdm_typography_to_inline_style( $typo ) {
		if ( empty( $typo ) || ! is_array( $typo ) ) {
			return '';
		}
		$map = array(
			'fontFamily'      => 'font-family',
			'fontSize'        => 'font-size',
			'fontWeight'      => 'font-weight',
			'fontStyle'       => 'font-style',
			'lineHeight'      => 'line-height',
			'letterSpacing'   => 'letter-spacing',
			'textTransform'   => 'text-transform',
			'textDecoration'  => 'text-decoration',
		);
		$parts = array();
		foreach ( $map as $attr_key => $css_prop ) {
			if ( ! empty( $typo[ $attr_key ] ) ) {
				$parts[] = $css_prop . ':' . esc_attr( $typo[ $attr_key ] );
			}
		}
		return implode( ';', $parts );
	}
}

$layout_type        = ! empty( $attributes['layoutType'] ) ? sanitize_text_field( $attributes['layoutType'] ) : 'compact';
$heading            = ! empty( $attributes['heading'] ) ? wp_kses_post( $attributes['heading'] ) : '';
$subheading         = ! empty( $attributes['subheading'] ) ? wp_kses_post( $attributes['subheading'] ) : '';
$notification_email = ! empty( $attributes['notificationEmail'] ) ? sanitize_email( $attributes['notificationEmail'] ) : '';
$success_message    = ! empty( $attributes['successMessage'] ) ? esc_attr( $attributes['successMessage'] ) : esc_attr__( 'Thank you! Your message has been sent successfully.', 'telex-kdm-premium-form' );
$form_instance_id   = ! empty( $attributes['formInstanceId'] ) ? sanitize_text_field( $attributes['formInstanceId'] ) : '';
$turnstile_site_key = get_option( 'kdm_turnstile_site_key', '' );

// Generate a one-time submission token for double-submit prevention.
$submit_token = '';
if ( function_exists( 'telex_kdm_generate_submit_token' ) ) {
	$submit_token = telex_kdm_generate_submit_token();
}

// Resolve the current post ID for server-side attribute lookup.
$current_post_id = 0;
if ( isset( $block ) && is_object( $block ) && ! empty( $block->context['postId'] ) ) {
	$current_post_id = absint( $block->context['postId'] );
}
if ( ! $current_post_id ) {
	$current_post_id = get_the_ID() ? absint( get_the_ID() ) : 0;
}

$label_first_name       = ! empty( $attributes['labelFirstName'] ) ? wp_kses_post( $attributes['labelFirstName'] ) : esc_html__( 'First Name', 'telex-kdm-premium-form' );
$label_last_name        = ! empty( $attributes['labelLastName'] ) ? wp_kses_post( $attributes['labelLastName'] ) : esc_html__( 'Last Name', 'telex-kdm-premium-form' );
$label_email            = ! empty( $attributes['labelEmail'] ) ? wp_kses_post( $attributes['labelEmail'] ) : esc_html__( 'Email', 'telex-kdm-premium-form' );
$label_phone            = ! empty( $attributes['labelPhone'] ) ? wp_kses_post( $attributes['labelPhone'] ) : esc_html__( 'Phone', 'telex-kdm-premium-form' );
$label_message          = ! empty( $attributes['labelMessage'] ) ? wp_kses_post( $attributes['labelMessage'] ) : esc_html__( 'Message', 'telex-kdm-premium-form' );
$placeholder_first_name = ! empty( $attributes['placeholderFirstName'] ) ? esc_attr( $attributes['placeholderFirstName'] ) : '';
$placeholder_last_name  = ! empty( $attributes['placeholderLastName'] ) ? esc_attr( $attributes['placeholderLastName'] ) : '';
$placeholder_email      = ! empty( $attributes['placeholderEmail'] ) ? esc_attr( $attributes['placeholderEmail'] ) : '';
$placeholder_phone      = ! empty( $attributes['placeholderPhone'] ) ? esc_attr( $attributes['placeholderPhone'] ) : '';
$placeholder_message    = ! empty( $attributes['placeholderMessage'] ) ? esc_attr( $attributes['placeholderMessage'] ) : '';
$button_text            = ! empty( $attributes['buttonText'] ) ? wp_kses_post( $attributes['buttonText'] ) : esc_html__( 'Send Message →', 'telex-kdm-premium-form' );

// Field length constants.
$max_name    = defined( 'KDM_MAX_NAME_LENGTH' ) ? KDM_MAX_NAME_LENGTH : 100;
$max_email   = defined( 'KDM_MAX_EMAIL_LENGTH' ) ? KDM_MAX_EMAIL_LENGTH : 254;
$max_phone   = defined( 'KDM_MAX_PHONE_LENGTH' ) ? KDM_MAX_PHONE_LENGTH : 30;
$max_message = defined( 'KDM_MAX_MESSAGE_LENGTH' ) ? KDM_MAX_MESSAGE_LENGTH : 5000;

// Typography styles.
$heading_style          = telex_kdm_typography_to_inline_style( $attributes['headingTypography'] ?? array() );
$subheading_style       = telex_kdm_typography_to_inline_style( $attributes['subheadingTypography'] ?? array() );
$label_fn_style         = telex_kdm_typography_to_inline_style( $attributes['labelFirstNameTypography'] ?? array() );
$label_ln_style         = telex_kdm_typography_to_inline_style( $attributes['labelLastNameTypography'] ?? array() );
$label_em_style         = telex_kdm_typography_to_inline_style( $attributes['labelEmailTypography'] ?? array() );
$label_ph_style         = telex_kdm_typography_to_inline_style( $attributes['labelPhoneTypography'] ?? array() );
$label_mg_style         = telex_kdm_typography_to_inline_style( $attributes['labelMessageTypography'] ?? array() );
$input_fn_style         = telex_kdm_typography_to_inline_style( $attributes['inputFirstNameTypography'] ?? array() );
$input_ln_style         = telex_kdm_typography_to_inline_style( $attributes['inputLastNameTypography'] ?? array() );
$input_em_style         = telex_kdm_typography_to_inline_style( $attributes['inputEmailTypography'] ?? array() );
$input_ph_style         = telex_kdm_typography_to_inline_style( $attributes['inputPhoneTypography'] ?? array() );
$input_mg_style         = telex_kdm_typography_to_inline_style( $attributes['inputMessageTypography'] ?? array() );
$button_style           = telex_kdm_typography_to_inline_style( $attributes['buttonTypography'] ?? array() );

$wrapper_attributes = get_block_wrapper_attributes( array(
	'class'                => 'kdm-lp-form-box kdm-layout-' . esc_attr( $layout_type ),
	'data-success-message' => $success_message,
) );

$uid_fn = esc_attr( wp_unique_id( 'kdm-fn-' ) );
$uid_ln = esc_attr( wp_unique_id( 'kdm-ln-' ) );
$uid_em = esc_attr( wp_unique_id( 'kdm-em-' ) );
$uid_ph = esc_attr( wp_unique_id( 'kdm-ph-' ) );
$uid_mg = esc_attr( wp_unique_id( 'kdm-mg-' ) );
?>
<div <?php echo $wrapper_attributes; ?>>
	<?php if ( ! empty( $heading ) ) : ?>
		<h3<?php echo $heading_style ? ' style="' . esc_attr( $heading_style ) . '"' : ''; ?>><?php echo $heading; ?></h3>
	<?php endif; ?>
	<?php if ( ! empty( $subheading ) ) : ?>
		<p class="kdm-form-subheading"<?php echo $subheading_style ? ' style="' . esc_attr( $subheading_style ) . '"' : ''; ?>><?php echo $subheading; ?></p>
	<?php endif; ?>
	<form class="kdm-ajax-form" novalidate>
		<input type="hidden" name="_kdm_post_id" value="<?php echo esc_attr( $current_post_id ); ?>" />
		<input type="hidden" name="_kdm_form_id" value="<?php echo esc_attr( $form_instance_id ); ?>" />
		<input type="hidden" name="_kdm_loaded" value="" />
		<input type="hidden" name="_kdm_token" value="<?php echo esc_attr( $submit_token ); ?>" />
		<div style="position:absolute;left:-9999px;top:-9999px;height:0;width:0;overflow:hidden;" aria-hidden="true" tabindex="-1">
			<label for="<?php echo esc_attr( wp_unique_id( 'kdm-hp-' ) ); ?>"><?php esc_html_e( 'Leave this field empty', 'telex-kdm-premium-form' ); ?></label>
			<input type="text" name="website_url" id="<?php echo esc_attr( wp_unique_id( 'kdm-hp-' ) ); ?>" value="" tabindex="-1" autocomplete="off" />
		</div>
		<div class="kdm-lp-form-row">
			<div class="kdm-lp-form-group">
				<label for="<?php echo $uid_fn; ?>"<?php echo $label_fn_style ? ' style="' . esc_attr( $label_fn_style ) . '"' : ''; ?>><?php echo $label_first_name; ?></label>
				<input type="text" name="first_name" id="<?php echo $uid_fn; ?>" placeholder="<?php echo $placeholder_first_name; ?>" required maxlength="<?php echo esc_attr( $max_name ); ?>"<?php echo $input_fn_style ? ' style="' . esc_attr( $input_fn_style ) . '"' : ''; ?> />
			</div>
			<div class="kdm-lp-form-group">
				<label for="<?php echo $uid_ln; ?>"<?php echo $label_ln_style ? ' style="' . esc_attr( $label_ln_style ) . '"' : ''; ?>><?php echo $label_last_name; ?></label>
				<input type="text" name="last_name" id="<?php echo $uid_ln; ?>" placeholder="<?php echo $placeholder_last_name; ?>" required maxlength="<?php echo esc_attr( $max_name ); ?>"<?php echo $input_ln_style ? ' style="' . esc_attr( $input_ln_style ) . '"' : ''; ?> />
			</div>
		</div>
		<div class="kdm-lp-form-row">
			<div class="kdm-lp-form-group">
				<label for="<?php echo $uid_em; ?>"<?php echo $label_em_style ? ' style="' . esc_attr( $label_em_style ) . '"' : ''; ?>><?php echo $label_email; ?></label>
				<input type="email" name="email" id="<?php echo $uid_em; ?>" placeholder="<?php echo $placeholder_email; ?>" required maxlength="<?php echo esc_attr( $max_email ); ?>"<?php echo $input_em_style ? ' style="' . esc_attr( $input_em_style ) . '"' : ''; ?> />
			</div>
			<div class="kdm-lp-form-group">
				<label for="<?php echo $uid_ph; ?>"<?php echo $label_ph_style ? ' style="' . esc_attr( $label_ph_style ) . '"' : ''; ?>><?php echo $label_phone; ?></label>
				<input type="tel" name="phone" id="<?php echo $uid_ph; ?>" placeholder="<?php echo $placeholder_phone; ?>" maxlength="<?php echo esc_attr( $max_phone ); ?>"<?php echo $input_ph_style ? ' style="' . esc_attr( $input_ph_style ) . '"' : ''; ?> />
			</div>
		</div>
		<div class="kdm-lp-form-group">
			<label for="<?php echo $uid_mg; ?>"<?php echo $label_mg_style ? ' style="' . esc_attr( $label_mg_style ) . '"' : ''; ?>><?php echo $label_message; ?></label>
			<textarea name="message" id="<?php echo $uid_mg; ?>" rows="4" placeholder="<?php echo $placeholder_message; ?>" required maxlength="<?php echo esc_attr( $max_message ); ?>"<?php echo $input_mg_style ? ' style="' . esc_attr( $input_mg_style ) . '"' : ''; ?>></textarea>
		</div>
		<?php if ( ! empty( $turnstile_site_key ) ) : ?>
			<div class="kdm-turnstile-wrapper" style="margin: 16px 0;">
				<div class="cf-turnstile" data-sitekey="<?php echo esc_attr( $turnstile_site_key ); ?>"></div>
			</div>
		<?php endif; ?>
		<button type="submit" class="kdm-lp-form-submit"<?php echo $button_style ? ' style="' . esc_attr( $button_style ) . '"' : ''; ?>><?php echo $button_text; ?></button>
		<div class="kdm-form-status" style="margin-top: 12px; display: none;" role="alert" aria-live="polite"></div>
	</form>
</div>
