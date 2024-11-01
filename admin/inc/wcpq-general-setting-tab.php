<?php
/**
 * This file is used for rendering and saving plugin settings for non logged-in user.
 *
 * @package    Woocommerce_Price_Quote
 * @subpackage Woocommerce_Price_Quote/inc
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wp_roles;
$user_roles     = $wp_roles->get_names();
$wcpq_gen_stngs = get_option( 'wcpq_gen_stngs' );
$products       = wcpq_get_products();
$quote_mode     = ( isset( $wcpq_gen_stngs['quote_mode'] ) ) ? $wcpq_gen_stngs['quote_mode'] : 'all-products';
$class          = '';
if ( $quote_mode == 'all-products' ) {
	$class = 'hide';
}
?>
<div class="wbcom-tab-content">
	<form method="post" action="options.php">
		<?php
			settings_fields( 'wcpq_gen_stngs' );
			do_settings_sections( 'wcpq_gen_stngs' );
		?>
		<div class="wbcom-admin-title-section">
			<h3><?php esc_html_e( 'General Settings', 'woocommerce-price-quote' ); ?></h3>
		</div>
		<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
		<div class="form-table">
		<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">
			
				<div scope="row"><label for="blogname"><?php esc_html_e( 'Quote Mode', 'woocommerce-price-quote' ); ?></label></div>
		
				</div>
				<div class="wbcom-settings-section-options">
						<label class="blpro-label-padding">
							<input class="wcprq-quote-mode" name="wcpq_gen_stngs[quote_mode]" value="all-products" type="radio" <?php ( isset( $wcpq_gen_stngs['quote_mode'] ) ) ? checked( $wcpq_gen_stngs['quote_mode'], 'all-products' ) : ''; ?>>
							<span><?php esc_html_e( 'All Products', 'woocommerce-price-quote' ); ?></span>
						</label>
						
						<label class="blpro-label-padding">
							<input class="wcprq-quote-mode" name="wcpq_gen_stngs[quote_mode]" value="selected-products" type="radio" <?php ( isset( $wcpq_gen_stngs['quote_mode'] ) ) ? checked( $wcpq_gen_stngs['quote_mode'], 'selected-products' ) : ''; ?>>
							<span><?php esc_html_e( 'Selected Products', 'woocommerce-price-quote' ); ?></span>
						</label>
			  </div>		        
			   </div>
			<div class="wcprq-products <?php echo esc_attr( $class ); ?>">
			<div class="wbcom-settings-section-wrap">
			<div class="wbcom-settings-section-options-heading">

				<div scope="row"><label for="blogname"><?php esc_html_e( 'Products', 'woocommerce-price-quote' ); ?></label></div>
				</div>
				<div class="wbcom-settings-section-options">
					<select id="products-to-quote" multiple name="wcpq_gen_stngs[products-to-quote][]" >
						<?php if ( ! empty( $products ) ) { ?>
							<?php
							foreach ( $products as $product ) {
								$selected = '';
								?>
								<?php
								if ( isset( $wcpq_gen_stngs['products-to-quote'] ) && in_array( $product->ID, $wcpq_gen_stngs['products-to-quote'] ) ) {
									$selected = 'selected';
								}
								?>
								<option value="<?php echo esc_attr( $product->ID ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $product->post_title ); ?></option>
							<?php } ?>
						<?php } ?>
					</select>
					</div>
				 </div>
				</div>
				<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
				<div scope="row"><label for="blogname"><?php esc_html_e( 'Hide add to cart button', 'woocommerce-price-quote' ); ?></label></div>
				</div>
				<div class="wbcom-settings-section-options">
					<input type="checkbox" name="wcpq_gen_stngs[remove_cat_button]" value="yes" <?php ( isset( $wcpq_gen_stngs['remove_cat_button'] ) ) ? checked( $wcpq_gen_stngs['remove_cat_button'], 'yes' ) : ''; ?>>
				</div>
				</div>
				<div class="wbcom-settings-section-wrap">
					<div class="wbcom-settings-section-options-heading">
				<div scope="row"><label for="blogname"><?php esc_html_e( 'Hide product prices', 'woocommerce-price-quote' ); ?></label></div>
				</div>
					<input type="checkbox" name="wcpq_gen_stngs[hide-price]" value="yes" <?php ( isset( $wcpq_gen_stngs['hide-price'] ) ) ? checked( $wcpq_gen_stngs['hide-price'], 'yes' ) : ''; ?>>
				</div>
	
				<div class="wbcom-settings-section-wrap">
					
				<div class="wbcom-settings-section-options-heading">
				<div scope="row"><label for="blogname"><?php esc_html_e( 'Quote products according to user roles', 'woocommerce-price-quote' ); ?></label></div>
				
					<p class="wcpq-selection-tags">
						<a href="javascript:void(0);" id="wcpq-select-all-uroles"><?php esc_html_e( 'Select All', 'woocommerce-price-quote' ); ?></a> /
						<a href="javascript:void(0);" id="wcpq-unselct-all-uroles"><?php esc_html_e( 'Unselect All', 'woocommerce-price-quote' ); ?></a>
					</p>
					</div>
					<div class="wbcom-settings-section-options">
						<div>
					<select id="user-role-stngs" name="wcpq_gen_stngs[user_roles][]" multiple>
						<?php foreach ( $user_roles as $slug => $role_name ) { ?>
							<?php $selected = ( ! empty( $wcpq_gen_stngs['user_roles'] ) && in_array( $slug, $wcpq_gen_stngs['user_roles'] ) ) ? 'selected' : ''; ?>
							<option value="<?php echo esc_attr( $slug ); ?>" <?php echo esc_attr( $selected ); ?>><?php echo esc_html( $role_name ); ?></option>
						<?php } ?>
					</select>
						</div>
				 </div>
			   </div>
			   <div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
				<div scope="row"><label for="blogname"><?php esc_html_e( 'Add to quote action after click', 'woocommerce-price-quote' ); ?></label></div>
				</div>
				<div class="wbcom-settings-section-options">
					
						<label class="blpro-label-padding">
							<input name="wcpq_gen_stngs[click_action]" value="link" type="radio" <?php ( isset( $wcpq_gen_stngs['click_action'] ) ) ? checked( $wcpq_gen_stngs['click_action'], 'link' ) : ''; ?>>
							<span><?php esc_html_e( 'after clicking on "add to quote button", the user will view a link to go to the request list.', 'woocommerce-price-quote' ); ?></span>
						</label><br/>
						<label class="blpro-label-padding">
							<input name="wcpq_gen_stngs[click_action]" value="rdirect" type="radio" <?php ( isset( $wcpq_gen_stngs['click_action'] ) ) ? checked( $wcpq_gen_stngs['click_action'], 'rdirect' ) : ''; ?>>
							<span><?php esc_html_e( 'after clicking on "add to quote button", the user will be automatically redirected to the request list.', 'woocommerce-price-quote' ); ?></span>
						</label>
				
				</div>	
				</div>
				<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
				<div scope="row"><label for="blogname"><?php esc_html_e( 'Quote request page', 'woocommerce-price-quote' ); ?></label><p class="description"><?php esc_html_e( 'Page should contain [products_to_quote] shortcode.', 'woocommerce-price-quote' ); ?></p></div>
				</div>
				 <div class="wbcom-settings-section-options">
					<select id="request-quote-page" name="wcpq_gen_stngs[quote_page]">
						<?php foreach ( get_pages() as $index => $page ) { ?>
							<option value="<?php echo esc_attr( $page->ID ); ?>" <?php ( isset( $wcpq_gen_stngs['quote_page'] ) ) ? selected( $wcpq_gen_stngs['quote_page'], $page->ID ) : ''; ?> ><?php echo esc_html( $page->post_title ); ?></option>
						<?php } ?>
					</select>
				</div>
				</div>
				<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
				<div scope="row"><label for="blogname"><?php esc_html_e( 'Quoted products page', 'woocommerce-price-quote' ); ?></label><p class="description"><?php esc_html_e( 'Page should contain [my_quoted_products] shortcode.', 'woocommerce-price-quote' ); ?></p></div>
				</div>
				<div class="wbcom-settings-section-options">
					<select id="quoted-products-page" name="wcpq_gen_stngs[quoted_prods_page]">
						<?php foreach ( get_pages() as $index => $page ) { ?>
							<option value="<?php echo esc_attr( $page->ID ); ?>" <?php ( isset( $wcpq_gen_stngs['quoted_prods_page'] ) ) ? selected( $wcpq_gen_stngs['quoted_prods_page'], $page->ID ) : ''; ?> ><?php echo esc_html( $page->post_title ); ?></option>
						<?php } ?>
					</select>
				</div>
				</div>
			</tr>
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
				<div scope="row"><label for="blogname"><?php esc_html_e( 'Add to quote button label', 'woocommerce-price-quote' ); ?></label></div>
				</div>
				<div class="wbcom-settings-section-options">
					<input type="text" name="wcpq_gen_stngs[quote_label]" value="<?php echo ( isset( $wcpq_gen_stngs['quote_label'] ) ) ? $wcpq_gen_stngs['quote_label'] : '';//phpcs:ignore ?>" placeholder="<?php esc_html_e( 'Add to quote', 'woocommerce-price-quote' ); ?>">
				</div>
		
			</div>
			<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
				<div scope="row"><label for="blogname"><?php esc_html_e( 'Remove from quote label', 'woocommerce-price-quote' ); ?></label></div>
				</div>
				<div class="wbcom-settings-section-options">
					<input type="text" name="wcpq_gen_stngs[remove_from_quote]" value="<?php echo ( isset( $wcpq_gen_stngs['remove_from_quote'] ) ) ? $wcpq_gen_stngs['remove_from_quote'] : ''; //phpcs:ignore ?>" placeholder="<?php esc_html_e( 'Remove from quote', 'woocommerce-price-quote' ); ?>">
				</div>
				</div>

				<div class="wbcom-settings-section-wrap">
				<div class="wbcom-settings-section-options-heading">
				<div scope="row"><label for="blogname"><?php esc_html_e( 'Browse quoted product list label', 'woocommerce-price-quote' ); ?></label></div>
				</div>
				<div class="wbcom-settings-section-options">
					<input type="text" name="wcpq_gen_stngs[browse_list]" value="<?php echo ( isset( $wcpq_gen_stngs['browse_list'] ) ) ? $wcpq_gen_stngs['browse_list'] : '';//phpcs:ignore ?>" placeholder="<?php esc_html_e( 'Browse the list', 'woocommerce-price-quote' ); ?>">
				</div>
				</div>
				</div>
		<?php submit_button(); ?>
	</form>
	</div>
</div>
