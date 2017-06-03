<?php
/**
 * Plugin Name:     Easy Digital Downloads - Optimize Checkout
 * Plugin URI:      https://easydigitaldownloads.com/extension/optimize-checkout/
 * Description:     Allows site owners to optimize their checkout assets
 * Version:         1.0
 * Author:          Easy Digital Downloads, LLC
 * Author URI:      https://easydigitaldownloads.com
 * Text Domain:     edd-optimize-checkout
 *
 * @package         EDD\OptimizeCheckout
 * @author          Easy Digital Downloads, LLC <support@easydigitaldownloads.com>
 * @author          Chris Klosowski
 * @copyright       Copyright (c) 2013-2014, Easy Digital Downloads, LLC
 */


// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( ! class_exists( 'EDD_Optimize_Checkout' ) ) {

	/**
	 * Main EDD_Optimize_Checkout class
	 *
	 * @since       1.0.0
	 */
	class EDD_Optimize_Checkout {


		/**
		 * @var         EDD_Optimize_Checkout $instance The one true EDD_Optimize_Checkout
		 * @since       1.0.0
		 */
		private static $instance;

		public $detector;


		/**
		 * Get active instance
		 *
		 * @access      public
		 * @since       1.0.1
		 * @return      object self::$instance The one true EDD_Optimize_Checkout
		 */
		public static function instance() {
			if( ! self::$instance ) {
				self::$instance = new EDD_Optimize_Checkout();
				self::$instance->setup_constants();
				self::$instance->includes();
				self::$instance->load_textdomain();
				self::$instance->init();
			}

			return self::$instance;
		}


		/**
		 * Setup plugin constants
		 *
		 * @access      private
		 * @since       1.0.9
		 * @return      void
		 */
		private function setup_constants() {
			// Plugin version
			define( 'EDD_OPTIMIZE_CHECKOUT_VERSION', '1.0' );

			// Plugin path
			define( 'EDD_OPTIMIZE_CHECKOUT_DIR', plugin_dir_path( __FILE__ ) );

			// Plugin URL
			define( 'EDD_OPTIMIZE_CHECKOUT_URL', plugin_dir_url( __FILE__ ) );
		}


		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       1.0.9
		 * @return      void
		 */
		private function includes() {

			require_once EDD_OPTIMIZE_CHECKOUT_DIR . 'includes/enqueue-detector.php';
			require_once EDD_OPTIMIZE_CHECKOUT_DIR . 'includes/dequeuer.php';

			if( is_admin() ) {
				require_once EDD_OPTIMIZE_CHECKOUT_DIR . 'includes/admin/settings/register.php';
			}

		}

		private function init() {
			self::$instance->detector = new EDD_Optimize_Checkout_Detector();
		}

		/**
		 * Internationalization
		 *
		 * @access      public
		 * @since       1.0.0
		 * @return      void
		 */
		public function load_textdomain() {
			// Set filter for language directory
			$lang_dir = EDD_PURCHASE_LIMIT_DIR . '/languages/';
			$lang_dir = apply_filters( 'edd_optimize_checkout_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'edd-optimize-checkout' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'edd-optimize-checkout', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/edd-optimize-checkout/' . $mofile;

			if( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/edd-optimize-checkout/ folder
				load_textdomain( 'edd-optimize-checkout', $mofile_global );
			} elseif( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/edd-optimize-checkout/languages/ folder
				load_textdomain( 'edd-optimize-checkout', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'edd-optimize-checkout', false, $lang_dir );
			}
		}
	}
}


/**
 * The main function responsible for returning the one true EDD_Optimize_Checkout
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      \EDD_Optimize_Checkout The one true EDD_Optimize_Checkout
 */
function edd_optimize_checkout() {
	return EDD_Optimize_Checkout::instance();
}
add_action( 'plugins_loaded', 'edd_optimize_checkout', PHP_INT_MAX );
