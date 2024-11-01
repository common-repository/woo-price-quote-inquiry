<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly
$name = $email = '';
if ( is_user_logged_in() ) {
	$user_id = get_current_user_id();
	$usr     = get_userdata( $user_id );
	$name    = $usr->data->display_name;
	$email   = $usr->data->user_email;
}

$user_id            = get_current_user_id();
$products_to_quoted = get_user_meta( $user_id, 'products_to_quote', true );
$products_quoted    = get_user_meta( $user_id, 'my_quoted_products', true );
if ( ! empty( $products_quoted ) ) {
	$quoted_prod_page_id = wcpq_get_quoted_products_page();
	if ( $quoted_prod_page_id ) {
		$quoted_products_link = get_permalink( $quoted_prod_page_id );
		echo '<p><a href="' . esc_url( $quoted_products_link ) . '" class="browse-quote-products button">Browse quoted products</a></p>';
	}
}

if ( ! empty( $products_to_quoted ) ) {
	$prod_str = '';
	foreach ( $products_to_quoted as $pid ) {
		$prod_str .= $pid . ',';
	}
	$prod_str = rtrim( $prod_str, ',' );
	echo '<h3>' . esc_html__( 'Quote products', 'woocommerce-price-quote' ) . '</h3>';
	echo '<div class="products_to_quote_html">';
	echo do_shortcode( '[products ids="' . $prod_str . '" paginate=true per_page=10]' );
	echo '</div>';
	?>
	<p class="woocommerce-info multi-quote-message"> <?php echo esc_html__( 'Quotation saved for the products. We will get back to you soon with your details.', 'woocommerce-price-quote' ); ?></p>
		<div class="wcpq-product-enquiry">
			<h4><?php esc_html_e( 'Quotation details', 'woocommerce-price-quote' ); ?></h4>
				<table class="wcpq-enquiry-tbl">
					<tr>
						<th><?php esc_html_e( 'Name', 'woocommerce-price-quote' ); ?></th>
						<td>
							<input type="text" placeholder="<?php esc_html_e( 'Your Name', 'woocommerce-price-quote' ); ?>" id="wcpq_enquiree_name" value="<?php echo esc_attr( $name ); ?>" size="35">
						</td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Email', 'woocommerce-price-quote' ); ?></th>
						<td>
							<input type="email" placeholder="<?php esc_html_e( 'Your Email', 'woocommerce-price-quote' ); ?>" id="wcpq_enquiree_email" value="<?php echo esc_attr( $email ); ?>" size="35">
						</td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Message', 'woocommerce-price-quote' ); ?></th>
						<td>
							<textarea placeholder="<?php esc_html_e( 'Message', 'woocommerce-price-quote' ); ?>" id="wcpq_enquiree_summary"></textarea>
						</td>
					</tr>
					<tr>
						<th><?php esc_html_e( 'Send me a copy', 'woocommerce-price-quote' ); ?></th>
						<td>
							<input type="checkbox" value="yes" id="wcpq_send_copy" name="wcpq_send_copy">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="hidden" id="wcpq_quoted_products_id" value="<?php echo esc_attr( $prod_str ); ?>">
							<input type="button" value="<?php esc_html_e( 'Send Quote', 'woocommerce-price-quote' ); ?>" id="wcpq_send_multiple_enquiry">
						</td>
					</tr>
				</table>
		</div>
	<?php
} else {
	echo '<div class="woocommerce-info">' . esc_html__( 'No product to quote.', 'woocommerce-price-quote' ) . '</div>';
}
?>
