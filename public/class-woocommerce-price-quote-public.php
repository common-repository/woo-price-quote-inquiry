<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Price_Quote
 * @subpackage Woocommerce_Price_Quote/public
 */

if ( ! class_exists( 'Woocommerce_Price_Quote_Public' ) ) :

	/**
	 * The public-facing functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the public-facing stylesheet and JavaScript.
	 *
	 * @package    Woocommerce_Price_Quote
	 * @subpackage Woocommerce_Price_Quote/public
	 * @author     wbcomdesigns <admin@wbcomdesigns.com>
	 */
	class Woocommerce_Price_Quote_Public {

		/**
		 * The ID of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $plugin_name    The ID of this plugin.
		 */
		private $plugin_name;

		/**
		 * The version of this plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @var      string    $version    The current version of this plugin.
		 */
		private $version;

		/**
		 * Initialize the class and set its properties.
		 *
		 * @since    1.0.0
		 * @param      string $plugin_name       The name of the plugin.
		 * @param      string $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

		}

		/**
		 * Register the stylesheets for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles() {
			global $post;

			if ( is_shop() || is_product() || ( has_shortcode( $post->post_content, 'products_to_quote' ) || has_shortcode( $post->post_content, 'my_quoted_products' ) ) ) {

				/**
				* This function is provided for demonstration purposes only.
				*
				* An instance of this class should be passed to the run() function
				* defined in Woocommerce_Price_Quote_Loader as all of the hooks are defined
				* in that particular class.
				*
				* The Woocommerce_Price_Quote_Loader will then create the relationship
				* between the defined hooks and the functions defined in this
				* class.
				*/

				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-price-quote-public.css', array(), $this->version, 'all' );

				if ( ! wp_style_is( 'font-awesome', 'enqueued' ) ) {
					wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
				}
			}

		}

		/**
		 * Register the JavaScript for the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts() {
			global $post;

			if ( is_shop() || is_product() || ( has_shortcode( $post->post_content, 'products_to_quote' ) || has_shortcode( $post->post_content, 'my_quoted_products' ) ) ) {
				/**
				 * This function is provided for demonstration purposes only.
				 *
				 * An instance of this class should be passed to the run() function
				 * defined in Woocommerce_Price_Quote_Loader as all of the hooks are defined
				 * in that particular class.
				 *
				 * The Woocommerce_Price_Quote_Loader will then create the relationship
				 * between the defined hooks and the functions defined in this
				 * class.
				 */

				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-price-quote-public.js', array( 'jquery' ), $this->version, true );
				$wcpq_get_request_page = wcpq_get_quote_request_page();

				wp_localize_script(
					$this->plugin_name,
					'wcpq_ajax_object',
					array(
						'ajax_url'          => admin_url( 'admin-ajax.php' ),
						'ajax_nonce'        => wp_create_nonce( 'wcpq_ajax_security' ),
						'is_user_logged_in' => is_user_logged_in() ? true : false,
						'is_request_page'   => $wcpq_get_request_page,
					)
				);
			}
		}

		/**
		 * Get product id from product object
		 *
		 * @param  object $obj  product object
		 * @param  string $prop get property from boject
		 *
		 * @return string|int    based on $prop
		 */
		public function wcpq_access_protected( $obj, $prop ) {
			$reflection = new ReflectionClass( $obj );
			$property   = $reflection->getProperty( $prop );
			$property->setAccessible( true );
			return $property->getValue( $obj );
		}

		/**
		 * Check if product is purchasable
		 *
		 * @param  boolean $is_purchasable
		 * @param  object  $product
		 *
		 * @return boolean
		 */
		public function wcpq_product_is_purchasable( $is_purchasable, $product ) {

			$quote_mode      = wcpq_get_quote_mode();
			$quote_available = wcpq_is_quote_available_for_user_role();

			if ( $quote_mode == 'all-products' && $quote_available ) {
				return false;
			} else {
				$quote_products = wcpq_get_products_to_quote();
			}

			$pid = $this->wcpq_access_protected( $product, 'id' );
			if ( ! empty( $quote_products ) ) {
				if ( in_array( $pid, $quote_products ) && $quote_available ) {
					return false;
				} else {
					return $is_purchasable;
				}
			}
			return $is_purchasable;
		}

		/**
		 * Removes the price from selected-products or all-products.
		 * Dependent on Quote Mode
		 *
		 * @author wbcomdesigns
		 * @version 1.0.0
		 * @since   1.0.0
		 * @link     https://wbcomdesigns.com/
		 */
		public function wcpq_hide_product_prices( $price, $product ) {
			$p_id       = $this->wcpq_access_protected( $product, 'id' );
			$hide_price = wcpq_is_hide_product_prices();
			$quote_mode = wcpq_get_quote_mode();

			if ( $hide_price ) {
				if ( 'selected-products' === $quote_mode ) {
					$products = wcpq_get_products_to_quote();
					if ( is_array( $products ) ) {
						if ( in_array( $p_id, $products ) ) {
							$price = '';
						}
					}
				} elseif ( 'all-products' === $quote_mode ) {
					$price = '';
				}
			}
			return $price;
		}

		/**
		 * Removes the add to cart button in specific product
		 *
		  * @param  boolean $is_purchasable
		  * @param  object $product
		 */
		public function wcpq_hide_add_to_cart_specific_product( $is_purchasable, $product ) {
			global $product;
			$quote_mode 	= wcpq_get_quote_mode();
			$wcpq_gen_stngs = get_option('wcpq_gen_stngs');
			if ( 'selected-products' === $quote_mode ) {
				if ( array_key_exists( 'remove_cat_button', $wcpq_gen_stngs ) && 'yes' == $wcpq_gen_stngs['remove_cat_button'] ) {
					if ( ! empty( $wcpq_gen_stngs['products-to-quote'] ) && in_array( get_the_id(), $wcpq_gen_stngs['products-to-quote'] ) ) {
						return false;
					}
				}
			}
			return $is_purchasable;
			}


		/**
		 * Removes the add to cart button
		 *
		 * @author wbcomdesigns
		 * @version 1.0.0
		 * @since   1.0.0
		 * @link    https://wbcomdesigns.com/
		 * @return  [type]                       [description]
		 */
		public function wcpq_hide_add_to_cart() {
			$quote_mode = wcpq_get_quote_mode();
			if ( ! wcpq_is_hide_add_to_cart() ) {
				return false;
			}
			if( 'all-products' !== $quote_mode ){
				return false;
			}
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
		}

		/**
		 * Add Quote button on products on archive page and single page.
		 */
		public function wcpq_add_to_quote_button() {
			global $product;
			$p_id                    = $this->wcpq_access_protected( $product, 'id' );
			$add_to_quote_label      = wcpq_get_add_to_quote_label();
			$remove_from_quote_label = wcpq_get_remove_from_quote_label();
			$wcpq_get_request_page   = wcpq_get_quote_request_page();
			if ( ! is_user_logged_in() ) {
				if ( is_product() ) {
					echo '<a href="javascript:void(0)" class="wcpq-add-to-quote button" data-id="' . esc_attr( $p_id ) . '">' . esc_html( $add_to_quote_label ) . '</a>';
				} else {
					$product_link = get_permalink( $p_id );
					echo '<a href="' . esc_url( $product_link ) . '" class="wcpq-add-to-quote button" data-id="' . esc_attr( $p_id ) . '">' . esc_html( $add_to_quote_label ) . '</a>';
				}
			} else {

				$uid                = get_current_user_id();
				$products_to_quoted = get_user_meta( $uid, 'products_to_quote', true );
				$show_add_quote     = false;
				$quote_mode         = wcpq_get_quote_mode();

				if ( 'all-products' === $quote_mode ) {
					$show_add_quote = true;
				} else {
					$quote_products = wcpq_get_products_to_quote();
					if ( ! empty( $quote_products ) ) {
						if ( in_array( $p_id, $quote_products ) ) {
							$show_add_quote = true;
						} else {
							$show_add_quote = false;
						}
					}
				}

				$quote_available    = wcpq_is_quote_available_for_user_role();
				$quote_page_id      = wcpq_get_quote_request_page();
				$request_quote_link = get_permalink( $quote_page_id );
				$browse_list_label  = wcpq_get_browse_quote_list_label();

				if ( $show_add_quote && $quote_available ) {
					if ( is_array( $products_to_quoted ) && in_array( $p_id, $products_to_quoted ) ) {
						echo '<a href="javascript:void(0)" class="wcpq-remove-quote button" data-id="' . esc_attr( $p_id ) . '">' . esc_html( $remove_from_quote_label ) . '</a>';
						if ( ! is_page( $wcpq_get_request_page ) ) {
							echo '<a href="' . esc_url( $request_quote_link ) . '" class="browse-quote-list button" >' . esc_html( $browse_list_label ) . '</a>';
						}
					} else {
						echo '<a href="javascript:void(0)" class="wcpq-add-to-quote button" data-id="' . esc_attr( $p_id ) . '">' . esc_html( $add_to_quote_label ) . '</a>';
					}
				}
			}
		}

		/**
		 * Add product to quote list
		 */
		public function wcpq_add_products_to_quote() {
			if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wcpq_ajax_security' ) ) {
				exit();
			}
			if ( isset( $_POST['action'] ) && $_POST['action'] === 'wcpq_add_products_to_quote' ) {
				$quote_product_id = isset( $_POST['quote_product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['quote_product_id'] ) ) : '';

				// Update user meta for adding products to quote
				if ( is_user_logged_in() ) {
					$uid               = get_current_user_id();
					$products_to_quote = get_user_meta( $uid, 'products_to_quote', true );
					if ( ! is_array( $products_to_quote ) ) {
						$products_to_quote = array();
					}
					$products_to_quote[] = $quote_product_id;
					update_user_meta( $uid, 'products_to_quote', $products_to_quote );
				}

				$quote_action            = wcpq_get_quote_click_action();
				$quote_page_id           = wcpq_get_quote_request_page();
				$request_quote_link      = get_permalink( $quote_page_id );
				$remove_from_quote_label = wcpq_get_remove_from_quote_label();
				$browse_list_label       = wcpq_get_browse_quote_list_label();

				$remove_from_quote_btn = '<a href="javascript:void(0)" class="wcpq-remove-quote button" data-id="' . $quote_product_id . '">' . $remove_from_quote_label . '</a>';

				$browse_quoted_list = '<a href="' . $request_quote_link . '" class="browse-quote-list button" >' . $browse_list_label . '</a>';

				$response = array(
					'quote_action'          => $quote_action,
					'remove_from_quote_btn' => $remove_from_quote_btn,
					'browse_quoted_list'    => $browse_quoted_list,
					'quote_pg_link'         => $request_quote_link,
				);

				echo wp_json_encode( $response );
				die;
			}
		}

		/**
		 * Remove product from quote list
		 *
		 * @return string ajax responce
		 */
		public function wcpq_remove_products_from_quote() {
			if ( isset( $_POST['nonce'] ) && ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'wcpq_ajax_security' ) ) {
				exit();
			}
			if ( isset( $_POST['action'] ) && $_POST['action'] === 'wcpq_remove_products_from_quote' ) {
				$remove_product_id = isset( $_POST['p_id'] ) ? sanitize_text_field( wp_unslash( $_POST['p_id'] ) ) : '';

				$user_id          = get_current_user_id();
				$product_to_quote = get_user_meta( $user_id, 'products_to_quote', true );
				foreach ( $product_to_quote as $key => $p_id ) {
					if ( $p_id == $remove_product_id ) {
						unset( $product_to_quote[ $key ] );
					}
				}
				update_user_meta( $user_id, 'products_to_quote', $product_to_quote );

				$add_to_quote_label = wcpq_get_add_to_quote_label();
				$add_to_quote_btn   = '<a href="javascript:void(0)" class="wcpq-add-to-quote button" data-id="' . $p_id . '">' . $add_to_quote_label . '</a>';

				$response = array(
					'add_to_quote_btn' => $add_to_quote_btn,
				);
				echo json_encode( $response );
				die;
			}
		}

		public function wcpq_products_to_quote_shortcode() {
			$file = WCPQ_PLUGIN_PATH . 'shortcodes/products-to-quote.php';
			if ( file_exists( $file ) ) {
				include_once $file;
			}
		}

		/**
		 * Function added the woocommerce class on shortcode pages.
		 *
		 * @param  array $classes Get a Body Classes.
		 */
		public function wcpq_add_body_class_on_shortcode_page( $classes ) {

			global $post;

			if ( is_shop() || is_product() || ( has_shortcode( $post->post_content, 'products_to_quote' ) || has_shortcode( $post->post_content, 'my_quoted_products' ) ) ) {
				$classes[] = 'woocommerce';
			}
			return $classes;
		}

		public function wcpq_send_multiple_product_enquiry() {
			if ( isset( $_POST['action'] ) && 'wcpq_send_multiple_product_enquiry' === $_POST['action'] ) {

				check_ajax_referer( 'wcpq_ajax_security', 'ajax_nonce' );

				$name                   = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
				$email                  = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
				$summary                = isset( $_POST['summary'] ) ? sanitize_text_field( wp_unslash( $_POST['summary'] ) ) : '';
				$send_user_copy         = isset( $_POST['send_copy'] ) ? sanitize_text_field( wp_unslash( $_POST['send_copy'] ) ) : '';
				$product_id             = isset( $_POST['send_copy'] ) ? sanitize_text_field( wp_unslash( $_POST['send_copy'] ) ) : '';
				$custom_logo_id         = get_theme_mod( 'custom_logo' );
				$logo                   = wp_get_attachment_image_src( $custom_logo_id, 'full' );
				$subject                = __( 'Enquiry for products', 'woocommerce-price-quote' );
				$authorwise_product_id  = array();
				$return_responce        = '';
				$quoted_products        = '';
				$quoted_author_products = '';
				$author_msg             = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title>Demystifying Email Design</title>
				<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
				</head>
				<body style="margin: 0; padding: 0;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
				<td>
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc;">
				<tr>
				<td align="center" bgcolor="#70bbd9" style="padding: 30px 0 15px 0;color:#ffffff;font-weight:600;font-size:30px">
				' . get_bloginfo( 'name' ) . '
				</td>
				</tr>
				<tr>
				<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
				<td align="center" style="color: #153643!important; font-family: Arial, sans-serif; font-size: 30px;padding-bottom:20px;font-weight:800"> Quoted Product
				</td>
				</tr>';

				$customer_msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title>Demystifying Email Design</title>
				<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
				</head>
				<body style="margin: 0; padding: 0;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
				<td>
				<table align="center" border="0" cellpadding="0" cellspacing="0" width="600" style="border: 1px solid #cccccc;">
				<tr>
				<td align="center" bgcolor="#70bbd9" style="padding: 30px 0 15px 0;color:#ffffff;font-weight:600;font-size:30px">
				' . get_bloginfo( 'name' ) . '
				</td>
				</tr>
				<tr>
				<td bgcolor="#ffffff" style="padding: 40px 30px 40px 30px;">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
				<tr>
				<td align="center" style="color: #153643!important; font-family: Arial, sans-serif; font-size: 30px;padding-bottom:20px;font-weight:800">Your Quoted Product
				</td>
				</tr>';

				$customer_msg_footer = '</table>
				</td>
				</tr>
				<tr>
				<td bgcolor="#ee4c50">
				<table border="0" cellpadding="0" cellspacing="0" width="100%" style="padding:30px 15px 30px 15px">
				<tr>
				<td align="center" width="100%" style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px; font-weight:800;">
				Copyright &copy; ' . date( 'Y' ) . '<a style="color:#ffffff!important;" href="' . home_url() . '">|' . get_bloginfo( 'name' ) . '</a>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</td>
				</tr>
				</table>
				</body>
				</html>';

				$au_msg = '<tr><td style="color: #153643; font-family: Arial, sans-serif; font-size: 13px; line-height: 20px;font-weight:600;padding-bottom:10px;">Hello,<br>There is a request from ' . esc_html( $name ) . '.<br>Message : ' . esc_html( $summary ) . '<br>Contact Email : ' . esc_html( $email ) . '</td></tr>';

				if ( is_user_logged_in() ) {
					$uid            = get_current_user_id();
					$products_array = get_user_meta( $uid, 'products_to_quote', true );

					foreach ( $products_array as $key => $prod_id ) {
						$_product   = get_post( $prod_id );
						$author     = $_product->post_author;
						$prd_author = get_userdata( $author );
						$to         = $prd_author->data->user_email;

						if ( ! in_array( $to, $authorwise_product_id ) ) {
							$authorwise_product_id[ $to ][] = $prod_id;
						} else {
							$authorwise_product_id[ $to ][] = $prod_id;
						}
					}

					foreach ( $authorwise_product_id as $author_email => $product_ids ) {

						foreach ( $product_ids as $key => $product_id ) {
							$_product      = get_post( $product_id );
							$_product_link = get_permalink( $product_id );
							$author        = $_product->post_author;
							$prd_author    = get_userdata( $author );
							$to            = $prd_author->data->user_email;
							$author_uname  = $prd_author->data->display_name;

							$category_name = '';
							$categories    = '';
							$product_terms = get_the_terms( $product_id, 'product_cat' );
							if ( is_array( $product_terms ) ) {
								foreach ( $product_terms as $key => $value ) {
									$category_name .= $value->name . ',';
								}
							}
							if ( $category_name ) {
								$category_name = rtrim( $category_name, ',' );
								$categories    = '<td style="color: #153643; font-family: Arial, sans-serif;"><b>' . esc_html__( 'Category:', 'woocommerce-price-quote' ) . '</b>' . esc_html( $category_name ) . '</td>';
							}

							$quoted_products .= '<tr>
							<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
							<td width="260" valign="top">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
							<td align="center" style="color: #153643!important; font-family: Arial, sans-serif; font-size: 24px;padding-bottom:14px;">
							<a style="color:#153643!important; text-decoration:none;" href="' . esc_url( $_product_link ) . '">
							' . esc_html( $_product->post_title ) . '</a>
							</td>
							</tr>
							</table>
							</td>
							<td style="font-size: 0; line-height: 0;" width="20">
							&nbsp;
							</td>
							<td width="260" valign="top">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
							<td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
							' . wp_kses_post( $_product->post_excerpt ) . '
							</td>
							</tr>
							<tr>
							<td style="color: #153643; font-family: Arial, sans-serif; padding-top:20px;"><b>Vendor : </b><a href="mailto:' . esc_url( $to ) . '">' . esc_html( $author_uname ) . '</a></td>
							</tr>
							<tr>
							' . $categories . '
							</tr>
							</table>
							</td>
							</tr>
							</table>
							</td>
							</tr><hr style="border-style: solid; border-color: #8c8b8b; border-width: 1px 0 0 0;padding-bottom:5px;">';

							$quoted_author_products .= '<tr>
							<td>
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
							<td width="260" valign="top">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
							<td align="center" style="color: #153643!important; font-family: Arial, sans-serif; font-size: 24px;padding-bottom:14px;">
							<a style="color:#153643!important; text-decoration:none;" href="' . esc_url( $_product_link ) . '">
							' . esc_html( $_product->post_title ) . '</a>
							</td>
							</tr>
							</table>
							</td>
							<td style="font-size: 0; line-height: 0;" width="20">
							&nbsp;
							</td>
							<td width="260" valign="top">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
							<td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
							' . wp_kses_post( $_product->post_excerpt ) . '
							</td>
							</tr>
							<tr>
							<td style="color: #153643; font-family: Arial, sans-serif; padding-top:20px;"><b>Vendor : </b><a href="mailto:' . esc_url( $to ) . '">' . esc_html( $author_uname ) . '</a></td>
							</tr>
							<tr>
							' . $categories . '
							</tr>
							</table>
							</td>
							</tr>
							</table>
							</td>
							</tr><hr style="border-style: solid; border-color: #8c8b8b; border-width: 1px 0 0 0;padding-bottom:5px;">';
						}

						$headers                = array( 'Content-Type: text/html; charset=UTF-8', 'From: Me Myself <me@example.net>' );
						$author_content         = $author_msg . $au_msg . $quoted_author_products . $customer_msg_footer;
						$mail                   = wp_mail( $author_email, $subject, $author_content, $headers );
						$quoted_author_products = '';
						if ( $mail ) {

							$return_responce = array(
								'user_logged_in' => true,
							);

						}
					}
				} else {
					$_product      = get_post( $product_id );
					$_product_link = get_permalink( $product_id );
					$author        = $_product->post_author;
					$prd_author    = get_userdata( $author );
					$to            = $prd_author->data->user_email;
					$author_uname  = $prd_author->data->display_name;

					$category_name = '';
					$categories    = '';
					$product_terms = get_the_terms( $product_id, 'product_cat' );
					if ( is_array( $product_terms ) ) {
						foreach ( $product_terms as $key => $value ) {
							$category_name .= $value->name . ',';
						}
					}
					if ( $category_name ) {
						$category_name = rtrim( $category_name, ',' );
						$categories    = '<td style="color: #153643; font-family: Arial, sans-serif;"><b>' . esc_html__( 'Category:', 'woocommerce-price-quote' ) . '</b>' . esc_html( $category_name ) . '</td>';
					}

					$quoted_products .= '<tr><td>
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					<td width="260" valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					<td align="center" style="color: #153643!important; font-family: Arial, sans-serif; font-size: 24px;padding-bottom:14px;">
					<a style="color:#153643!important; text-decoration:none;" href="' . esc_url( $_product_link ) . '">
					' . esc_html( $_product->post_title ) . '</a>
					</td>
					</tr>
					</table>
					</td>
					<td style="font-size: 0; line-height: 0;" width="20">
					&nbsp;
					</td>
					<td width="260" valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					<td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
					' . wp_kses_post( $_product->post_excerpt ) . '
					</td>
					</tr>
					<tr>
					<td style="color: #153643; font-family: Arial, sans-serif; padding-top:20px;"><b>Vendor : </b><a href="mailto:' . esc_url( $to ) . '">' . esc_html( $author_uname ) . '</a></td>
					</tr>
					<tr>
					' . $categories . '
					</tr>
					</table>
					</td>
					</tr>
					</table>
					</td>
					</tr><hr style="border-style: solid; border-color: #8c8b8b; border-width: 1px 0 0 0;padding-bottom:5px;">';

					$quoted_author_products .= '<tr>
					<td>
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					<td width="260" valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					<td align="center" style="color: #153643!important; font-family: Arial, sans-serif; font-size: 24px;padding-bottom:14px;">
					<a style="color:#153643!important; text-decoration:none;" href="' . esc_url( $_product_link ) . '">
					' . esc_html( $_product->post_title ) . '</a>
					</td>
					</tr>
					</table>
					</td>
					<td style="font-size: 0; line-height: 0;" width="20">
					&nbsp;
					</td>
					<td width="260" valign="top">
					<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
					<td style="color: #153643; font-family: Arial, sans-serif; font-size: 16px; line-height: 20px;">
					' . wp_kses_post( $_product->post_excerpt ) . '
					</td>
					</tr>
					<tr>
					<td style="color: #153643; font-family: Arial, sans-serif; padding-top:20px;"><b>Vendor : </b><a href="mailto:' . esc_url( $to ) . '">' . esc_html( $author_uname ) . '</a></td>
					</tr>
					<tr>
					' . $categories . '
					</tr>
					</table>
					</td>
					</tr>
					</table>
					</td>
					</tr><hr style="border-style: solid; border-color: #8c8b8b; border-width: 1px 0 0 0;padding-bottom:5px;">';

					$headers                = array( 'Content-Type: text/html; charset=UTF-8', 'From: Me Myself <me@example.net>' );
					$author_content         = $author_msg . $au_msg . $quoted_author_products . $customer_msg_footer;
					$mail                   = wp_mail( $to, $subject, $author_content, $headers );
					$quoted_author_products = '';

					if ( $mail ) {

							$return_responce = array(
								'user_logged_in' => false,
							);

					}
				}

				$customer_msg .= $quoted_products . $customer_msg_footer;
				$headers       = array( 'Content-Type: text/html; charset=UTF-8' );

				if ( $send_user_copy == 'yes' ) {
					$subject_usr = esc_html__( 'Quoted Products', 'woocommerce-price-quote' );
					wp_mail( $email, $subject_usr, $customer_msg, $headers );
				}

				if ( is_user_logged_in() ) {
					$uid             = get_current_user_id();
					$products_quoted = get_user_meta( $uid, 'my_quoted_products', true );
					if ( ! is_array( $products_quoted ) ) {
						$products_quoted = array();
					}
					foreach ( $products_array as $key => $value ) {
						if ( ! in_array( $value, $products_quoted ) ) {
							$products_quoted[] = $value;
						}
					}
					update_user_meta( $uid, 'my_quoted_products', $products_quoted );
					$updated_array = array();
					update_user_meta( $uid, 'products_to_quote', $updated_array );
				}
			}
			wp_send_json_success( $return_responce );
		}

		public function wcpq_my_quoted_products_content() {
			$file = WCPQ_PLUGIN_PATH . 'shortcodes/my-quoted-products.php';
			if ( file_exists( $file ) ) {
				include_once $file;
			}
		}

		public function wcqp_quote_form_popup() {
			if ( ! is_user_logged_in() ) {
				if ( is_product() && ! is_shop() ) {
					global $product;
					$p_id = $product->get_id();
					?>
						<p class="woocommerce-info multi-quote-message"><?php echo esc_html__( 'Quotation saved for the products. We will get back to you soon with your details.', 'woocommerce-price-quote' ); ?> </p>
						<div id="wcqp_enquery_popup" class="wcpq-enquery-modal-box">
							<a href="#" class="wcpq-modal-close close">×</a>
							<div class="wcpq-enquery-modal-body">
								<div class="wcpq-product-enquiry">
									<p style="font-size: 27px;"><?php esc_html_e( 'Quotation details', 'woocommerce-price-quote' ); ?></p>
									<table class="wcpq-enquiry-tbl">
										<tr>
											<th><?php esc_html_e( 'Name', 'woocommerce-price-quote' ); ?></th>
											<td>
												<input type="text" placeholder="<?php esc_html_e( 'Your Name', 'woocommerce-price-quote' ); ?>" id="wcpq_enquiree_name" size="35">
											</td>
										</tr>
										<tr>
											<th><?php esc_html_e( 'Email', 'woocommerce-price-quote' ); ?></th>
											<td>
												<input type="email" placeholder="<?php esc_html_e( 'Your Email', 'woocommerce-price-quote' ); ?>" id="wcpq_enquiree_email" size="35">
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
												<input type="hidden" id="wcpq_quoted_products_id" value="<?php echo esc_attr( $p_id ); ?>">
												<input type="button" value="<?php esc_html_e( 'Send Quote', 'woocommerce-price-quote' ); ?>" id="wcpq_send_multiple_enquiry">
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>
					<?php
				}
			}
		}
	}

endif;
