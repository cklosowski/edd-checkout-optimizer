<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }
function edd_oc_dequeue_scripts() {
	if ( defined( 'EDD_OPTIMIZE_CHECKOUT_DETECTION' ) && EDD_OPTIMIZE_CHECKOUT_DETECTION ) {
		return;
	}

	if ( ! edd_is_checkout() ) {
		return;
	}

	$script_settings = get_option( 'edd_oc_script_settings' );
	if ( ! empty( $script_settings ) ) {
		foreach ( $script_settings as $handle => $disabled ) {
			if ( ! empty( $disabled ) ) {
				wp_dequeue_script( $handle );
			}
		}
	}

}
add_action( 'wp_print_scripts', 'edd_oc_dequeue_scripts', 1 );

function edd_oc_dequeue_styles() {
	if ( defined( 'EDD_OPTIMIZE_CHECKOUT_DETECTION' ) && EDD_OPTIMIZE_CHECKOUT_DETECTION ) {
		return;
	}

	if ( ! edd_is_checkout() ) {
		return;
	}

	$style_settings = get_option( 'edd_oc_style_settings' );
	if ( ! empty( $style_settings ) ) {
		foreach ( $style_settings as $handle => $disabled ) {
			if ( ! empty( $disabled ) ) {
				wp_dequeue_style( $handle );
			}
		}
	}

}
add_action( 'wp_print_styles', 'edd_oc_dequeue_styles', 1 );