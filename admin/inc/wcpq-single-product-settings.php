<?php
/**
 * This file is used for pro feature single product tab content.
 *
 * @package    Woocommerce_Price_Quote
 * @subpackage Woocommerce_Price_Quote/inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="wbcom-tab-content price-quote-tab">
	<div class="wbcom-welcome-main-wrapper">
		<a href="<?php echo esc_url( 'https://wbcomdesigns.com/downloads/woo-product-inquiry-quote-pro/' ); ?>" target="_blank">
			<small><?php esc_html_e( 'Get Pro Version', 'woocommerce-price-quote' ); ?></small>
			<img src="<?php echo esc_url( WCPQ_PLUGIN_URL . 'admin/images/single-product.png' ); ?>">
		</a>
	</div>
</div>