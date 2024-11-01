<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wbcomdesigns.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Price_Quote
 * @subpackage Woocommerce_Price_Quote/admin
 */

if ( ! class_exists( 'Woocommerce_Price_Quote_Admin' ) ) :

	/**
	 * The admin-specific functionality of the plugin.
	 *
	 * Defines the plugin name, version, and two examples hooks for how to
	 * enqueue the admin-specific stylesheet and JavaScript.
	 *
	 * @package    Woocommerce_Price_Quote
	 * @subpackage Woocommerce_Price_Quote/admin
	 * @author     wbcomdesigns <admin@wbcomdesigns.com>
	 */
	class Woocommerce_Price_Quote_Admin {

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
		 * @param      string $plugin_name       The name of this plugin.
		 * @param      string $version    The version of this plugin.
		 */
		public function __construct( $plugin_name, $version ) {

			$this->plugin_name = $plugin_name;
			$this->version     = $version;

		}

		/**
		 * Register the stylesheets for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_styles( $hook ) {

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
			// if ( 'wb-plugins_page_woocommerce-price-quote' === $hook ) {.
			if ( ! wp_style_is( 'wbcom-selectize-css', 'enqueued' ) ) {
				wp_enqueue_style( 'wbcom-selectize-css', plugin_dir_url( __FILE__ ) . 'css/selectize.css' );
			}
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/woocommerce-price-quote-admin.css', array(), $this->version, 'all' );
			// }
		}

		/**
		 * Register the JavaScript for the admin area.
		 *
		 * @since    1.0.0
		 */
		public function enqueue_scripts( $hook ) {

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
			// if ( 'wb-plugins_page_woocommerce-price-quote' === $hook ) {.
				wp_enqueue_script( $this->plugin_name . '-selectize-js', plugin_dir_url( __FILE__ ) . 'js/selectize.min.js', array( 'jquery' ), $this->version, false );

				wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/woocommerce-price-quote-admin.js', array( 'jquery' ), $this->version, false );
			// }
		}

		/**
		 * Wbcom_hide_all_admin_notices_from_setting_page
		 *
		 * @return void
		 */
		public function wbcom_hide_all_admin_notices_from_setting_page() {
			$wbcom_pages_array  = array( 'wbcomplugins', 'wbcom-plugins-page', 'wbcom-support-page', 'wcpq-settings' );
			$wbcom_setting_page = filter_input( INPUT_GET, 'page' ) ? filter_input( INPUT_GET, 'page' ) : '';

			if ( in_array( $wbcom_setting_page, $wbcom_pages_array, true ) ) {
				remove_all_actions( 'admin_notices' );
				remove_all_actions( 'all_admin_notices' );
			}
		}

		/**
		 * Actions performed on loading admin_menu.
		 *
		 * @since    1.0.0
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function wcpq_add_submenu_page_admin_settings() {
			if ( empty( $GLOBALS['admin_page_hooks']['wbcomplugins'] ) && class_exists( 'WooCommerce' ) ) {
				add_menu_page(
					esc_html__( 'WB Plugins', 'woocommerce-price-quote' ),
					esc_html__( 'WB Plugins', 'woocommerce-price-quote' ),
					'manage_options',
					'wbcomplugins',
					array( $this, 'wcpq_admin_options_page' ),
					'dashicons-lightbulb',
					59
				);
				add_submenu_page(
					'wbcomplugins',
					esc_html__( 'General', 'woocommerce-price-quote' ),
					esc_html__( 'General', 'woocommerce-price-quote' ),
					'manage_options',
					'wbcomplugins'
				);

			}
			add_submenu_page(
				'wbcomplugins',
				esc_html__( 'Woo Product Inquiry & Quote', 'woocommerce-price-quote' ),
				esc_html__( 'Price Quote', 'woocommerce-price-quote' ),
				'manage_options',
				'wcpq-settings',
				array( $this, 'wcpq_admin_options_page' )
			);
		}

		/**
		 * Actions performed to create a submenu page content.
		 *
		 * @since    1.0.0
		 * @access public
		 */
		public function wcpq_admin_options_page() {
			global $allowedposttags;
			$tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'wcpq-welcome';
			?>
			<div class="wrap">
				
				<div class="wbcom-bb-plugins-offer-wrapper">
				<div id="wb_admin_logo">
					<a href="https://wbcomdesigns.com/downloads/buddypress-community-bundle/" target="_blank">
						<img src="<?php echo esc_url( WCPQ_PLUGIN_URL ) . 'admin/wbcom/assets/imgs/wbcom-offer-notice.png'; ?>">
					</a>
				</div>
			</div>
			<div class="wbcom-wrap">

			<div class="blpro-header">
					<div class="wbcom_admin_header-wrapper">
						<div id="wb_admin_plugin_name">
							<?php esc_html_e( 'Woo Product Inquiry & Quote ', 'woocommerce-price-quote' ); ?>
							<span><?php printf( __( 'Version %s', 'woocommerce-price-quote' ), WCPQ_VERSION  ); //phpcs:ignore?></span>
						</div>
						<?php echo do_shortcode( '[wbcom_admin_setting_header]' ); ?>
					</div>
				</div>
					<div class="wbcom-admin-settings-page">
						<?php
						settings_errors();
						$this->wcpq_plugin_settings_tabs();
						settings_fields( $tab );
						do_settings_sections( $tab );
						?>
					</div>
				</div>
			</div>
			<?php
		}

		/**
		 * Actions performed to create tabs on the sub menu page.
		 */
		public function wcpq_plugin_settings_tabs() {
			$current_tab = filter_input( INPUT_GET, 'tab' ) ? filter_input( INPUT_GET, 'tab' ) : 'wcpq-welcome';
			// xprofile setup tab.
			echo '<div class="wbcom-tabs-section"><div class="nav-tab-wrapper"><div class="wb-responsive-menu"><span>' . esc_html( 'Menu' ) . '</span><input class="wb-toggle-btn" type="checkbox" id="wb-toggle-btn"><label class="wb-toggle-icon" for="wb-toggle-btn"><span class="wb-icon-bars"></span></label></div><ul>';
			foreach ( $this->plugin_settings_tabs as $tab_key => $tab_caption ) {
				$active = $current_tab === $tab_key ? 'nav-tab-active' : '';
				echo '<li class=' . esc_attr( $tab_key ) . '><a class="nav-tab ' . esc_attr( $active ) . '" id="' . esc_attr( $tab_key ) . '-tab" href="?page=wcpq-settings' . '&tab=' . esc_attr( $tab_key ) . '">' . esc_attr( $tab_caption ) . '</a></li>';
			}
			echo '</div></ul></div>';
		}

		/**
		 * Actions performed on loading plugin settings
		 *
		 * @since    1.0.9
		 * @access   public
		 * @author   Wbcom Designs
		 */
		public function wcpq_init_plugin_settings() {

			$this->plugin_settings_tabs['wcpq-welcome'] = esc_html__( 'Welcome', 'woocommerce-price-quote' );
			register_setting( 'wcpq_admin_welcome_options', 'wcpq_admin_welcome_options' );
			add_settings_section( 'wcpq-welcome', ' ', array( $this, 'wcpq_admin_welcome_content' ), 'wcpq-welcome' );

			$this->plugin_settings_tabs['wcpq-general'] = esc_html__( 'General', 'woocommerce-price-quote' );
			register_setting( 'wcpq_gen_stngs', 'wcpq_gen_stngs' );
			add_settings_section( 'wcpq-general', ' ', array( $this, 'wcpq_admin_general_content' ), 'wcpq-general' );

			$this->plugin_settings_tabs['wcpq-enquiry-cart'] = esc_html__( 'Enquiry Cart ( PRO )', 'woocommerce-price-quote' );
			add_settings_section( 'wcpq-general', ' ', array( $this, 'wcpq_enquiry_cart_content' ), 'wcpq-enquiry-cart' );

			$this->plugin_settings_tabs['wcpq-product-category'] = esc_html__( 'Product Category ( PRO )', 'woocommerce-price-quote' );
			add_settings_section( 'wcpq-general', ' ', array( $this, 'wcpq_product_category_content' ), 'wcpq-product-category' );

			$this->plugin_settings_tabs['wcpq-single-product'] = esc_html__( 'Single Product ( PRO )', 'woocommerce-price-quote' );
			add_settings_section( 'wcpq-general', ' ', array( $this, 'wcpq_single_product_content' ), 'wcpq-single-product' );

			$this->plugin_settings_tabs['wcpq-advanced-setting'] = esc_html__( 'Advanced Setting ( PRO )', 'woocommerce-price-quote' );
			add_settings_section( 'wcpq-general', ' ', array( $this, 'wcpq_advanced_setting_content' ), 'wcpq-advanced-setting' );

		}

		/**
		 * Include Woo Product Inquiry & Quote admin welcome setting tab content file.
		 */
		public function wcpq_admin_welcome_content() {
			include 'inc/wcpq-welcome-page.php';
		}

		/**
		 * Include Woo Product Inquiry & Quote admin genral setting tab content file.
		 */
		public function wcpq_admin_general_content() {
			include 'inc/wcpq-general-setting-tab.php';
		}

		/**
		 * Include Woo Product Inquiry & Quote admin enquiry cart tab content file.
		 */
		public function wcpq_enquiry_cart_content() {
			include 'inc/wcpq-enquiry-cart-settings.php';
		}
		/**
		 * Include Woo Product Inquiry & Quote admin product category tab content file.
		 */
		public function wcpq_product_category_content() {
			include 'inc/wcpq-product-category-settings.php';
		}

		/**
		 * Include Woo Product Inquiry & Quote admin single product tab content file.
		 */
		public function wcpq_single_product_content() {
			include 'inc/wcpq-single-product-settings.php';
		}

		/**
		 * Include Woo Product Inquiry & Quote admin advanced setting tab content file.
		 */
		public function wcpq_advanced_setting_content() {
			include 'inc/wcpq-advanced-settings.php';
		}

	}

endif;
