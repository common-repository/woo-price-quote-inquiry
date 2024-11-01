<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wbcomdesigns.com/
 * @since             1.0.0
 * @package           Woocommerce_Price_Quote
 *
 * @wordpress-plugin
 * Plugin Name:       Woo Product Inquiry & Quote
 * Plugin URI:        https://wbcomdesigns.com/
 * Description:       With the Woo Product Inquiry & Quote plugin, your customers can request an estimate for a selection of products they're interested in. This plugin also enables you to hide prices and/or add a quote button, allowing your customers to request a quote directly from each product page.
 * Version:           1.3.3
 * Author:            Wbcom Designs
 * Author URI:        https://wbcomdesigns.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-price-quote
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
if ( ! defined( 'WCPQ_VERSION' ) ) {
	define( 'WCPQ_VERSION', '1.3.3' );
}
if ( ! defined( 'WCPQ_DIR' ) ) {
	define( 'WCPQ_DIR', dirname( __FILE__ ) );
}
if ( ! defined( 'WCPQ_PLUGIN_PATH' ) ) {
	define( 'WCPQ_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
}
if ( ! defined( 'WCPQ_PLUGIN_URL' ) ) {
	define( 'WCPQ_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}
if ( ! defined( 'WCPQ_PLUGIN_BASENAME' ) ) {
	define( 'WCPQ_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}
if ( ! defined( 'WCPQ_PLUGIN_FILE' ) ) {
	define( 'WCPQ_PLUGIN_FILE', __FILE__ );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woocommerce-price-quote-activator.php
 */
function activate_woocommerce_price_quote() {
	$wcpq_gen_stngs = get_option( 'wcpq_gen_stngs' );

	if ( function_exists( 'get_page_by_title' ) ) {
		$page_title = 'Request Quotes';
		$page       = get_page_by_title( $page_title );
		if ( empty( $page ) ) {
			$args                         = array(
				'post_type'    => 'page',
				'post_title'   => $page_title,
				'post_status'  => 'publish',
				'post_content' => '[products_to_quote]',
			);
			$pg_id                        = wp_insert_post( $args );
			$wcpq_gen_stngs['quote_page'] = ( isset( $wcpq_gen_stngs['quote_page'] ) && ! empty( $wcpq_gen_stngs['quote_page'] ) ) ? $wcpq_gen_stngs['quote_page'] : $pg_id;
		}

		$my_quote_page_title = 'Quoted Products';
		$quote_page          = get_page_by_title( $my_quote_page_title );
		if ( empty( $quote_page ) ) {
			$args                                = array(
				'post_type'    => 'page',
				'post_title'   => $my_quote_page_title,
				'post_status'  => 'publish',
				'post_content' => '[my_quoted_products]',
			);
			$quote_pg_id                         = wp_insert_post( $args );
			$wcpq_gen_stngs['quoted_prods_page'] = ( isset( $wcpq_gen_stngs['quoted_prods_page'] ) && ! empty( $wcpq_gen_stngs['quoted_prods_page'] ) ) ? $wcpq_gen_stngs['quoted_prods_page'] : $quote_pg_id;
		}

		$wcpq_gen_stngs['quote_mode'] = ( isset( $wcpq_gen_stngs['quote_mode'] ) && ! empty( $wcpq_gen_stngs['quote_mode'] ) ) ? $wcpq_gen_stngs['quote_mode'] : 'all-products';

		$wcpq_gen_stngs['click_action'] = ( isset( $wcpq_gen_stngs['click_action'] ) && ! empty( $wcpq_gen_stngs['click_action'] ) ) ? $wcpq_gen_stngs['click_action'] : 'link';

		update_option( 'wcpq_gen_stngs', $wcpq_gen_stngs );

	}
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-price-quote-activator.php';
	Woocommerce_Price_Quote_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woocommerce-price-quote-deactivator.php
 */
function deactivate_woocommerce_price_quote() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-price-quote-deactivator.php';
	Woocommerce_Price_Quote_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_woocommerce_price_quote' );
register_deactivation_hook( __FILE__, 'deactivate_woocommerce_price_quote' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-woocommerce-price-quote.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woocommerce_price_quote() {

	$plugin = new Woocommerce_Price_Quote();
	$plugin->run();

}
run_woocommerce_price_quote();

if ( ! function_exists( 'woo_price_quote_check_woocomerce' ) ) {
	add_action( 'admin_init', 'woo_price_quote_check_woocomerce' );
	/**
	 * Function check for woocommerce is installed and activate.
	 *
	 * @since    1.1.0
	 */
	function woo_price_quote_check_woocomerce() {
		$activated_plugins   = get_option( 'active_plugins' );
		$woo_price_quote_pro = 'woocommerce-price-quote-pro/woocommerce-price-quote-pro.php';
		if ( in_array( $woo_price_quote_pro, $activated_plugins ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
		} elseif ( ! class_exists( 'WooCommerce' ) ) {
			deactivate_plugins( plugin_basename( __FILE__ ) );
			add_action( 'admin_notices', 'woo_price_quote_admin_notice' );
		}

	}
}


if ( ! function_exists( 'woo_price_quote_admin_notice' ) ) {
	/**
	 * Admin notice if WooCommerce not found.
	 *
	 * @since    1.1.0
	 */
	function woo_price_quote_admin_notice() {

		$wooquotes_plugin    = esc_html__( 'Woo Product Inquiry & Quote', 'woocommerce-price-quote' );
		$woo_plugin          = esc_html__( 'WooCommerce', 'woocommerce-price-quote' );
		$action              = 'install-plugin';
		$slug                = 'woocommerce';
		$plugin_install_link = '<a href="' . wp_nonce_url(
			add_query_arg(
				array(
					'action' => $action,
					'plugin' => $slug,
				),
				admin_url( 'update.php' )
			),
			$action . '_' . $slug
		) . '">' . $woo_plugin . '</a>';
		echo '<div class="error"><p>';
		/* translators: %1$s: Woo Product Inquiry & Quote ;  %2$s: WooCommerce*/
		echo sprintf( esc_html__( '%1$s is ineffective now as it requires %2$s to be installed and active.', 'woocommerce-price-quote' ), '<strong>' . esc_html( $wooquotes_plugin ) . '</strong>', '<strong>' . wp_kses_post( $plugin_install_link ) . '</strong>' );
		echo '</p></div>';

	}
}


/**
 * Redirect to plugin settings page after activated.
 *
 * @param string $plugin Path to the plugin file relative to the plugins directory.
 */
function woo_price_quote_activation_redirect_settings( $plugin ) {
	$activated_plugins   = get_option( 'active_plugins' );
	$woo_price_quote_pro = 'woocommerce-price-quote-pro/woocommerce-price-quote-pro.php';
	if ( plugin_basename( __FILE__ ) === $plugin && class_exists( 'WooCommerce' ) && ! in_array( $woo_price_quote_pro, $activated_plugins ) ) {
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action']  == 'activate' && isset( $_REQUEST['plugin'] ) && $_REQUEST['plugin'] == $plugin) {
			wp_safe_redirect( admin_url( 'admin.php?page=wcpq-settings&tab=wcpq-welcome' ) );
			exit;
		}
	}
}
add_action( 'activated_plugin', 'woo_price_quote_activation_redirect_settings', 9999 );
