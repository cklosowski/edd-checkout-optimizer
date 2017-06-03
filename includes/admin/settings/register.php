<?php
/**
 * Settings
 *
 * @package     EDD\OptimizeCheckout\Admin\Settings\Register
 * @since       1.0
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Add settings section
 *
 * @since       1.0
 * @param       array $sections The existing extensions sections
 * @return      array The modified extensions settings
 */
function edd_optimize_checkout_add_settings_section( $sections ) {
	$sections['optimize-checkout'] = __( 'Optimize Checkout', 'edd-optimize-checkout' );

	return $sections;
}
add_filter( 'edd_settings_sections_extensions', 'edd_optimize_checkout_add_settings_section' );


/**
 * Add extension settings
 *
 * @since       1.0
 * @param       array $settings The existing plugin settings
 * @return      array The modified plugin settings
 */
function edd_optimize_checkout_add_settings( $settings ) {
	if( EDD_VERSION >= '2.5' ) {
		$new_settings = array(
			'optimize-checkout' => apply_filters( 'edd_optimize_checkout_settings', array(
				array(
					'id'   => 'edd_optimize_checkout_scripts',
					'name' => __( 'Optimize Checkout Settings', 'edd-optimize-checkout' ),
					'desc' => __( 'Configure the settings for EDD Optimize Checkout', 'edd-optimize-checkout' ),
					'type' => 'header',
				),
				array(
					'id'   => 'optimize_checkout_detect',
					'name' => '',
					'type' => 'hook',
				),
				array(
					'id'   => 'optimize_checkout_scripts_settings',
					'name' => __( 'Dequeue Scripts', 'edd-optimize-checkout' ),
					'type' => 'hook',
				),
				array(
					'id'   => 'optimize_checkout_styles_settings',
					'name' => __( 'Dequeue Styles', 'edd-optimize-checkout' ),
					'type' => 'hook',
				),
			) )
		);

		$settings = array_merge( $settings, $new_settings );
	}

	return $settings;
}
add_filter( 'edd_settings_extensions', 'edd_optimize_checkout_add_settings' );

function edd_oc_display_detect_setting() {
	$checkout_url = add_query_arg( array( 'edd_action' => 'optimize_checkout_detector' ), edd_get_checkout_uri() );
	wp_remote_get( $checkout_url );
}
add_action( 'edd_optimize_checkout_detect', 'edd_oc_display_detect_setting' );

function edd_oc_display_scripts_setting() {
	$scripts = get_option( 'edd_oc_script_settings', array() );
	foreach ( $scripts as $script => $disabled ) {
		?>
		<p>
		<input type="hidden" name="oc_script_settings[<?php echo $script; ?>]" value="-1" />
		<?php
		echo EDD()->html->checkbox( array(
			'id'       => $script,
			'name'     => 'oc_script_settings[' . $script . ']',
			'current'  => $disabled,
			'class'    => 'edd-checkbox',
		) );
		?>
		<label for="oc_script_settings[<?php echo $script; ?>]"><?php echo $script; ?></label>
		</p>
		<?php
	}
}
add_action( 'edd_optimize_checkout_scripts_settings', 'edd_oc_display_scripts_setting' );

function edd_oc_display_styles_setting() {
	$styles = get_option( 'edd_oc_style_settings', array() );
	foreach ( $styles as $style => $disabled ) {
		?>
		<p>
		<input type="hidden" name="oc_style_settings[<?php echo $style; ?>]" value="-1" />
		<?php
		echo EDD()->html->checkbox( array(
			'id'       => $style,
			'name'     => 'oc_style_settings[' . $style . ']',
			'current'  => $disabled,
			'class'    => 'edd-checkbox',
		) );
		?>
		<label for="oc_style_settings[<?php echo $style; ?>]"><?php echo $style; ?></label>
		</p>
		<?php
	}
}
add_action( 'edd_optimize_checkout_styles_settings', 'edd_oc_display_styles_setting' );

function edd_oc_save_settings( $input ) {

	if ( isset( $_POST['oc_script_settings'] ) ) {
		$new_scripts = array();
		foreach( $_POST['oc_script_settings']  as $script => $disabled ) {
			$new_scripts[ $script ] = '-1' === $disabled ? 0 : 1;
		}

		update_option( 'edd_oc_script_settings', $new_scripts );
	}

	if ( isset( $_POST['oc_style_settings'] ) ) {
		$new_styles = array();
		foreach( $_POST['oc_style_settings']  as $style => $disabled ) {
			$new_styles[ $style ] = '-1' === $disabled ? 0 : 1;
		}

		update_option( 'edd_oc_style_settings', $new_styles );
	}

	return $input;
}
add_filter( 'edd_settings_extensions-optimize-checkout_sanitize', 'edd_oc_save_settings', 10,1 );
add_filter( 'edd_settings_extensions-main_sanitize', 'edd_oc_save_settings', 10,1 );