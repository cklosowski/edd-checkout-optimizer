<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }

class EDD_Optimize_Checkout_Detector {

	public function __construct() {
		$this->hooks();
	}

	private function hooks() {
		add_action( 'edd_optimize_checkout_detector', array( $this, 'enable_detection' ) );
		add_action( 'wp_print_scripts', array( $this, 'detect_scripts' ), PHP_INT_MAX );
		add_action( 'wp_print_styles', array( $this, 'detect_styles' ), PHP_INT_MAX );
	}

	public function enable_detection() {
		define( 'EDD_OPTIMIZE_CHECKOUT_DETECTION', true );
	}

	public function detect_scripts() {
		if ( ! defined( 'EDD_OPTIMIZE_CHECKOUT_DETECTION' ) || ! EDD_OPTIMIZE_CHECKOUT_DETECTION ) {
			return;
		}

		if ( ! edd_is_checkout() ) {
			return;
		}

		global $wp_scripts;
		$existing_settings = get_option( 'edd_oc_script_settings', array() );
		$new_settings      = array();

		foreach ( $wp_scripts->queue as $script_handle ) {
			$new_settings[ $script_handle ] = isset( $existing_settings[ $script_handle ] ) ? $existing_settings[ $script_handle ] : 0;
		}

		update_option( 'edd_oc_script_settings', $new_settings );
	}

	public function detect_styles() {
		if ( ! defined( 'EDD_OPTIMIZE_CHECKOUT_DETECTION' ) || ! EDD_OPTIMIZE_CHECKOUT_DETECTION ) {
			return;
		}

		if ( ! edd_is_checkout() ) {
			return;
		}

		global $wp_styles;
		$existing_settings = get_option( 'edd_oc_style_settings', array() );
		$new_settings      = array();

		foreach ( $wp_styles->queue as $script_handle ) {
			$new_settings[ $script_handle ] = isset( $existing_settings[ $script_handle ] ) ? $existing_settings[ $script_handle ] : 0;
		}

		update_option( 'edd_oc_style_settings', $new_settings );
	}
}
