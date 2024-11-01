<?php

function wcpq_get_quote_mode() {
	$wcpq_gen_stngs = get_option( 'wcpq_gen_stngs' );
	$quote_mode     = ( isset( $wcpq_gen_stngs['quote_mode'] ) ) ? $wcpq_gen_stngs['quote_mode'] : 'all-products';

	return apply_filters( 'alter_wcpq_get_quote_mode', $quote_mode );
}

function wcpq_get_products_to_quote() {
	$wcpq_gen_stngs = get_option( 'wcpq_gen_stngs' );
	$quote_products = ( isset( $wcpq_gen_stngs['products-to-quote'] ) ) ? $wcpq_gen_stngs['products-to-quote'] : array();

	return apply_filters( 'alter_wcpq_get_products_to_quote', $quote_products );
}

function wcpq_get_add_to_quote_label() {
	$wcpq_gen_stngs     = get_option( 'wcpq_gen_stngs' );
	$add_to_quote_label = ( isset( $wcpq_gen_stngs['quote_label'] ) && ! empty( $wcpq_gen_stngs['quote_label'] ) ) ? $wcpq_gen_stngs['quote_label'] : 'Add to quote';

	return apply_filters( 'alter_wcpq_get_add_to_quote_label', $add_to_quote_label );
}

function wcpq_get_remove_from_quote_label() {
	$wcpq_gen_stngs     = get_option( 'wcpq_gen_stngs' );
	$add_to_quote_label = ( isset( $wcpq_gen_stngs['remove_from_quote'] ) && ! empty( $wcpq_gen_stngs['remove_from_quote'] ) ) ? $wcpq_gen_stngs['remove_from_quote'] : 'Remove from quote';

	return apply_filters( 'alter_wcpq_get_add_to_quote_label', $add_to_quote_label );
}

function wcpq_get_browse_quote_list_label() {
	$wcpq_gen_stngs        = get_option( 'wcpq_gen_stngs' );
	$browse_the_list_label = ( isset( $wcpq_gen_stngs['browse_list'] ) && ! empty( $wcpq_gen_stngs['browse_list'] ) ) ? $wcpq_gen_stngs['browse_list'] : 'Browse the list';

	return apply_filters( 'alter_wcpq_get_browse_quoted_list_label', $browse_the_list_label );
}

function wcpq_get_quote_click_action() {
	$wcpq_gen_stngs     = get_option( 'wcpq_gen_stngs' );
	$quote_click_action = ( isset( $wcpq_gen_stngs['click_action'] ) && ! empty( $wcpq_gen_stngs['click_action'] ) ) ? $wcpq_gen_stngs['click_action'] : 'link';

	return apply_filters( 'alter_wcpq_get_quote_click_action', $quote_click_action );
}

function wcpq_get_quote_request_page() {
	$wcpq_gen_stngs     = get_option( 'wcpq_gen_stngs' );
	$quote_request_page = ( isset( $wcpq_gen_stngs['quote_page'] ) && ! empty( $wcpq_gen_stngs['quote_page'] ) ) ? $wcpq_gen_stngs['quote_page'] : '';

	return apply_filters( 'alter_wcpq_get_quote_request_page', $quote_request_page );
}

function wcpq_get_quoted_products_page() {
	$wcpq_gen_stngs    = get_option( 'wcpq_gen_stngs' );
	$quoted_prods_page = ( isset( $wcpq_gen_stngs['quoted_prods_page'] ) && ! empty( $wcpq_gen_stngs['quoted_prods_page'] ) ) ? $wcpq_gen_stngs['quoted_prods_page'] : '';

	return apply_filters( 'alter_wcpq_get_quoted_products_page', $quoted_prods_page );
}

function wcpq_get_user_roles_for_quoting() {
	$wcpq_gen_stngs = get_option( 'wcpq_gen_stngs' );
	$user_roles     = ( isset( $wcpq_gen_stngs['user_roles'] ) && ! empty( $wcpq_gen_stngs['user_roles'] ) ) ? $wcpq_gen_stngs['user_roles'] : false;

	return apply_filters( 'alter_wcpq_get_user_roles_for_quoting', $user_roles );
}

function wcpq_is_quote_available_for_user_role() {
	$users_roles     = wcpq_get_user_roles_for_quoting();
	$quote_available = true;
	if ( $users_roles ) {
		$user            = wp_get_current_user();
		$quote_available = false;
		if ( ! empty( array_intersect( $user->roles, $users_roles ) ) ) {
			$quote_available = true;
		}
	}

	return apply_filters( 'alter_wcpq_is_quote_available_for_user_role', $quote_available );
}

function wcpq_is_hide_product_prices() {
	$wcpq_gen_stngs = get_option( 'wcpq_gen_stngs' );
	$hide_price     = ( isset( $wcpq_gen_stngs['hide-price'] ) && 'yes' === $wcpq_gen_stngs['hide-price'] ) ? true : false;

	return apply_filters( 'alter_wcpq_is_hide_product_prices', $hide_price );
}

function wcpq_is_hide_add_to_cart() {
	$wcpq_gen_stngs = get_option( 'wcpq_gen_stngs' );
	$hide_cart      = ( isset( $wcpq_gen_stngs['remove_cat_button'] ) && ! empty( $wcpq_gen_stngs['remove_cat_button'] ) ) ? true : false;

	return apply_filters( 'alter_wcpq_is_hide_add_to_cart', $hide_cart );
}

/**
 * Get an array of the products.
 *
 * @return array
 */
function wcpq_get_products() {
	$args     = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	);
	$products = get_posts( $args );
	if ( ! empty( $products ) ) {
		return $products;
	}
}
