<?php
/**
 * This file is used for rendering and saving plugin welcome settings.
 *
 * @package    Woocommerce_Price_Quote
 * @subpackage Woocommerce_Price_Quote/inc
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
	// Exit if accessed directly.
}
?>

<div class="wbcom-tab-content">
	<div class="wbcom-welcome-main-wrapper">
		<div class="wbcom-welcome-head">
			<p class="wbcom-welcome-description">
			<p class="wbcom-welcome-description"><?php esc_html_e( 'Woo Price Quotes plugin allows users to send a quote for a product to purchase. The customers will not be able to view the purchase details of the product. Rather, they can send an inquiry regarding the product.', 'woocommerce-price-quote' ); ?></p>

			</p>
		</div><!-- .wbcom-welcome-head -->

		<div class="wbcom-welcome-content">

			<div class="wbcom-welcome-support-info">
				<h3><?php esc_html_e( 'Help &amp; Support Resources', 'woocommerce-price-quote' ); ?></h3>
				<p><?php esc_html_e( 'Here are all the resources you may need to get help from us. Documentation is usually the best place to start. Should you require help anytime, our customer care team is available to assist you at the support center.', 'woocommerce-price-quote' ); ?></p>

				<div class="wbcom-support-info-wrap">
					<div class="wbcom-support-info-widgets">
						<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-book"></span><?php esc_html_e( 'Documentation', 'woocommerce-price-quote' ); ?></h3>
						<p><?php esc_html_e( 'We have prepared an extensive guide onWoo Product Inquiry & Quote to learn all aspects of the plugin. You will find most of your answers here.', 'woocommerce-price-quote' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/docs/woo-addons/woo-price-quotes/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Read Documentation', 'woocommerce-price-quote' ); ?></a>
						</div>
					</div>

					<div class="wbcom-support-info-widgets">
						<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-sos"></span><?php esc_html_e( 'Support Center', 'woocommerce-price-quote' ); ?></h3>
						<p><?php esc_html_e( 'We strive to offer the best customer care via our support center. Once your theme is activated, you can ask us for help anytime.', 'woocommerce-price-quote' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/support/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Get Support', 'woocommerce-price-quote' ); ?></a>
					</div>
					</div>
					<div class="wbcom-support-info-widgets">
						<div class="wbcom-support-inner">
						<h3><span class="dashicons dashicons-admin-comments"></span><?php esc_html_e( 'Got Feedback?', 'woocommerce-price-quote' ); ?></h3>
						<p><?php esc_html_e( 'We want to hear about your experience with the plugin. We would also love to hear any suggestions you may for future updates.', 'woocommerce-price-quote' ); ?></p>
						<a href="<?php echo esc_url( 'https://wbcomdesigns.com/contact/' ); ?>" class="button button-primary button-welcome-support" target="_blank"><?php esc_html_e( 'Send Feedback', 'woocommerce-price-quote' ); ?></a>
					</div>
					</div>
				</div>
			</div>
		</div>		
	</div><!-- .wbcom-welcome-main-wrapper -->
</div><!-- .wbcom-tab-content -->





























