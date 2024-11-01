<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Price_Quote
 * @subpackage Woocommerce_Price_Quote/includes
 */

if ( ! class_exists( 'Woocommerce_Price_Quote_i18n' ) ) :

	/**
	 * Define the internationalization functionality.
	 *
	 * Loads and defines the internationalization files for this plugin
	 * so that it is ready for translation.
	 *
	 * @since      1.0.0
	 * @package    Woocommerce_Price_Quote
	 * @subpackage Woocommerce_Price_Quote/includes
	 * @author     wbcomdesigns <admin@wbcomdesigns.com>
	 */
	class Woocommerce_Price_Quote_i18n {


		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain() {

			load_plugin_textdomain(
				'woocommerce-price-quote',
				false,
				dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
			);

		}

	}

endif;
