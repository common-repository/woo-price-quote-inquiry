<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly
$uid             = get_current_user_id();
$products_quoted = get_user_meta( $uid, 'my_quoted_products', true );
if ( ! empty( $products_quoted ) ) {
	$prod_str = '';
	foreach ( $products_quoted as $pid ) {
		$prod_str .= $pid . ',';
	}
	$prod_str = rtrim( $prod_str, ',' );
	echo do_shortcode( '[products ids="' . $prod_str . '" paginate=true per_page=10]' );
} else {
	echo '<p class="woocommerce-info">' . esc_html_e( 'No quoted product.', 'woocommerce-price-quote' ) . '</p>';
}
